<template>
    <h3 class="font-semibold text-xl mb-3">Vehicle Damages</h3>
    <Button label="Add Damage" icon="pi pi-plus" @click="openAddDamageDialog" />

    <DataTable :value="damages" selectionMode="single" :metaKeySelection="false" dataKey="id" tableStyle="min-width: 50rem" @row-click="openDamageDetails">
      <Column field="damagePositionLabel" header="Damage Position" :body="getDamagePositionLabel"></Column>
      <Column field="damageSeverityLabel" header="Damage Severity" :body="getDamageSeverityLabel"></Column>
      <Column field="damageTypeLabel" header="Damage Type" :body="getDamageTypeLabel"></Column>
      <Column field="damageDescription" header="Damage Description"></Column>
      <Column field="images" header="Images">
        <template #body="slotProps">
          <span>{{ slotProps.data.images?.length || 0 }} images</span>
        </template>
      </Column>
    </DataTable>

    <Dialog :header="dialogHeader" v-model:visible="showDialog" :modal="true" :closable="true">
      <div class="p-fluid">
        <div class="p-field">
          <label for="damagePosition">Damage Position</label>
          <Select id="damagePosition" v-model="currentDamage.damagePosition" :options="damagePositions" optionLabel="label" optionValue="value" placeholder="Select a Position" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
        </div>
        <div class="p-field">
          <label for="damageSeverity">Damage Severity</label>
          <Select id="damageSeverity" v-model="currentDamage.damageSeverity" :options="damageSeverities" optionLabel="label" optionValue="value" placeholder="Select Severity" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
        </div>
        <div class="p-field">
          <label for="damageType">Damage Type</label>
          <Select id="damageType" v-model="currentDamage.damageType" :options="damageTypes" optionLabel="label" optionValue="value" placeholder="Select Type" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
        </div>
        <div class="p-field">
          <label for="damageDescription">Damage Description</label>
          <Textarea v-model="currentDamage.damageDescription" rows="5" cols="30" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
        </div>
        <div class="p-field mt-3">
          <label for="damageImages">Damage Images</label>
          <FileUpload
            v-if="!isViewMode"
            ref="fileUpload"
            :multiple="true"
            accept="image/*"
            :maxFileSize="5000000"
            @select="onSelect"
            :customUpload="true"
            :auto="true"
            chooseLabel="Select Images"
            class="mt-2"
          >
            <template #empty>
              <p>Drag and drop images here to upload.</p>
            </template>
          </FileUpload>
        </div>
        <div v-if="(currentDamage.images?.length > 0 || currentDamage.tempImages?.length > 0)" class="damage-images">
          <h4 class="mb-2">Images:</h4>
          <div class="image-grid">
            <!-- Immagini già salvate -->
            <div v-for="(image, index) in currentDamage.images" :key="'saved-' + index" class="image-container">
              <img :src="image.url" :alt="'Damage image ' + (index + 1)" />
              <Button
                v-if="!isViewMode"
                icon="pi pi-trash"
                class="p-button-danger p-button-rounded delete-image-btn"
                @click="removeImage(index, false)"
              />
            </div>
            <!-- Immagini temporanee -->
            <div v-for="(image, index) in currentDamage.tempImages" :key="'temp-' + index" class="image-container">
              <img :src="image.url" :alt="'New damage image ' + (index + 1)" />
              <Button
                v-if="!isViewMode"
                icon="pi pi-trash"
                class="p-button-danger p-button-rounded delete-image-btn"
                @click="removeImage(index, true)"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="p-dialog-footer">
        <Button v-if="isViewMode" label="Edit" icon="pi pi-pencil" @click="enableEditMode" />
        <Button v-if="!isViewMode" label="Cancel" icon="pi pi-times" @click="closeDialog" class="p-button-text" />
        <Button v-if="!isViewMode" label="Save" icon="pi pi-check" @click="saveDamage" />
        <Button v-if="!isViewMode && currentDamage.id" label="Delete" icon="pi pi-trash" @click="deleteDamage" class="p-button-danger" />
      </div>
    </Dialog>
  </template>

  <script>
