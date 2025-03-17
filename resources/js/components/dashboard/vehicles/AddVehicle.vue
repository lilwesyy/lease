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
                      <Select v-model="status" :options="statusOptions" optionLabel="label" optionValue="value"/>
                      <label for="status">Status</label>
                    </FloatLabel>
                      <div class="vehicle-info">
                          <img :src="brandLogo" width="400" :alt="selectedBrand + '-Logo'">
                          <div class="input-group">
                              <FloatLabel variant="on">
                                  <Select id="brand" class="full-width" v-model="selectedBrand" :options="brands" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="brand">Brand</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="model" class="full-width" :options="filteredModels" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="model">Model</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="bodyType" class="full-width" :options="bodyTypes" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="bodyType">Body Type</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText type="number" class="full-width" v-model="year" :readonly="isViewMode && !isEditMode"/>
                                  <label for="year">Year</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="location" class="full-width" :options="Locations" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="location">Location</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText type="text" class="full-width" v-model="plate" :readonly="isViewMode && !isEditMode"/>
                                  <label for="plate">Plate</label>
                              </FloatLabel>
                          </div>
                      </div>
                      <div class="vehicle-specs">
                          <Divider><strong>Vehicle Specs</strong></Divider>

                          <div class="input-group">
                              <FloatLabel variant="on">
                                  <InputText v-model="odometer" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="odometer">Odometer</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="externalColour" class="full-width" :options="colorOptions" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }">
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
                                  <Select v-model="passengers" class="full-width" :options="passengerOptions" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="passengers">Passengers</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="fuelType" class="full-width" :options="fuelTypes" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="fuelType">Fuel Type</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <Select v-model="transmission" class="full-width" :options="transmissions" optionLabel="label" optionValue="value" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
                                  <label for="transmission">Transmission</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText v-model="baseKmDay" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="baseKmDay">Base Km per Day</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText v-model="kmExtraPrice" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="kmExtraPrice">Extra Km Price</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText v-model="basicDailyPrice" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="basicDailyPrice">Basic Daily Price</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText v-model="franchise" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="franchise">Franchise</label>
                              </FloatLabel>

                              <FloatLabel variant="on">
                                  <InputText v-model="deposit" class="full-width" type="number" :readonly="isViewMode && !isEditMode"/>
                                  <label for="deposit">Deposit</label>
                              </FloatLabel>
                          </div>
                          <div v-if="isViewMode && !isEditMode">
                              <Button @click="enableEditMode" label="Edit" icon="pi pi-pencil" class="p-button-primary mt-4" />
                          </div>
                          <div v-else-if="isEditMode">
                              <Button @click="saveChanges" label="Save" icon="pi pi-check" class="p-button-success mt-4" />
                              <Button @click="cancelEditMode" label="Cancel" icon="pi pi-times" class="p-button-secondary mt-4 ml-2" />
                          </div>
                      </div>
                  </TabPanel>

                  <TabPanel value="1">
                      <div class="photo-upload-grid">
                          <div class="photo-upload-item" v-for="(photo, index) in photos" :key="index">
                              <div v-if="isViewMode">
                                  <img :src="imageUrl" alt="Vehicle Image" class="uploaded-photo"/>
                              </div>
                              <div v-else>
                                  <div v-if="!photo">
                                      <FileUpload
                                          mode="basic"
                                          @select="onFileSelect(index)"
                                          customUpload
                                          auto
                                          severity="secondary"
                                          class="p-button-outlined"
                                      />
                                  </div>
                                  <div v-else class="photo-container">
                                      <img :src="photo" alt="Uploaded Photo" class="uploaded-photo"/>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </TabPanel>

                  <TabPanel value="2">
                      <div class="damage-section">
                        <VehicleDamage :isViewMode="isViewMode" :isEditMode="isEditMode" :vehicle="vehicle" :damages="damages"/>

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

