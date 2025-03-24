<template>
  <h1 v-if="!isViewMode" class="font-bold text-3xl">Add Vehicle</h1>
  <Breadcrumb v-if="!isViewMode" :model="items" class="custom-breadcrumb" />

  <Card class="isviewed">
    <template #content>
      <Tabs value="0">
        <TabList>
          <Tab value="0"><i class="pi pi-car mr-2"></i>Vehicle Information</Tab>
          <Tab value="1"><i class="pi pi-image mr-2"></i>{{ isViewMode ? 'Photos' : 'Upload Photos' }}</Tab>
          <Tab value="2"><i class="pi pi-cog mr-2"></i>Damages</Tab>
        </TabList>
        <TabPanels>
          <TabPanel value="0">
            <FloatLabel v-if="isViewMode || isEditMode" variant="on">
              <Select v-model="status" :options="statusOptions" optionLabel="label" optionValue="value"
                @change="handleStatusChange" />
              <label for="status">Status</label>
            </FloatLabel>
            <div class="vehicle-info">
              <img :src="brandLogo" width="400" :alt="selectedBrand + '-Logo'" />
              <div class="input-group">
                <FloatLabel variant="on">
                  <Select id="brand" class="full-width" v-model="selectedBrand" :options="brands" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="brand">Brand</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="model" class="full-width" :options="filteredModels" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="model">Model</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="bodyType" class="full-width" :options="bodyTypes" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="bodyType">Body Type</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText type="number" class="full-width" v-model="year" :readonly="isViewMode && !isEditMode" />
                  <label for="year">Year</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="location" class="full-width" :options="locations" optionLabel="name" optionValue="id"
                    :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="location">Location</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText type="text" class="full-width" v-model="plate" :readonly="isViewMode && !isEditMode" />
                  <label for="plate">Plate</label>
                </FloatLabel>
              </div>
            </div>
            <div class="vehicle-specs">
              <Divider><strong>Vehicle Specs</strong></Divider>

              <div class="input-group">
                <FloatLabel variant="on">
                  <InputText v-model="odometer" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="odometer">Odometer</label>
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
                  <label for="externalColour">External Colour</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="passengers" class="full-width" :options="passengerOptions" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="passengers">Passengers</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="fuelType" class="full-width" :options="fuelTypes" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="fuelType">Fuel Type</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select v-model="transmission" class="full-width" :options="transmissions" optionLabel="label"
                    optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }" />
                  <label for="transmission">Transmission</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="baseKmDay" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="baseKmDay">Base Km per Day</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="kmExtraPrice" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="kmExtraPrice">Extra Km Price</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="basicDailyPrice" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="basicDailyPrice">Basic Daily Price</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="franchise" class="full-width" type="number"
                    :readonly="isViewMode && !isEditMode" />
                  <label for="franchise">Franchise</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <InputText v-model="deposit" class="full-width" type="number" :readonly="isViewMode && !isEditMode" />
                  <label for="deposit">Deposit</label>
                </FloatLabel>
              </div>
              <div v-if="isViewMode && !isEditMode">
                <Button @click="enableEditMode" label="Edit" icon="pi pi-pencil" class="p-button-primary mt-4" />
              </div>
              <div v-else-if="isEditMode">
                <Button @click="saveChanges" label="Save" icon="pi pi-check" class="p-button-success mt-4" />
                <Button @click="cancelEditMode" label="Cancel" icon="pi pi-times"
                  class="p-button-secondary mt-4 ml-2" />
                <Button @click="deleteVehicle" label="Delete" icon="pi pi-trash" class="p-button-danger mt-4 ml-2" />
              </div>
              <div v-if="!isViewMode && !isEditMode" class="mt-4">
                <Button @click="createVehicle" label="Create Vehicle" icon="pi pi-check" />
              </div>
            </div>
          </TabPanel>

          <TabPanel value="1">
            <div v-if="isViewMode" class="images-container">
              <div v-if="isViewMode" v-for="(slot, index) in fotoSlots" :key="index">
                <div v-if="!hasVehicleImages" class="no-images">
                  <div class="upload-container">
                    <FileUpload v-if="!src[index]" ref="fileupload" mode="basic" @select="onFileSelect($event, index)"
                      chooseLabel="Scegli" showUploadButton="false" customUpload auto name="fileupload"
                      url="/api/upload" accept="image/*" :maxFileSize="1000000" @upload="onUpload" class="file-upload-button" />
                      
                   
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
              <!-- In modalità visualizzazione: mostra le foto esistenti e permette di aggiungerne altre (solo se ci sono immagini) -->
              <div v-else class="photo-upload-grid">
                <!-- Prima mostriamo le foto esistenti -->
                <div class="photo-upload-item" v-for="image in vehicle.images" :key="image.id">
                  <img :src="image.url" alt="Vehicle Image" class="uploaded-photo" />
                </div>

                <!-- Mostra un pulsante per aggiungere un'altra foto se ci sono meno di 3 foto -->
                <div v-if="vehicle.images && vehicle.images.length < 3" class="photo-upload-item add-photo-button"
                  @click="enableEditMode">
                  <div class="add-photo-content">
                    <i class="pi pi-plus-circle" style="font-size: 2rem; color: #6c757d;"></i>
                    <p>Aggiungi foto</p>
                  </div>
                </div>
              </div>
            </div>

            <div v-else>
              <!-- In modalità modifica o creazione: mostra i 3 slot -->
              <div class="photo-upload-grid">
                <div class="photo-upload-item" v-for="index in 3" :key="index - 1">
                  <div v-if="!photos[index - 1]" class="upload-container">
                    <!-- Usa label invece di div per migliore accessibilità e compatibilità -->
                    <label :for="'file-upload-' + (index - 1)" class="custom-file-upload">
                      <i class="pi pi-cloud-upload" style="font-size: 2.5rem; color: #3498db;"></i>
                      <span style="margin-top: 10px; font-weight: bold;">Click to Upload Image</span>
                      <span style="font-size: 0.8rem; margin-top: 5px; color: #6c757d;">Max size: 5MB</span>
                    </label>
                    <input :id="'file-upload-' + (index - 1)" type="file" accept="image/*" class="hidden-file-input"
                      @change="handleFileUpload($event, index - 1)" />
                  </div>
                  <div v-else class="photo-container">
                    <img :src="photos[index - 1]" alt="Uploaded Photo" class="uploaded-photo" />
                    <Button icon="pi pi-times" class="p-button-rounded p-button-danger p-button-sm delete-photo-btn"
                      @click="removePhoto(index - 1)" />
                  </div>
                </div>
              </div>
            </div>

            <div v-if="isEditMode" class="mt-4">
              <Button @click="savePhotos" label="Save Photos" icon="pi pi-check" />
              <Button @click="cancelEditMode" label="Cancel" icon="pi pi-times" class="p-button-secondary ml-2" />
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

