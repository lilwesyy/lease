<template>
  <h1 v-if="!isViewMode" class="font-bold text-3xl">{{ $t('vehicle.addVehicle') }}</h1>
  <Breadcrumb v-if="!isViewMode" :model="items" class="custom-breadcrumb" />

  <Card class="isviewed">
    <template #content>
      <Tabs value="0">
        <TabList>
          <Tab value="0"><i class="pi pi-car mr-2"></i>{{ $t('vehicle.vehicleInformation') }}</Tab>
          <Tab value="1"><i class="pi pi-image mr-2"></i>{{ isViewMode ? t('vehicle.photos') : t('vehicle.addPhotos') }}</Tab>
          <Tab value="2"><i class="pi pi-cog mr-2"></i>{{ $t('vehicle.damages') }}</Tab>
        </TabList>
        <TabPanels>
          <TabPanel value="0">
            <FloatLabel v-if="isViewMode || isEditMode" variant="on">
              <Select v-model="status" :options="statusOptions" optionLabel="label" optionValue="value"
                @change="handleStatusChange" />
              <label for="status">{{ $t('vehicle.status') }}</label>
            </FloatLabel>
            <div class="vehicle-info">
              <img :src="brandLogo" width="400" :alt="selectedBrand + '-Logo'" />
              <div class="input-group">
                <FloatLabel variant="on">
                  <Select id="brand" class="full-width full-height" v-model="selectedBrand" :options="brands" filter
                    optionValue="value" optionLabel="label" placeholder="‎ "
                    :class="{ 'select-readonly': isViewMode && !isEditMode }">
                    <template #option="slotProps">
                      <div class="flex items-center">
                        <img :alt="slotProps.option.label" :src="slotProps.option.icon || '/brand-logo.svg'"
                          class="mr-2" style="width: 35px" />
                        <div>{{ slotProps.option.label }}</div>
                      </div>
                    </template>
                  </Select>
                  <label for="brand">Brand</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="model" class="full-width" :options="filteredModels" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="model">{{ $t('vehicle.fields.model') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="bodyType" class="full-width" :options="bodyTypes" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="bodyType">{{ $t('vehicle.fields.bodyType') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText type="number" class="full-width" v-model="year" :readonly="isViewMode && !isEditMode" />
                  <label for="year">{{ $t('vehicle.fields.year') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="location" class="full-width" :options="locations" optionLabel="name" optionValue="id"
                    :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="location">{{ $t('vehicle.fields.location') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText type="text" class="full-width" v-model="plate" :readonly="isViewMode && !isEditMode" />
                  <label for="plate">{{ $t('vehicle.fields.plate') }}</label>
                </FloatLabel>
              </div>
            </div>
            <div class="vehicle-specs">
              <Divider><strong>{{ $t('vehicle.specs') }}</strong></Divider>

              <div class="input-group">
                <FloatLabel variant="on">
                  <InputText v-model="odometer" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="odometer">{{ $t('vehicle.fields.odometer') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="externalColour" class="full-width" :options="colorOptions" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }">
                    <template #item="{ item }">
                      <div class="color-item">
                        <span class="color-bullet" :style="{ backgroundColor: item.value }"></span>
                        {{ item.label }}
                      </div>
                    </template>
                  </Select>
                  <label for="externalColour">{{ $t('vehicle.fields.color') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="passengers" class="full-width" :options="passengerOptions" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="passengers">{{ $t('vehicle.fields.passengers') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="fuelType" class="full-width" :options="fuelTypes" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="fuelType">{{ $t('vehicle.fields.fuelType') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="transmission" class="full-width" :options="transmissions" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="transmission">{{ $t('vehicle.fields.transmission') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="baseKmDay" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="baseKmDay">{{ $t('vehicle.fields.kmPerDay') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="kmExtraPrice" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="kmExtraPrice">{{ $t('vehicle.fields.extraKmPrice') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="basicDailyPrice" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="basicDailyPrice">{{ $t('vehicle.fields.basicDailyPrice') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="franchise" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="franchise">{{ $t('vehicle.fields.franchise') }}</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="deposit" class="full-width" type="number" :readonly="isViewMode && !isEditMode" />
                  <label for="deposit">{{ $t('vehicle.fields.deposit') }}</label>
                </FloatLabel>
              </div>
              <div v-if="isViewMode && !isEditMode">
                <Button @click="enableEditMode" :label="$t('common.edit')" icon="pi pi-pencil" class="p-button-primary mt-4" />
              </div>
              <div v-else-if="isEditMode">
                <Button @click="saveChanges" :label="$t('common.save')" icon="pi pi-check" />
                <Button @click="cancelEditMode" :label="$t('common.cancel')" icon="pi pi-times"
                  class="p-button-secondary mt-4 ml-2" />
                <Button @click="deleteVehicle" :label="$t('common.delete')" icon="pi pi-trash" class="p-button-danger mt-4 ml-2" />
              </div>
              <div v-if="!isViewMode && !isEditMode" class="mt-4">
                <Button @click="createVehicle" :label="$t('vehicle.createVehicle')" icon="pi pi-check" />
              </div>
            </div>
          </TabPanel>

          <TabPanel value="1">
            <div v-if="isViewMode" class="images-container">
              <div v-if="isViewMode" v-for="(slot, index) in fotoSlots" :key="index">
                <div class="no-images">
                  <div class="upload-container">
                    <FileUpload v-if="!src[index]" ref="fileupload" mode="basic" @select="onFileSelect($event, index)"
                      chooseLabel="Scegli" showUploadButton="false" customUpload auto name="fileupload"
                      url="/api/upload" accept="image/*" :maxFileSize="1000000" @upload="onUpload"
                      class="file-upload-button" />
                    <img v-if="!src[index]" :src="slot.imageSrc" alt="Image" class="uploaded-image" />
                    <div v-if="src[index]" class="uploaded-image-container">
                      <img :src="src[index]" alt="Image" class="uploaded-image" />
                      <button @click="removeImage(index)" class="remove-image-button">
                        <i class="pi pi-times"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div v-else class="images-container">
              <div v-for="(slot, index) in fotoSlots" :key="index">
                <div class="no-images">
                  <div class="upload-container">
                    <FileUpload v-if="!src?.[index]" ref="fileupload" mode="basic" @select="onFileSelect($event, index)"
                      chooseLabel="Scegli" showUploadButton="false" customUpload auto name="fileupload"
                      url="/api/upload" accept="image/*" :maxFileSize="1000000" @upload="onUpload"
                      class="file-upload-button" />
                    <img v-if="!src?.[index]" :src="slot.imageSrc" alt="Image" class="uploaded-image" />
                    <div v-if="src?.[index]" class="uploaded-image-container">
                      <img :src="src?.[index]" alt="Image" class="uploaded-image" />
                      <button @click="removeImage(index)" class="remove-image-button">
                        <i class="pi pi-times"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="isEditMode" class="mt-4">
              <Button @click="savePhotos" :label="$t('common.save')" icon="pi pi-check" />
              <Button @click="cancelEditMode" :label="$t('common.cancel')" icon="pi pi-times" class="p-button-secondary ml-2" />
            </div>
          </TabPanel>
          <TabPanel value="2">
            <div class="damage-section">
              <VehicleDamage :isViewMode="isViewMode" :isEditMode="isEditMode" :vehicle="vehicle" :damages="damages" />
            </div>
          </TabPanel>
        </TabPanels>
      </Tabs>
    </template>
  </Card>
</template>

<script setup>
import { ref, computed, watch, onMounted, inject } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import axios from 'axios';
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import FloatLabel from 'primevue/floatlabel';
import VehicleDamage from './VehicleDamage.vue';
import { fotoSlots } from './utils.js';
import { ref } from "vue";

export default {
  name: 'AddVehicle',
  emits: ['vehicle-updated'],
  props: {
    isViewMode: {
      type: Boolean,
      default: false
    },
    vehicle: {
      type: Object,
      default: () => ({
        make: '',
        model: '',
        bodyType: '',
        year: '',
        location: '',
        plateNumber: '',
        odometer: '',
        color: '',
        seats: '',
        fuel_type: '',
        transmission: '',
        km_per_day: '',
        extra_km_price: '',
        basic_daily_price: '',
        franchise: '',
        deposit: '',
        imageUrl: '',
        status: '',
        damages: []
      })
    },
  },
  components: {
    Breadcrumb,
    Card,
    Tabs,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
    InputText,
    Select,
    Divider,
    FileUpload,
    Button,
    Textarea,
    DataTable,
    Column,
    ColumnGroup,
    Row,
    FloatLabel,
    VehicleDamage
  },
  data() {
    return {
      existingPhotos: [],
      src: [null, null, null, null, null, null],
      fotoSlots: fotoSlots,
      fileInputs: [],
      isEditMode: false,
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Add Vehicles', url: '/dashboard/add-vehicles' }
      ],
      photos: [null, null, null],
      damagePhotos: [null, null, null],
      photoIds: [null, null, null],
      damageDescription: '',
      damageSeverity: '',
      damages: this.vehicle ? this.vehicle.damages : [
        {
          damagePosition: 'Front Bumper',
          damageSeverity: 'High',
          damageType: 'Dent',
          damageDescription: 'Severe dent and scratches'
        }
      ],
      selectedBrand: '',
      model: '',
      bodyType: '',
      year: '',
      location: '',
      plate: '',
      odometer: '',
      externalColour: '',
      passengers: '',
      fuelType: '',
      transmission: '',
      baseKmDay: '',
      kmExtraPrice: '',
      basicDailyPrice: '',
      franchise: '',
      deposit: '',
      imageUrl: '',
      status: '',
      statusOptions: [
        { label: 'Available', value: 'available' },
        { label: 'Unavailable', value: 'unavailable' },
        { label: 'Maintenance', value: 'maintenance' }
      ],
      brands: [],
      models: {},
      damageSeverities: [
        { label: 'Minor', value: 'minor' },
        { label: 'Moderate', value: 'moderate' },
        { label: 'Severe', value: 'severe' }
      ],
      bodyTypes: [
        { label: 'Wagon/Estate', value: 'wagon' },
        { label: 'Passenger Van', value: 'van' },
        { label: 'Sedan/Limousine', value: 'sedan' },
        { label: 'Convertible', value: 'convertible' },
        { label: 'Sport', value: 'sport' },
        { label: 'SUV/4x4', value: 'suv' }
      ],
      locations: [],
      fuelTypes: [
        { label: 'Petrol', value: 'petrol' },
        { label: 'Diesel', value: 'diesel' },
        { label: 'Electric', value: 'electric' },
        { label: 'Hybrid', value: 'hybrid' }
      ],
      transmissions: [
        { label: 'Automatic', value: 'automatic' },
        { label: 'Manual', value: 'manual' }
      ],
      colorOptions: [
        { label: 'Red', value: 'red', style: 'background-color: red;' },
        { label: 'Blue', value: 'blue', style: 'background-color: blue;' },
        { label: 'Green', value: 'green', style: 'background-color: green;' },
        { label: 'Black', value: 'black', style: 'background-color: black;' },
        { label: 'White', value: 'white', style: 'background-color: white;' },
        { label: 'Silver', value: 'silver', style: 'background-color: silver;' }
      ],
      passengerOptions: [
        { label: '2', value: 2 },
        { label: '4', value: 4 },
        { label: '5', value: 5 },
        { label: '7', value: 7 }
      ],
      originalData: {}
    };
  },
  mounted() {
    if (window.location.pathname === "/dashboard/add-vehicles") {
      this.src = [null, null, null, null, null, null]
      }

    this.fetchLocations();
    this.fetchBrandsAndModels();
  },
  watch: {
    vehicle: {
      handler(newVal) {
        // Only update values that are actually present in the new vehicle object
        if (newVal.make?.id) this.selectedBrand = newVal.make.id;
        if (newVal.model?.id) this.model = newVal.model.id;

        // For other properties, use the nullish coalescing operator to keep
        // existing values if the new ones are null/undefined
        this.bodyType = newVal.bodyType ?? this.bodyType;
        this.year = newVal.year ?? this.year;
        this.location = newVal.location_id ?? this.location;
        this.plate = newVal.plateNumber ?? this.plate;
        this.odometer = newVal.odometer ?? this.odometer;
        this.externalColour = newVal.color ?? this.externalColour;
        this.passengers = newVal.seats ?? this.passengers;
        this.fuelType = newVal.fuel_type ?? this.fuelType;
        this.transmission = newVal.transmission ?? this.transmission;
        this.baseKmDay = newVal.km_per_day ?? this.baseKmDay;
        this.kmExtraPrice = newVal.extra_km_price ?? this.kmExtraPrice;
        this.basicDailyPrice = newVal.basic_daily_price ?? this.basicDailyPrice;
        this.franchise = newVal.franchise ?? this.franchise;
        this.deposit = newVal.deposit ?? this.deposit;
        this.src = newVal.images?.map(image => image.url) ?? this.images;
        this.status = newVal.status ?? this.status;
      },
      immediate: true,
      deep: true
    },
    selectedBrand(newBrand) {
      this.model = '';
    }
  },
  methods: {
    onFileSelect(event, index) {
      if (window.location.pathname === "/dashboard/vehicles") {
        this.isEditMode = true;
      }

      const file = event.files[0];
      const reader = new FileReader();

      reader.onload = async (e) => {
        this.src[index] = e.target.result; // Imposta l'immagine nell'array basato sull'indice
      };

      reader.readAsDataURL(file);
    },
    removeImage(index) {
      if (window.location.pathname === "/dashboard/vehicles") {
        this.isEditMode = true;
      }
      this.src[index] = null; // Rimuove l'immagine selezionata
    },
    fetchLocations() {
      axios.post('/location')
        .then(response => {
          // Your API returns an array of location objects with id and name properties
          this.locations = response.data;
          // console.log('Locations loaded:', this.locations);
        })
        .catch(error => {
          console.error('Error fetching locations:', error);
          this.$showToast('error', 'Error', 'Failed to load locations');
        });
    },
    triggerFileInput(index) {
      // Usa un timeout breve per assicurarsi che l'evento di click sia completato
      setTimeout(() => {
        const inputElement = document.getElementById(`file-upload-${index}`);
        if (inputElement) {
          inputElement.click();
        } else {
          console.error(`Input file con id file-upload-${index} non trovato`);
        }
      }, 10);
    },
    handleFileUpload(event, index) {
      this.isEditMode = true;
      const file = event.target.files[0];
      if (!file) {
        return;
      }

  // Size validation
  if (file.size > 5000000) {
    showToast('error', 'Error', 'File size must be less than 5MB');
    return;
  }

      const reader = new FileReader();
      reader.onload = (e) => {
        // Aggiorna l'array delle foto con la nuova immagine
        this.photos[index] = e.target.result;
      };
      reader.onerror = (e) => {
        console.error("Errore durante la lettura del file:", e);
        this.$showToast('error', 'Error', 'Failed to read the image file');
      };
      reader.readAsDataURL(file);
    },
    getVehicleImageUrl(index) {
      // Controlla se il veicolo esiste e ha immagini
      if (this.vehicle && this.vehicle.images && this.vehicle.images.length > 0) {
        // Cerca un'immagine con la posizione corrispondente all'indice
        const image = this.vehicle.images.find(img => img.position === index);

    if (image && image.url) {
      return image.url;
    }

    if (props.vehicle.images[index] && props.vehicle.images[index].url) {
      return props.vehicle.images[index].url;
    }
  }

  return '/images/no-image.png';
};

const handleStatusChange = () => {
  const currentMake = selectedBrand.value;
  const currentModel = model.value;

  if ((props.isViewMode || isEditMode.value) && props.vehicle && props.vehicle.id) {
    const statusUpdate = {
      id: props.vehicle.id,
      status: status.value
    };

    axios.put(`/vehicles/update-status/${props.vehicle.id}`, statusUpdate)
      .then(response => {
        showToast('success', 'Success', 'Vehicle status updated successfully');

        if (response.data && response.data.vehicle) {
          const updatedVehicle = response.data.vehicle;

          const mergedVehicle = {
            ...props.vehicle,
            ...updatedVehicle,
            make: props.vehicle.make,
            model: props.vehicle.model
          };

          status.value = updatedVehicle.status || status.value;

              // Emit the merged vehicle data
              this.$root.$emit('vehicle-status-updated', mergedVehicle);
              this.$emit('vehicle-updated', mergedVehicle);
            }
          })
          .catch(error => {
            console.error('Error updating status:', error);
            this.$showToast('error', 'Error', 'Failed to update vehicle status');

            // Restore previous status in case of error
            this.status = this.vehicle.status;
          });
      }
    },
    createVehicle() {
      const uploads = [];
      // Validate required fields
      if (!this.selectedBrand || !this.model || !this.year || !this.plate) {
        this.$showToast('error', 'Error', 'Please fill in all required fields');
        return;
      }

      const newVehicle = {
        make_id: this.selectedBrand,
        model_id: this.model,
        bodyType: this.bodyType,
        year: this.year,
        location_id: this.location,
        plateNumber: this.plate,
        odometer: this.odometer,
        color: this.externalColour,
        seats: this.passengers,
        fuel_type: this.fuelType,
        transmission: this.transmission,
        km_per_day: this.baseKmDay,
        extra_km_price: this.kmExtraPrice,
        basic_daily_price: this.basicDailyPrice,
        dailyPrice: this.basicDailyPrice,
        franchise: this.franchise,
        deposit: this.deposit,
        status: 'available' // Default status for new vehicles
      };

      // this.src.forEach((photo, index) => {
      //   // Aggiungi solo le foto che iniziano con 'data:', indicando che sono nuove immagini da caricare
      //   if (photo && photo.startsWith('data:')) {
      //     const file = this.dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      //     uploads.push({ file, index });
      //   }
      // });

      // if (uploads.length === 0) {
      //   this.$showToast('info', 'Info', 'No new photos to upload');
      //   return;
      // }

      // Create a form data object to send all photos


  const formData = new FormData();

  Object.keys(newVehicle).forEach(key => {
    formData.append(key, newVehicle[key]);
  });

      this.src.forEach((photo, index) => {
        // Aggiungi solo le nuove immagini (quelle che iniziano con 'data:')
        if (photo && photo.startsWith("data:")) {
          const file = this.dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
          uploads.push({ file, index });
        }
      });


      uploads.forEach(({ file, index }) => {
        formData.append(`photos[${index}]`, file); // Aggiunge il file a FormData
        formData.append(`indices[${index}]`, index); // Aggiunge l'indice
      });

      // this.photos.forEach((photo, index) => {
      //   if (photo) {
      //     hasPhotos = true;
      //     // Convert base64 back to file if needed
      //     const photoFile = this.dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      //     formData.append(`photos[${index}]`, photoFile);
      //     formData.append(`indices[${index}]`, index);
      //   }
      // });
      formData.append('vehicle_id', this.vehicle.id);
      // Send the request to the API
      axios.post('/vehicles/create', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(response => {
          this.$showToast('success', 'Success', 'Vehicle created successfully');

          // Reset form or redirect to vehicles list
          this.resetForm();

          // Optionally navigate to the vehicles list
          this.$router.push('/dashboard/vehicles');
        })
        .catch(error => {
          console.error('Error creating vehicle:', error);
          this.$showToast('error', 'Error', 'Failed to create vehicle');
        });
    },

const dataURLtoFile = (dataURL, filename) => {
  const arr = dataURL.split(',');
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);

  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }

  return new File([u8arr], filename, { type: mime });
};

const resetForm = () => {
  selectedBrand.value = '';
  model.value = '';
  bodyType.value = '';
  year.value = '';
  location.value = '';
  plate.value = '';
  odometer.value = '';
  externalColour.value = '';
  passengers.value = '';
  fuelType.value = '';
  transmission.value = '';
  baseKmDay.value = '';
  kmExtraPrice.value = '';
  basicDailyPrice.value = '';
  franchise.value = '';
  deposit.value = '';
  photos.value = [null, null, null];
};

const fetchBrandsAndModels = async () => {
  try {
    const response = await axios.post('/vehicle-makes');
    brands.value = response.data.map(make => ({ label: make.name, value: make.id, icon: make.icon }));
    models.value = response.data.reduce((acc, make) => {
      acc[make.id] = make.models.map(model => ({ label: model.name, value: model.id }));
      return acc;
    }, {});

          // Se siamo in modalità visualizzazione e abbiamo un veicolo
          if (this.vehicle && this.isViewMode) {
            // Imposta il brand basato sull'ID
            this.selectedBrand = this.vehicle.make?.id || '';

            // Imposta il modello dopo un breve ritardo per assicurarsi che i modelli filtrati siano disponibili
            setTimeout(() => {
              this.model = this.vehicle.model?.id || '';
            }, 100);
          }
        })
        .catch(error => {
          console.error('Error fetching vehicle makes and models:', error);
        });
    },
    // onFileSelect(event, index) {
    //   const file = event.files[0];
    //   if (!file) return;

    //   // Size validation
    //   if (file.size > 5000000) {
    //     this.$showToast('error', 'Error', 'File size must be less than 5MB');
    //     return;
    //   }

    //   const reader = new FileReader();
    //   reader.onload = (e) => {
    //     // Aggiorna l'array delle foto con la nuova immagine
    //     this.photos[index] = e.target.result;

    //     console.log(`Immagine caricata in index ${index}:`, this.photos[index].substring(0, 50) + '...');
    //   };
    //   reader.readAsDataURL(file);
    // },

    removePhoto(index) {
      // Replace this.$set with direct assignment
      this.photos[index] = null;
    },

    uploadPhoto(file, index) {
      const formData = new FormData();
      formData.append('photo', file);
      formData.append('index', index);

      if (this.vehicle && this.vehicle.id) {
        formData.append('vehicle_id', this.vehicle.id);
      }

      axios.post('/vehicles/upload-photo', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(response => {
          this.$showToast('success', 'Success', 'Photo uploaded successfully');

          // If we have a photo ID from the server, store it
          if (response.data && response.data.photo_id) {
            // Create or update photoIds array
            if (!this.photoIds) this.photoIds = Array(3).fill(null);
            // Replace this.$set with direct assignment
            this.photoIds[index] = response.data.photo_id;
          }
        })
        .catch(error => {
          console.error('Error uploading photo:', error);
          this.$showToast('error', 'Error', 'Failed to upload photo');
        });
    },

    savePhotos() {
      // Collect all files that need to be uploaded

  const formData = new FormData();
  formData.append('vehicle_id', props.vehicle.id);


  axios.post('/vehicles/upload-multiple-photos', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(response => {
      showToast('success', 'Success', 'Photos saved successfully');
      let tmpPh = JSON.parse(JSON.stringify(src.value));
      emit('vehicle-updated-photo');
    })
    .catch(error => {
      console.error('Error saving photos:', error);
      showToast('error', 'Error', 'Failed to save photos');
    });
};

const onDamagePhotoSelect = (index) => {
  return (event) => {
    const file = event.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
      damagePhotos.value[index] = e.target.result;
    };
    reader.readAsDataURL(file);
  };
};

const enableEditMode = (tabValue) => {
  if (props.vehicle && props.vehicle.images && props.vehicle.images.length > 0) {
    props.vehicle.images.forEach((image, idx) => {
      if (idx < src.value.length) {
        src.value[idx] = image.url;
      }
    });
  }

      // Imposta la modalità di modifica
      this.isEditMode = true;

      // Se è specificato un valore di tab, impostiamo la tab corrente
      if (tabValue) {
        this.activeTabIndex = tabValue;
      }

      // Log per debug
      // Salva i dati originali per poterli ripristinare in caso di annullamento
      this.originalData = JSON.parse(JSON.stringify(this.$data));
    },
    saveChanges() {
      // Create the updated vehicle object
      const updatedVehicle = new FormData(); // Usare FormData invece di un oggetto normale

      updatedVehicle.append("id", this.vehicle.id);
      updatedVehicle.append("make_id", this.selectedBrand);
      updatedVehicle.append("model_id", this.model);
      updatedVehicle.append("bodyType", this.bodyType);
      updatedVehicle.append("year", this.year);
      updatedVehicle.append("location_id", this.location);
      updatedVehicle.append("plateNumber", this.plate);
      updatedVehicle.append("odometer", this.odometer);
      updatedVehicle.append("color", this.externalColour);
      updatedVehicle.append("seats", this.passengers);
      updatedVehicle.append("fuel_type", this.fuelType);
      updatedVehicle.append("transmission", this.transmission);
      updatedVehicle.append("km_per_day", this.baseKmDay);
      updatedVehicle.append("extra_km_price", this.kmExtraPrice);
      updatedVehicle.append("basic_daily_price", this.basicDailyPrice);
      updatedVehicle.append("dailyPrice", this.basicDailyPrice);
      updatedVehicle.append("franchise", this.franchise);
      updatedVehicle.append("deposit", this.deposit);
      updatedVehicle.append("status", this.status);

      


      // Send the update request to the API////////
      axios.put(`/vehicles/edit/${this.vehicle.id}`, updatedVehicle)
        .then(response => {
          this.isEditMode = false;

          // Log della risposta per debug
          // Aggiorna il veicolo locale con i dati ricevuti dal server
          if (response.data && response.data.vehicle) {
            const updatedVehicle = response.data.vehicle;

        window.dispatchEvent(new CustomEvent('vehicle-updated', { 
          detail: updatedVehicle
        }));

        emit('vehicle-updated', updatedVehicle);

        selectedBrand.value = updatedVehicle.make_id;
        model.value = updatedVehicle.model_id;
        bodyType.value = updatedVehicle.bodyType;
        year.value = updatedVehicle.year;
        location.value = updatedVehicle.location;
        plate.value = updatedVehicle.plateNumber;
        odometer.value = updatedVehicle.odometer;
        externalColour.value = updatedVehicle.color;
        passengers.value = updatedVehicle.seats;
        fuelType.value = updatedVehicle.fuel_type;
        transmission.value = updatedVehicle.transmission;
        baseKmDay.value = updatedVehicle.km_per_day;
        kmExtraPrice.value = updatedVehicle.extra_km_price;
        basicDailyPrice.value = updatedVehicle.basic_daily_price;
        franchise.value = updatedVehicle.franchise;
        deposit.value = updatedVehicle.deposit;
        status.value = updatedVehicle.status;
      }

      showToast('success', 'Success', 'Vehicle details updated successfully');
    })
    .catch(error => {
      console.error('Error updating vehicle:', error);
      showToast('error', 'Error', 'Failed to update vehicle details');
    });
};

const cancelEditMode = () => {
  // Restore original data
  selectedBrand.value = originalData.value.selectedBrand;
  model.value = originalData.value.model;
  bodyType.value = originalData.value.bodyType;
  year.value = originalData.value.year;
  location.value = originalData.value.location;
  plate.value = originalData.value.plate;
  odometer.value = originalData.value.odometer;
  externalColour.value = originalData.value.externalColour;
  passengers.value = originalData.value.passengers;
  fuelType.value = originalData.value.fuelType;
  transmission.value = originalData.value.transmission;
  baseKmDay.value = originalData.value.baseKmDay;
  kmExtraPrice.value = originalData.value.kmExtraPrice;
  basicDailyPrice.value = originalData.value.basicDailyPrice;
  franchise.value = originalData.value.franchise;
  deposit.value = originalData.value.deposit;
  status.value = originalData.value.status;
  src.value = originalData.value.src ? JSON.parse(JSON.stringify(originalData.value.src)) : [null, null, null, null, null, null];
  
  isEditMode.value = false;
};

const deleteVehicle = () => {
  if (confirm('Are you sure you want to delete this vehicle?')) {
    axios.delete(`/vehicles/delete/${props.vehicle.id}`)
      .then(response => {
        window.dispatchEvent(new CustomEvent('vehicle-deleted', { 
          detail: props.vehicle.id 
        }));
        
        showToast('success', 'Success', 'Vehicle deleted successfully');
        router.push('/dashboard/vehicles');
      })
      .catch(error => {
        console.error('Error deleting vehicle:', error);
        showToast('error', 'Error', 'Failed to delete vehicle');
      });
  }
};

// Lifecycle hooks
onMounted(() => {
  console.log(fotoSlots);
  console.log(src.value, 'src.value');
  
  fetchLocations();
  fetchBrandsAndModels();
});
</script>

<style scoped>
/* Keeping the same styles from the original component */
.vehicle-info {
  display: flex;
  align-items: center;
}

.input-group {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: space-between;
}

.input-group>* {
  flex: 1 1 calc(50% - 1rem);
}

.vehicle-specs {
  margin-top: 2rem;
}

.vehicle-specs .input-group>* {
  flex: 1 1 calc(33.333% - 1rem);
}

.images-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  width: 100%;
  padding: 10px;
}

.no-images {
  height: 350px;
  background-color: #f8f9fa;
  border: 1px dashed #ccc;
  border-radius: 4px;
  color: #6c757d;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

@media (max-width: 768px) {
  .images-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .images-container {
    grid-template-columns: 1fr;
  }
}

.color-option {
  display: flex;
  align-items: center;
}

.color-ball {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  margin-right: 8px;
}

.color-item {
  display: flex;
  align-items: center;
}

.color-bullet {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  margin-right: 8px;
}

.damages-section {
  margin-top: 2rem;
}

.damages-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.damage-item {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.vehicle-image {
  margin-top: 1rem;
  border-radius: 8px;
}

.photo-upload-grid {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
}

.photo-upload-item {
  flex: 1 1 calc(33.333% - 1rem);
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px dashed #ccc;
  padding: 1rem;
  height: 200px;
  position: relative;
  background-color: #f8f9fa;
  overflow: hidden;
}

.uploaded-photo {
  width: 100%;
  height: 100%;
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
  object-position: center;
}

.isViewMode .photo-upload-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
}

.isViewMode .photo-upload-item {
  flex: 0 0 calc(33.333% - 1rem);
  height: 240px;
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 4px;
  overflow: hidden;
  transition: transform 0.2s;
}

.isViewMode .photo-upload-item:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.full-width {
  width: 100%;
}

.photo-container {
  position: relative;
  width: 100%;
  height: 100%;
}

.delete-photo-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  opacity: 0.8;
}

.delete-photo-btn:hover {
  opacity: 1;
}

.photo-upload-item {
  flex: 1 1 calc(33.333% - 1rem);
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px dashed #ccc;
  padding: 1rem;
  height: 200px;
  position: relative;
  background-color: #f8f9fa;
}

.add-photo-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 100%;
  cursor: pointer;
}

.add-photo-button {
  cursor: pointer;
  transition: background-color 0.2s;
}

.add-photo-button:hover {
  background-color: #e9ecef;
}

.clickable {
  cursor: pointer;
}

.upload-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

:deep(.upload-button) {
  width: 90%;
  height: auto;
  margin: 0 auto;
  display: block;
}

:deep(.upload-button .p-button) {
  background-color: #f3f3f3;
  border: 1px solid #ced4da;
  color: #495057;
  font-weight: normal;
  width: 100%;
  padding: 0.75rem;
}

:deep(.upload-button .p-button:hover) {
  background-color: #e9ecef;
  border-color: #ced4da;
  color: #495057;
}

.upload-container .p-fileupload-buttonbar {
  display: flex;
  justify-content: center;
  width: 100%;
}

.upload-container .p-button-outlined {
  width: 100%;
  height: 48px;
}

.custom-file-upload {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  cursor: pointer;
  background-color: #f8f9fa;
  border: 2px dashed #ced4da;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.custom-file-upload:hover {
  background-color: #e9ecef;
  border-color: #6c757d;
}

.hidden-file-input {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  border: 0;
}

.upload-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  cursor: pointer;
  padding: 20px;
  text-align: center;
}

.upload-label i {
  font-size: 2rem;
  margin-bottom: 10px;
  color: #3498db;
}

.hidden-file-input {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  border: 0;
}

.clickable {
  cursor: pointer;
}

.no-images {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.upload-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

.upload-container .p-button {
  padding: 10px 20px;
  background-color: transparent;
  border: 2px dashed #6c757d;
  color: #6c757d;
}

.uploaded-image-container {
  position: relative;
  display: inline-block;
  width: 100%;
  text-align: center;
}

.uploaded-image {
  width: 100%;
  margin-bottom: 10px;
}

.remove-image-button {
  position: absolute;
  top: 5px;
  right: 5px;
  background-color: red;
  border: none;
  color: white;
  border-radius: 50%;
  padding: 6px 6px;
  cursor: pointer;
  z-index: 10;
  font-size: 14px;
  display: flex;
  justify-content: center;
  align-items: center;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.remove-image-button:hover {
  background-color: darkred;
}

:deep(.file-upload-button) {
  opacity: 0.8 !important;
  transition: opacity 0.3s ease;
  position: absolute !important;
  left: 80px !important;
}

:deep(.file-upload-button:hover) {
  opacity: 1 !important;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.9);
}
</style>