import { ref, watch, getCurrentInstance, onUnmounted } from 'vue';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Dialog from 'primevue/dialog';
  import Button from 'primevue/button';
  import Textarea from 'primevue/textarea';
  import Select from 'primevue/select';
  import FileUpload from 'primevue/fileupload';
  import axios from 'axios';

  export default {
    name: 'VehicleDamage',
    props: {
      isViewMode: Boolean,
      isEditMode: Boolean,
      vehicle: Object,
      damages: Array
    },
    components: {
      DataTable,
      Column,
      Dialog,
      Button,
      Textarea,
      Select,
      FileUpload
    },
    setup(props) {
      const { proxy } = getCurrentInstance();
      const showDialog = ref(false);
      const isViewMode = ref(false);
      const isEditMode = ref(false);
      const dialogHeader = ref('');
      const damages = ref([...props.damages]);
      const currentDamage = ref({
        id: null,
        damagePosition: '',
        damageSeverity: '',
        damageType: '',
        damageDescription: '',
        images: [],
        tempImages: []
      });

      const damagePositions = ref([
        { label: 'Front Bumper', value: 'front_bumper' },
        { label: 'Rear Bumper', value: 'rear_bumper' },
        { label: 'Left Fender', value: 'left_fender' },
        { label: 'Right Fender', value: 'right_fender' },
        { label: 'Front Left Door', value: 'front_left_door' },
        { label: 'Front Right Door', value: 'front_right_door' },
        { label: 'Rear Right Door', value: 'rear_right_door' },
        { label: 'Rear Left Door', value: 'rear_left_door' }
      ]);

      const damageSeverities = ref([
        { label: 'Minor', value: 'minor' },
        { label: 'Moderate', value: 'moderate' },
        { label: 'Severe', value: 'severe' },
        { label: 'Very Severe', value: 'very_severe' }
      ]);

      const damageTypes = ref([
        { label: 'Scratch', value: 'scratch' },
        { label: 'Dent', value: 'dent' },
        { label: 'Crack', value: 'crack' },
        { label: 'Broken', value: 'broken' }
      ]);

      // Funzione per selezionare immagini senza upload immediato
      const onSelect = (event) => {
        const files = event.files;
        for (let file of files) {
          const previewUrl = URL.createObjectURL(file);
          currentDamage.value.tempImages.push({
            url: previewUrl,
            filename: file.name,
            file: file
          });
        }

        // Pulisci il file uploader
        if (proxy.$refs.fileUpload) {
          proxy.$refs.fileUpload.clear();
        }
      };

      // Funzione di pulizia delle risorse
      const cleanup = () => {
        if (currentDamage.value.tempImages) {
          currentDamage.value.tempImages.forEach(image => {
            if (image.url.startsWith('blob:')) {
              URL.revokeObjectURL(image.url);
            }
          });
        }
      };

      // Rimuove un'immagine (temporanea o salvata)
      const removeImage = (index, isTemp) => {
        if (isTemp) {
          const image = currentDamage.value.tempImages[index];
          URL.revokeObjectURL(image.url);
          currentDamage.value.tempImages.splice(index, 1);
        } else {
          currentDamage.value.images.splice(index, 1);
        }
      };

      // Chiude la finestra di dialogo e pulisce le risorse
      const closeDialog = () => {
        cleanup();
        currentDamage.value.tempImages = [];
        showDialog.value = false;
      };

      const getDamagePositionLabel = (rowData) => {
        const position = damagePositions.value.find(pos => pos.value === rowData.damagePosition);
        return position ? position.label : rowData.damagePosition;
      };

      const getDamageSeverityLabel = (rowData) => {
        const severity = damageSeverities.value.find(sev => sev.value === rowData.damageSeverity);
        return severity ? severity.label : rowData.damageSeverity;
      };

      const getDamageTypeLabel = (rowData) => {
        const type = damageTypes.value.find(typ => typ.value === rowData.damageType);
        return type ? type.label : rowData.damageType;
      };

      const openAddDamageDialog = () => {
        dialogHeader.value = 'Add Damage';
        isViewMode.value = false;
        isEditMode.value = true;
        currentDamage.value = {
          id: null,
          damagePosition: '',
          damageSeverity: '',
          damageType: '',
          damageDescription: '',
          images: [],
          tempImages: []
        };
        showDialog.value = true;
      };

      const openDamageDetails = (event) => {
        dialogHeader.value = 'Damage Details';
        isViewMode.value = true;
        isEditMode.value = false;
        currentDamage.value = {
          ...event.data,
          tempImages: [] // Assicuriamoci che non ci siano immagini temporanee in visualizzazione
        };
        showDialog.value = true;
      };

      const enableEditMode = () => {
        isViewMode.value = false;
        isEditMode.value = true;
      };

      // Salva il danno e carica le immagini solo in questo momento
      const saveDamage = async () => {
        try {
          // Carica tutte le immagini temporanee
          const uploadedImages = [];
          for (const image of currentDamage.value.tempImages) {
            const formData = new FormData();
            formData.append('image', image.file);

            const response = await axios.post('/damages/upload', formData, {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            });

            if (response.data) {
              uploadedImages.push({
                url: response.data.url,
                filename: response.data.filename
              });
              URL.revokeObjectURL(image.url);
            }
          }

          // Combina le immagini esistenti con quelle appena caricate
          const allImages = [...currentDamage.value.images, ...uploadedImages];

          // Crea l'oggetto danno finale
          const damageData = {
            id: currentDamage.value.id,
            damagePosition: currentDamage.value.damagePosition,
            damageSeverity: currentDamage.value.damageSeverity,
            damageType: currentDamage.value.damageType,
            damageDescription: currentDamage.value.damageDescription,
            images: allImages
          };

          if (currentDamage.value.id) {
            const index = damages.value.findIndex(damage => damage.id === currentDamage.value.id);
            if (index !== -1) {
              damages.value[index] = damageData;
            }
          } else {
            damages.value.push({ ...damageData, id: Date.now() });
          }

          damages.value = [...damages.value];
          showDialog.value = false;

          // Pulisci le immagini temporanee
          currentDamage.value.tempImages = [];
        } catch (error) {
          console.error('Failed to save damage:', error);
        }
      };

      const deleteDamage = () => {
        damages.value = damages.value.filter(damage => damage.id !== currentDamage.value.id);
        showDialog.value = false;
      };

      watch(() => props.damages, (newDamages) => {
        damages.value = [...newDamages];
      });

      // Pulisci le risorse quando il componente viene distrutto
      onUnmounted(cleanup);

      return {
        showDialog,
        isViewMode,
        isEditMode,
        dialogHeader,
        damages,
        currentDamage,
        damagePositions,
        damageSeverities,
        damageTypes,
        openAddDamageDialog,
        openDamageDetails,
        enableEditMode,
        saveDamage,
        deleteDamage,
        getDamagePositionLabel,
        getDamageSeverityLabel,
        getDamageTypeLabel,
        onSelect,
        removeImage,
        closeDialog
      };
    }
  };
  </script>

  <style>
  .full-width {
    width: 100%;
  }

  .select-readonly .p-dropdown,
  .select-readonly .p-inputtext {
    pointer-events: none;
  }

  .damage-images {
    margin-top: 1rem;
  }

  .image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
  }

  .image-container {
    position: relative;
    aspect-ratio: 1;
  }

  .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
  }

  .delete-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 2rem !important;
    height: 2rem !important;
  }

  .p-fileupload {
    width: 100%;
  }
  </style>