<script>
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
// import { fotoSlots } from '@/utils.js';
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
    console.log(fotoSlots);

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
        this.imageUrl = newVal.imageUrl ?? this.imageUrl;
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
      const file = event.files[0];
      const reader = new FileReader();

      reader.onload = async (e) => {
        this.src[index] = e.target.result; // Imposta l'immagine nell'array basato sull'indice
      };
      reader.readAsDataURL(file);
    },
    removeImage(index) {
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
      console.log(`Attivazione input file per l'indice ${index}`);
      // Usa un timeout breve per assicurarsi che l'evento di click sia completato
      setTimeout(() => {
        const inputElement = document.getElementById(`file-upload-${index}`);
        if (inputElement) {
          inputElement.click();
          console.log('Input file attivato');
        } else {
          console.error(`Input file con id file-upload-${index} non trovato`);
        }
      }, 10);
    },
    handleFileUpload(event, index) {
      const file = event.target.files[0];
      if (!file) {
        console.log("Nessun file selezionato");
        return;
      }

      console.log(`File selezionato per index ${index}:`, file.name, file.size);

      // Size validation
      if (file.size > 5000000) {
        this.$showToast('error', 'Error', 'File size must be less than 5MB');
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        // Aggiorna l'array delle foto con la nuova immagine
        this.photos[index] = e.target.result;
        console.log(`Immagine caricata in index ${index}:`, this.photos[index].substring(0, 50) + '...');
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

        // Se trova un'immagine con la posizione specificata, usa il suo URL
        if (image && image.url) {
          return image.url;
        }

        // Alternativa: cerca un'immagine all'indice specificato nell'array
        // (utile se le immagini non hanno la proprietà position)
        if (this.vehicle.images[index] && this.vehicle.images[index].url) {
          return this.vehicle.images[index].url;
        }
      }

      // Se non ci sono immagini o l'immagine non esiste, mostra l'immagine predefinita
      return '/images/no-image.png';
    },
    handleStatusChange() {
      // No need to access event parameter, directly use this.status

      // Store current make and model values to restore them later if needed
      const currentMake = this.selectedBrand;
      const currentModel = this.model;

      // Only make the API call if in view or edit mode and we have a vehicle ID
      if ((this.isViewMode || this.isEditMode) && this.vehicle && this.vehicle.id) {
        // Create status update object
        const statusUpdate = {
          id: this.vehicle.id,
          status: this.status
        };

        // Send the request to the backend
        axios.put(`/vehicles/update-status/${this.vehicle.id}`, statusUpdate)
          .then(response => {
            this.$showToast('success', 'Success', 'Vehicle status updated successfully');

            // Update local vehicle with data from server
            if (response.data && response.data.vehicle) {
              const updatedVehicle = response.data.vehicle;

              // Create a merged vehicle object that preserves current data
              // but updates with new data from the server
              const mergedVehicle = {
                ...this.vehicle,
                ...updatedVehicle,
                make: this.vehicle.make,  // Preserve make object
                model: this.vehicle.model  // Preserve model object
              };

              // Update status (should be the same as this.status)
              this.status = updatedVehicle.status || this.status;

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

      // Handle any images that were uploaded
      const formData = new FormData();

      // Add all vehicle data to formData
      Object.keys(newVehicle).forEach(key => {
        formData.append(key, newVehicle[key]);
      });

      let hasPhotos = false;
      this.photos.forEach((photo, index) => {
        if (photo) {
          hasPhotos = true;
          // Convert base64 back to file if needed
          const photoFile = this.dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
          formData.append(`photos[${index}]`, photoFile);
          formData.append(`indices[${index}]`, index);
        }
      });

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

    // Helper method to convert base64 to File object
    dataURLtoFile(dataURL, filename) {
      const arr = dataURL.split(',');
      const mime = arr[0].match(/:(.*?);/)[1];
      const bstr = atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);

      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }

      return new File([u8arr], filename, { type: mime });
    },

    resetForm() {
      // Reset all form fields
      this.selectedBrand = '';
      this.model = '';
      this.bodyType = '';
      this.year = '';
      this.location = '';
      this.plate = '';
      this.odometer = '';
      this.externalColour = '';
      this.passengers = '';
      this.fuelType = '';
      this.transmission = '';
      this.baseKmDay = '';
      this.kmExtraPrice = '';
      this.basicDailyPrice = '';
      this.franchise = '';
      this.deposit = '';
      this.photos = [null, null, null];
    },
    fetchBrandsAndModels() {
      axios.post('/vehicle-makes')
        .then(response => {
          this.brands = response.data.map(make => ({ label: make.name, value: make.id, icon: make.icon }));
          this.models = response.data.reduce((acc, make) => {
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
      const uploads = [];

      this.photos.forEach((photo, index) => {
        if (photo && photo.startsWith('data:')) {
          // This is a new photo that needs to be uploaded
          const file = this.dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
          uploads.push({ file, index });
        }
      });

      if (uploads.length === 0) {
        this.$showToast('info', 'Info', 'No new photos to upload');
        return;
      }

      // Create a form data object to send all photos
      const formData = new FormData();
      formData.append('vehicle_id', this.vehicle.id);

      uploads.forEach(({ file, index }) => {
        formData.append(`photos[${index}]`, file);
        formData.append(`indices[${index}]`, index);
      });

      axios.post('/vehicles/upload-multiple-photos', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(response => {
          this.$showToast('success', 'Success', 'Photos saved successfully');

          // Aggiorna il veicolo con le nuove immagini
          if (response.data && response.data.vehicle && response.data.vehicle.images) {
            // Aggiorna l'oggetto vehicle con le nuove immagini
            const updatedVehicle = {
              ...this.vehicle,
              images: response.data.vehicle.images
            };

            // Aggiorna il veicolo locale
            this.vehicle = updatedVehicle;

            // Resetta le foto selezionate dopo il salvataggio
            this.photos = [null, null, null];

            // Emetti evento per aggiornare i componenti genitori
            this.$emit('vehicle-updated', updatedVehicle);
          }
        })
        .catch(error => {
          console.error('Error saving photos:', error);
          this.$showToast('error', 'Error', 'Failed to save photos');
        });
    },

    onDamagePhotoSelect(index) {
      // Handle damage photo selection event
      return (event) => {
        const file = event.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
          // Replace this.$set with direct assignment
          this.damagePhotos[index] = e.target.result;
        };
        reader.readAsDataURL(file);
      };
    },
    enableEditMode(tabValue) {
      // Inizializza l'array delle foto per la modifica
      this.photos = [null, null, null];

      // Se ci sono foto esistenti, le prepariamo per la modifica
      if (this.vehicle && this.vehicle.images && this.vehicle.images.length > 0) {
        // Per ogni immagine esistente, prepara il riferimento
        this.vehicle.images.forEach((image, idx) => {
          if (idx < 3) {
            // Assicurati che l'array photos esista e sia inizializzato
            this.photos[idx] = image.url;
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
      console.log('Photos array after enableEditMode:', this.photos);

      // Salva i dati originali per poterli ripristinare in caso di annullamento
      this.originalData = JSON.parse(JSON.stringify(this.$data));
    },
    saveChanges() {
      // Create the updated vehicle object
      const updatedVehicle = {
        id: this.vehicle.id,
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
        status: this.status
      };

      // Send the update request to the API
      axios.put(`/vehicles/edit/${this.vehicle.id}`, updatedVehicle)
        .then(response => {
          this.isEditMode = false;

          // Log della risposta per debug
          console.log('Response from server:', response.data);

          // Aggiorna il veicolo locale con i dati ricevuti dal server
          if (response.data && response.data.vehicle) {
            const updatedVehicle = response.data.vehicle;

            // Emetti un evento globale per aggiornare la tabella dei veicoli
            this.$root.$emit('vehicle-updated', updatedVehicle);

            // Emetti anche un evento locale per il componente padre diretto
            this.$emit('vehicle-updated', updatedVehicle);

            // Aggiorna i dati locali con quelli ricevuti dal server
            this.selectedBrand = updatedVehicle.make_id;
            this.model = updatedVehicle.model_id;
            this.bodyType = updatedVehicle.bodyType;
            this.year = updatedVehicle.year;
            this.location = updatedVehicle.location;
            this.plate = updatedVehicle.plateNumber;
            this.odometer = updatedVehicle.odometer;
            this.externalColour = updatedVehicle.color;
            this.passengers = updatedVehicle.seats;
            this.fuelType = updatedVehicle.fuel_type;
            this.transmission = updatedVehicle.transmission;
            this.baseKmDay = updatedVehicle.km_per_day;
            this.kmExtraPrice = updatedVehicle.extra_km_price;
            this.basicDailyPrice = updatedVehicle.basic_daily_price;
            this.franchise = updatedVehicle.franchise;
            this.deposit = updatedVehicle.deposit;
            this.status = updatedVehicle.status;
          }

          this.$showToast('success', 'Success', 'Vehicle details updated successfully');
        })
        .catch(error => {
          console.error('Error updating vehicle:', error);
          this.$showToast('error', 'Error', 'Failed to update vehicle details');
        });
    },
    cancelEditMode() {
      // Restore the original data
      for (const key in this.originalData) {
        if (key !== 'originalData' && Object.prototype.hasOwnProperty.call(this, key)) {
          this[key] = JSON.parse(JSON.stringify(this.originalData[key]));
        }
      }
      this.isEditMode = false;
    },
    deleteVehicle() {
      if (confirm('Are you sure you want to delete this vehicle?')) {
        axios.delete(`/vehicles/delete/${this.vehicle.id}`)
          .then(response => {
            this.$root.$emit('vehicle-deleted', this.vehicle.id);
            this.$showToast('success', 'Success', 'Vehicle deleted successfully');
            this.$router.push('/dashboard/vehicles');
          })
          .catch(error => {
            console.error('Error deleting vehicle:', error);
            this.$showToast('error', 'Error', 'Failed to delete vehicle');
          });
      }
    }
  },
  computed: {
    brandLogo() {
      const selectedBrand = this.brands.find(brand => brand.value === this.selectedBrand);
      return selectedBrand ? selectedBrand.icon : '/brand-logo.svg';
    },
    filteredModels() {
      return this.selectedBrand ? this.models[this.selectedBrand] || [] : [];
    },
    hasVehicleImages() {
      return this.vehicle && this.vehicle.images && this.vehicle.images.length > 0;
    }
  }
};
</script>

<style scoped>
.vehicle-info {
  display: flex;
  align-items: center;
}

.input-group {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: space-between;
  /* Aggiungi per distribuire meglio gli spazi */
}

.input-group>* {
  flex: 1 1 calc(50% - 1rem);
  /* Ogni elemento occupa il 50% dello spazio, adattato per schermi più piccoli */
}

.vehicle-specs {
  margin-top: 2rem;
}

.vehicle-specs .input-group>* {
  flex: 1 1 calc(33.333% - 1rem);
  /* Riduce il numero di colonne a 3 su schermi grandi */
}

.no-images {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 300px;
  width: 300px;
  background-color: #f8f9fa;
  border: 1px dashed #ccc;
  border-radius: 4px;
  color: #6c757d;
  padding: 20px;
}

.images-container {
  width: 100%;
  display: flex;
  gap: 20px;

  flex-wrap: wrap;
 
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
  /* Mantiene le proporzioni riempiendo il contenitore */
  object-position: center;
  /* Centra l'immagine */
}

/* Stile per le immagini in modalità visualizzazione */
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

/* Stile specifico per il pulsante di upload */
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
  /* Posiziona il contenitore in modo relativo */
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

.upload-container {
  position: relative;
  /* Imposta il contenitore in modo relativo */
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  /* Necessario per posizionare il pulsante sopra l'immagine */
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
/* Rendi il bottone sbiadito */
::v-deep .file-upload-button {
  opacity: 0.8 !important;
  transition: opacity 0.3s ease;
  position: absolute !important;
  left: 80px !important;
}

/* Modifica al passaggio del mouse */
::v-deep .file-upload-button:hover {
  opacity: 1 !important;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.9);
}
</style>
