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
                  <label for="brand">{{ $t('vehicle.fields.brand') }}</label>
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
import { ref, reactive, computed, watch, onMounted, toRaw } from 'vue';
import { useRouter } from 'vue-router';
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
import { useI18n } from 'vue-i18n'; 

const { t } = useI18n();

const router = useRouter();

// Props
const props = defineProps({
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
});

// Emits
const emit = defineEmits(['vehicle-updated', 'vehicle-updated-photo']);

// Reactive state
const isEditMode = ref(false);
const originalData = ref({});
const existingPhotos = ref([]);
const src = ref([null, null, null, null, null, null]);
const fileInputs = ref([]);
const photos = ref([null, null, null]);
const damagePhotos = ref([null, null, null]);
const photoIds = ref([null, null, null]);

const items = [
  { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
  { label: t('vehicle.addVehicle'), url: '/dashboard/add-vehicles' }
];

// Form fields
const selectedBrand = ref('');
const model = ref('');
const bodyType = ref('');
const year = ref('');
const location = ref('');
const plate = ref('');
const odometer = ref('');
const externalColour = ref('');
const passengers = ref('');
const fuelType = ref('');
const transmission = ref('');
const baseKmDay = ref('');
const kmExtraPrice = ref('');
const basicDailyPrice = ref('');
const franchise = ref('');
const deposit = ref('');
const imageUrl = ref('');
const status = ref('');
const damageDescription = ref('');
const damageSeverity = ref('');
const damages = ref(props.vehicle ? props.vehicle.damages : [
  {
    damagePosition: 'Front Bumper',
    damageSeverity: 'High',
    damageType: 'Dent',
    damageDescription: 'Severe dent and scratches'
  }
]);

// Options
const statusOptions = [
  { label: t('vehicle.available'), value: 'available' },
  { label: t('vehicle.unavailable'), value: 'unavailable' },
  { label: t('vehicle.maintenance'), value: 'maintenance' }
];

const brands = ref([]);
const models = ref({});

const damageSeverities = [
  { label: 'Minor', value: 'minor' },
  { label: 'Moderate', value: 'moderate' },
  { label: 'Severe', value: 'severe' }
];

const bodyTypes = [
  { label: 'Wagon/Estate', value: 'wagon' },
  { label: 'Passenger Van', value: 'van' },
  { label: 'Sedan/Limousine', value: 'sedan' },
  { label: 'Convertible', value: 'convertible' },
  { label: 'Sport', value: 'sport' },
  { label: 'SUV/4x4', value: 'suv' }
];

const locations = ref([]);

const fuelTypes = [
  { label: 'Petrol', value: 'petrol' },
  { label: 'Diesel', value: 'diesel' },
  { label: 'Electric', value: 'electric' },
  { label: 'Hybrid', value: 'hybrid' }
];

const transmissions = [
  { label: 'Automatic', value: 'automatic' },
  { label: 'Manual', value: 'manual' }
];

const colorOptions = [
  { label: 'Red', value: 'red', style: 'background-color: red;' },
  { label: 'Blue', value: 'blue', style: 'background-color: blue;' },
  { label: 'Green', value: 'green', style: 'background-color: green;' },
  { label: 'Black', value: 'black', style: 'background-color: black;' },
  { label: 'White', value: 'white', style: 'background-color: white;' },
  { label: 'Silver', value: 'silver', style: 'background-color: silver;' }
];

const passengerOptions = [
  { label: '2', value: 2 },
  { label: '4', value: 4 },
  { label: '5', value: 5 },
  { label: '7', value: 7 }
];

// Computed properties
const brandLogo = computed(() => {
  const selected = brands.value.find(brand => brand.value === selectedBrand.value);
  return selected ? selected.icon : '/brand-logo.svg';
});

const filteredModels = computed(() => {
  return selectedBrand.value ? models.value[selectedBrand.value] || [] : [];
});

const hasVehicleImages = computed(() => {
  return props.vehicle && props.vehicle.images && props.vehicle.images.length > 0;
});

// Methods
const fetchLocations = () => {
  axios.post('/location')
    .then(response => {
      locations.value = response.data;
    })
    .catch(error => {
      console.error('Error fetching locations:', error);
      window.$showToast('error', 'Error', 'Failed to load locations');
    });
};

const fetchBrandsAndModels = () => {
  axios.post('/vehicle-makes')
    .then(response => {
      brands.value = response.data.map(make => ({ 
        label: make.name, 
        value: make.id, 
        icon: make.icon 
      }));

      models.value = response.data.reduce((acc, make) => {
        acc[make.id] = make.models.map(model => ({ 
          label: model.name, 
          value: model.id 
        }));
        return acc;
      }, {});

      // If in view mode and we have a vehicle
      if (props.vehicle && props.isViewMode) {
        // Set the brand based on ID
        selectedBrand.value = props.vehicle.make?.id || '';

        // Set model after a brief delay to ensure filtered models are available
        setTimeout(() => {
          model.value = props.vehicle.model?.id || '';
        }, 100);
      }
    })
    .catch(error => {
      console.error('Error fetching vehicle makes and models:', error);
    });
};

const onFileSelect = (event, index) => {
  if (window.location.pathname === "/dashboard/vehicles") {
    isEditMode.value = true;
  }

  const file = event.files[0];
  const reader = new FileReader();

  reader.onload = async (e) => {
    src.value[index] = e.target.result; // Set the image in the array based on index
  };

  reader.readAsDataURL(file);
};

const removeImage = (index) => {
  if (window.location.pathname === "/dashboard/vehicles") {
    isEditMode.value = true;
  }
  src.value[index] = null; // Remove the selected image
};

const triggerFileInput = (index) => {
  // Use a short timeout to make sure the click event is completed
  setTimeout(() => {
    const inputElement = document.getElementById(`file-upload-${index}`);
    if (inputElement) {
      inputElement.click();
    } else {
      console.error(`Input file with id file-upload-${index} not found`);
    }
  }, 10);
};

const handleFileUpload = (event, index) => {
  isEditMode.value = true;
  const file = event.target.files[0];
  if (!file) {
    return;
  }

  // Size validation
  if (file.size > 5000000) {
    window.$showToast('error', 'Error', 'File size must be less than 5MB');
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    // Update photos array with the new image
    photos.value[index] = e.target.result;
  };
  reader.onerror = (e) => {
    console.error("Error reading file:", e);
    window.$showToast('error', 'Error', 'Failed to read the image file');
  };
  reader.readAsDataURL(file);
};

const getVehicleImageUrl = (index) => {
  // Check if vehicle exists and has images
  if (props.vehicle && props.vehicle.images && props.vehicle.images.length > 0) {
    // Look for an image with position matching the index
    const image = props.vehicle.images.find(img => img.position === index);

    // If found an image with the specified position, use its URL
    if (image && image.url) {
      return image.url;
    }

    // Alternative: look for an image at the specified index in the array
    if (props.vehicle.images[index] && props.vehicle.images[index].url) {
      return props.vehicle.images[index].url;
    }
  }

  // If no images or the image doesn't exist, show default image
  return '/images/no-image.png';
};

const handleStatusChange = () => {
  // No need to access event parameter, directly use status.value

  // Only make the API call if in view or edit mode and we have a vehicle ID
  if ((props.isViewMode || isEditMode.value) && props.vehicle && props.vehicle.id) {
    // Create status update object
    const statusUpdate = {
      id: props.vehicle.id,
      status: status.value
    };

    // Send the request to the backend
    axios.put(`/vehicles/update-status/${props.vehicle.id}`, statusUpdate)
      .then(response => {
        window.$showToast('success', 'Success', 'Vehicle status updated successfully');

        // Update local vehicle with data from server
        if (response.data && response.data.vehicle) {
          const updatedVehicle = response.data.vehicle;

          // Create a merged vehicle object that preserves current data
          // but updates with new data from the server
          const mergedVehicle = {
            ...props.vehicle,
            ...updatedVehicle,
            make: props.vehicle.make,  // Preserve make object
            model: props.vehicle.model  // Preserve model object
          };

          // Update status (should be the same as status.value)
          status.value = updatedVehicle.status || status.value;

          // Emit the merged vehicle data
          window.$root.$emit('vehicle-status-updated', mergedVehicle);
          emit('vehicle-updated', mergedVehicle);
        }
      })
      .catch(error => {
        console.error('Error updating status:', error);
        window.$showToast('error', 'Error', 'Failed to update vehicle status');

        // Restore previous status in case of error
        status.value = props.vehicle.status;
      });
  }
};

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

const createVehicle = () => {
  const uploads = [];
  // Validate required fields
  if (!selectedBrand.value || !model.value || !year.value || !plate.value) {
    window.$showToast('error', 'Error', 'Please fill in all required fields');
    return;
  }

  const newVehicle = {
    make_id: selectedBrand.value,
    model_id: model.value,
    bodyType: bodyType.value,
    year: year.value,
    location_id: location.value,
    plateNumber: plate.value,
    odometer: odometer.value,
    color: externalColour.value,
    seats: passengers.value,
    fuel_type: fuelType.value,
    transmission: transmission.value,
    km_per_day: baseKmDay.value,
    extra_km_price: kmExtraPrice.value,
    basic_daily_price: basicDailyPrice.value,
    dailyPrice: basicDailyPrice.value,
    franchise: franchise.value,
    deposit: deposit.value,
    status: 'available' // Default status for new vehicles
  };

  // Create a form data object to send all data
  const formData = new FormData();

  // Add all vehicle data to formData
  Object.keys(newVehicle).forEach(key => {
    formData.append(key, newVehicle[key]);
  });

  src.value.forEach((photo, index) => {
    // Add only new images (those that start with 'data:')
    if (photo && photo.startsWith("data:")) {
      const file = dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      uploads.push({ file, index });
    }
  });

  uploads.forEach(({ file, index }) => {
    formData.append(`photos[${index}]`, file); // Add file to FormData
    formData.append(`indices[${index}]`, index); // Add index
  });

  if (props.vehicle && props.vehicle.id) {
    formData.append('vehicle_id', props.vehicle.id);
  }

  // Send the request to the API
  axios.post('/vehicles/create', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(response => {
      window.$showToast('success', 'Success', 'Vehicle created successfully');

      // Reset form
      resetForm();

      // Navigate to the vehicles list
      router.push('/dashboard/vehicles');
    })
    .catch(error => {
      console.error('Error creating vehicle:', error);
      window.$showToast('error', 'Error', 'Failed to create vehicle');
    });
};

const resetForm = () => {
  // Reset all form fields
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

const enableEditMode = (tabValue) => {
  // Initialize photos array for editing
  // If there are existing photos, prepare them for editing
  if (props.vehicle && props.vehicle.images && props.vehicle.images.length > 0) {
    // For each existing image, prepare the reference
    props.vehicle.images.forEach((image, idx) => {
      if (idx < 6) { // Assuming maximum 6 photos
        src.value[idx] = image.url;
      }
    });
  }

  // Set edit mode
  isEditMode.value = true;

  // Save original data to restore in case of cancel
  originalData.value = JSON.parse(JSON.stringify({
    selectedBrand: selectedBrand.value,
    model: model.value,
    bodyType: bodyType.value,
    year: year.value,
    location: location.value,
    plate: plate.value,
    odometer: odometer.value,
    externalColour: externalColour.value,
    passengers: passengers.value,
    fuelType: fuelType.value,
    transmission: transmission.value,
    baseKmDay: baseKmDay.value,
    kmExtraPrice: kmExtraPrice.value,
    basicDailyPrice: basicDailyPrice.value,
    franchise: franchise.value,
    deposit: deposit.value,
    status: status.value,
    src: [...src.value]
  }));
};

const saveChanges = () => {
  // Create the updated vehicle object using FormData
  const updatedVehicle = new FormData();

  updatedVehicle.append("id", props.vehicle.id);
  updatedVehicle.append("make_id", selectedBrand.value);
  updatedVehicle.append("model_id", model.value);
  updatedVehicle.append("bodyType", bodyType.value);
  updatedVehicle.append("year", year.value);
  updatedVehicle.append("location_id", location.value);
  updatedVehicle.append("plateNumber", plate.value);
  updatedVehicle.append("odometer", odometer.value);
  updatedVehicle.append("color", externalColour.value);
  updatedVehicle.append("seats", passengers.value);
  updatedVehicle.append("fuel_type", fuelType.value);
  updatedVehicle.append("transmission", transmission.value);
  updatedVehicle.append("km_per_day", baseKmDay.value);
  updatedVehicle.append("extra_km_price", kmExtraPrice.value);
  updatedVehicle.append("basic_daily_price", basicDailyPrice.value);
  updatedVehicle.append("dailyPrice", basicDailyPrice.value);
  updatedVehicle.append("franchise", franchise.value);
  updatedVehicle.append("deposit", deposit.value);
  updatedVehicle.append("status", status.value);

  // Send the update request to the API
  axios.put(`/vehicles/edit/${props.vehicle.id}`, updatedVehicle)
    .then(response => {
      isEditMode.value = false;

      // Update local vehicle with data from server
      if (response.data && response.data.vehicle) {
        const updatedVehicle = response.data.vehicle;

        // Emit an event to update the vehicles table
        window.$root.$emit('vehicle-updated', updatedVehicle);

        // Also emit a local event for the direct parent component
        emit('vehicle-updated', updatedVehicle);

        // Update local data with data received from server
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

      window.$showToast('success', 'Success', 'Vehicle details updated successfully');
    })
    .catch(error => {
      console.error('Error updating vehicle:', error);
      window.$showToast('error', 'Error', 'Failed to update vehicle details');
    });
};

const cancelEditMode = () => {
  // Restore original data
  if (originalData.value) {
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
    src.value = [...originalData.value.src];
  }
  isEditMode.value = false;
};

const deleteVehicle = () => {
  if (confirm('Are you sure you want to delete this vehicle?')) {
    axios.delete(`/vehicles/delete/${props.vehicle.id}`)
      .then(response => {
        window.$root.$emit('vehicle-deleted', props.vehicle.id);
        window.$showToast('success', 'Success', 'Vehicle deleted successfully');
        router.push('/dashboard/vehicles');
      })
      .catch(error => {
        console.error('Error deleting vehicle:', error);
        window.$showToast('error', 'Error', 'Failed to delete vehicle');
      });
  }
};

const savePhotos = () => {
  // Create a form data object to send all photos
  const formData = new FormData();
  formData.append('vehicle_id', props.vehicle.id);

  // Process and add any new uploads
  const uploads = [];
  src.value.forEach((photo, index) => {
    if (photo && photo.startsWith("data:")) {
      const file = dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      formData.append(`photos[${index}]`, file);
      formData.append(`indices[${index}]`, index);
      uploads.push({ index, isNew: true });
    } else if (photo) {
      // This is an existing photo URL
      formData.append(`existing_photos[${index}]`, photo);
      formData.append(`existing_indices[${index}]`, index);
      uploads.push({ index, isNew: false });
    }
  });

  axios.post('/vehicles/upload-multiple-photos', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(response => {
      window.$showToast('success', 'Success', 'Photos saved successfully');
      emit('vehicle-updated-photo');
    })
    .catch(error => {
      console.error('Error saving photos:', error);
      window.$showToast('error', 'Error', 'Failed to save photos');
    });
};

const onUpload = (event) => {
  // Handle upload complete event if needed
};

// Watch for changes to props.vehicle
watch(() => props.vehicle, (newVal) => {
  if (newVal) {
    // Only update values that are actually present in the new vehicle object
    if (newVal.make?.id) selectedBrand.value = newVal.make.id;
    if (newVal.model?.id) model.value = newVal.model.id;

    // For other properties, use nullish coalescing to keep existing values if new ones are null/undefined
    bodyType.value = newVal.bodyType ?? bodyType.value;
    year.value = newVal.year ?? year.value;
    location.value = newVal.location_id ?? location.value;
    plate.value = newVal.plateNumber ?? plate.value;
    odometer.value = newVal.odometer ?? odometer.value;
    externalColour.value = newVal.color ?? externalColour.value;
    passengers.value = newVal.seats ?? passengers.value;
    fuelType.value = newVal.fuel_type ?? fuelType.value;
    transmission.value = newVal.transmission ?? transmission.value;
    baseKmDay.value = newVal.km_per_day ?? baseKmDay.value;
    kmExtraPrice.value = newVal.extra_km_price ?? kmExtraPrice.value;
    basicDailyPrice.value = newVal.basic_daily_price ?? basicDailyPrice.value;
    franchise.value = newVal.franchise ?? franchise.value;
    deposit.value = newVal.deposit ?? deposit.value;
    
    if (newVal.images) {
      src.value = newVal.images.map(image => image.url);
    }
    
    status.value = newVal.status ?? status.value;
  }
}, { immediate: true, deep: true });

// Watch for changes to selectedBrand to reset model
watch(selectedBrand, () => {
  model.value = '';
});

// Lifecycle hooks
onMounted(() => {
  if (window.location.pathname === "/dashboard/add-vehicles") {
    src.value = [null, null, null, null, null, null];
  }

  fetchLocations();
  fetchBrandsAndModels();
});
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

/* Contenitore della griglia per le immagini */
.images-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  /* 3 colonne di larghezza uguale */
  gap: 20px;
  /* Spaziatura tra le immagini */
  width: 100%;
  padding: 10px;
}

/* Ogni immagine occupa una cella della griglia */
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

/* Media query per schermi medi (tablet o schermi più piccoli) */
@media (max-width: 768px) {
  .images-container {
    grid-template-columns: repeat(2, 1fr);
    /* 2 colonne su schermi più piccoli */
  }
}

/* Media query per schermi piccoli (mobile) */
@media (max-width: 480px) {
  .images-container {
    grid-template-columns: 1fr;
    /* Una colonna su schermi molto piccoli */
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