export default {
  name: 'AddVehicle',
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
      isEditMode: false,
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Add Vehicles', url: '/dashboard/add-vehicles' }
      ],
      photos: [null, null, null],
      damagePhotos: [null, null, null],
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
      Locations: [
        { label: 'Torino', value: 'Torino' },
        { label: 'Milano', value: 'Milano' },
        { label: 'Roma', value: 'Roma' }
      ],
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
    this.fetchBrandsAndModels();
  },
  watch: {
    vehicle: {
      handler(newVal) {
        this.selectedBrand = newVal.make;
        this.model = newVal.model;
        this.bodyType = newVal.bodyType;
        this.year = newVal.year;
        this.location = newVal.location;
        this.plate = newVal.plateNumber;
        this.odometer = newVal.odometer;
        this.externalColour = newVal.color;
        this.passengers = newVal.seats;
        this.fuelType = newVal.fuel_type;
        this.transmission = newVal.transmission;
        this.baseKmDay = newVal.km_per_day;
        this.kmExtraPrice = newVal.extra_km_price;
        this.basicDailyPrice = newVal.basic_daily_price;
        this.franchise = newVal.franchise;
        this.deposit = newVal.deposit;
        this.imageUrl = newVal.imageUrl;
        this.status = newVal.status;
      },
      immediate: true,
      deep: true
    },
    selectedBrand(newBrand) {
      this.model = ''; // Reset the model when the brand changes
    }
  },
  methods: {
    fetchBrandsAndModels() {
        axios.post('/vehicle-makes')
      .then(response => {
        this.brands = response.data.map(make => ({ label: make.name, value: make.id, icon: make.icon }));
        this.models = response.data.reduce((acc, make) => {
          acc[make.id] = make.models.map(model => ({ label: model.name, value: model.id }));
          return acc;
        }, {});

        // Only set these values AFTER brands and models are loaded
        if (this.vehicle && this.isViewMode) {
          // If vehicle.make is a name/string, find the corresponding ID
          if (typeof this.vehicle.make === 'string') {
            const matchedBrand = this.brands.find(brand =>
              brand.label.toLowerCase() === this.vehicle.make.toLowerCase());
            this.selectedBrand = matchedBrand ? matchedBrand.value : '';
          } else {
            this.selectedBrand = this.vehicle.make;
          }

          // Same for model - handle if it's a string name vs an ID
          setTimeout(() => {
            if (typeof this.vehicle.model === 'string' && this.filteredModels.length) {
              const matchedModel = this.filteredModels.find(model =>
                model.label.toLowerCase() === this.vehicle.model.toLowerCase());
              this.model = matchedModel ? matchedModel.value : '';
            } else {
              this.model = this.vehicle.model;
            }
          }, 100);
        }
      })
        .catch(error => {
          console.error('Error fetching vehicle makes and models:', error);
        });
    },
    onFileSelect(index) {
      const file = event.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        this.$set(this.photos, index, e.target.result); // Add file as image
      };
      reader.readAsDataURL(file);
    },
    onDamagePhotoSelect(index) {
      // Gestisci l'evento di selezione delle foto dei danni
      return (event) => {
        const file = event.files[0];
        const reader = new FileReader();
        reader.onload = (e) => {
          this.$set(this.damagePhotos, index, e.target.result); // Aggiungi il file caricato come immagine
        };
        reader.readAsDataURL(file); // Leggi il file come URL Data
      };
    },
    enableEditMode() {
      this.isEditMode = true;
      this.originalData = { ...this.$data }; // Salva i dati originali
    },
    saveChanges() {
      this.isEditMode = false;
      // Logica per salvare le modifiche
      this.$showToast('success', 'Success', 'Vehicle details saved successfully');
    },
    cancelEditMode() {
      Object.assign(this.$data, this.originalData); // Ripristina i dati originali
      this.isEditMode = false;
    }
  },
  computed: {
    brandLogo() {
      const selectedBrand = this.brands.find(brand => brand.value === this.selectedBrand);
      return selectedBrand ? selectedBrand.icon : '/brand-logo.svg';
    },
    filteredModels() {
      return this.selectedBrand ? this.models[this.selectedBrand] || [] : [];
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
    justify-content: space-between; /* Aggiungi per distribuire meglio gli spazi */
}

.input-group > * {
    flex: 1 1 calc(50% - 1rem); /* Ogni elemento occupa il 50% dello spazio, adattato per schermi più piccoli */
}

.vehicle-specs {
    margin-top: 2rem;
}


.vehicle-specs .input-group > * {
    flex: 1 1 calc(33.333% - 1rem); /* Riduce il numero di colonne a 3 su schermi grandi */
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
}

.uploaded-photo {
    width: 100%; /* Aggiungi questa linea */
    height: 100%; /* Aggiungi questa linea */
    max-width: 100%;
    max-height: 100%;
    object-fit: cover; /* Aggiungi questa linea */
}

.full-width {
  width: 100%;
}
</style>
