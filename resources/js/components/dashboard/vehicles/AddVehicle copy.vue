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
                    <Select 
                        id="brand" 
                        class="full-width full-height" 
                        v-model="selectedBrand" 
                        :options="brands" 
                        filter 
                        optionValue="value"
                        optionLabel="label" 
                        placeholder="‎ "
                        :class="{ 'select-readonly': isViewMode && !isEditMode }"
                    >
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <img 
                                    :alt="slotProps.option.label" 
                                    :src="slotProps.option.icon || '/brand-logo.svg'" 
                                    class="mr-2" 
                                    style="width: 35px" 
                                />
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

// Component props
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

// Define emits
const emit = defineEmits(['vehicle-updated', 'vehicle-updated-photo']);

// Composition API setup
const { t } = useI18n();
const router = useRouter();
const showToast = inject('$showToast', (type, title, message) => {
  console.log(`${type}: ${title} - ${message}`); // Fallback if $showToast is not provided
});

// State variables
const existingPhotos = ref([]);
const src = ref([null, null, null, null, null, null]);
const fileInputs = ref([]);
const isEditMode = ref(false);
const photos = ref([null, null, null]);
const damagePhotos = ref([null, null, null]);
const photoIds = ref([null, null, null]);
const damageDescription = ref('');
const damageSeverity = ref('');
const damages = ref(props.vehicle?.damages || [
  {
    damagePosition: 'Front Bumper',
    damageSeverity: 'High',
    damageType: 'Dent',
    damageDescription: 'Severe dent and scratches'
  }
]);
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
const originalData = ref({});
const brands = ref([]);
const models = ref({});
const locations = ref([]);

// Define constant data
const items = [
  { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
  { label: t('vehicle.addVehicle'), url: '/dashboard/add-vehicles' }
];

const statusOptions = [
  { label: t('vehicle.available'), value: 'available' },
  { label: t('vehicle.unavailable'), value: 'unavailable' },
  { label: t('vehicle.maintenance'), value: 'maintenance' }
];

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
  const brand = brands.value.find(brand => brand.value === selectedBrand.value);
  return brand ? brand.icon : '/brand-logo.svg';
});

const filteredModels = computed(() => {
  return selectedBrand.value ? models.value[selectedBrand.value] || [] : [];
});

const hasVehicleImages = computed(() => {
  return props.vehicle && props.vehicle.images && props.vehicle.images.length > 0;
});

// Watch for changes
watch(() => props.vehicle, (newVal) => {
  if (newVal.make?.id) selectedBrand.value = newVal.make.id;
  if (newVal.model?.id) model.value = newVal.model.id;

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
  src.value = newVal.images?.map(image => image.url) ?? src.value;
  status.value = newVal.status ?? status.value;
}, { immediate: true, deep: true });

watch(selectedBrand, () => {
  model.value = '';
});

// Methods
const onFileSelect = (event, index) => {
  isEditMode.value = true;
  const file = event.files[0];
  const reader = new FileReader();

  reader.onload = async (e) => {
    src.value[index] = e.target.result;
  };

  reader.readAsDataURL(file);
};

const removeImage = (index) => {
  isEditMode.value = true;
  src.value[index] = null;
};

const fetchLocations = async () => {
  try {
    const response = await axios.post('/location');
    locations.value = response.data;
  } catch (error) {
    console.error('Error fetching locations:', error);
    showToast('error', 'Error', 'Failed to load locations');
  }
};

const triggerFileInput = (index) => {
  console.log(`Activating file input for index ${index}`);
  setTimeout(() => {
    const inputElement = document.getElementById(`file-upload-${index}`);
    if (inputElement) {
      inputElement.click();
      console.log('File input activated');
    } else {
      console.error(`File input with id file-upload-${index} not found`);
    }
  }, 10);
};

const handleFileUpload = (event, index) => {
  isEditMode.value = true;
  const file = event.target.files[0];
  if (!file) {
    console.log("No file selected");
    return;
  }

  console.log(`File selected for index ${index}:`, file.name, file.size);

  // Size validation
  if (file.size > 5000000) {
    showToast('error', 'Error', 'File size must be less than 5MB');
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    photos.value[index] = e.target.result;
  };
  reader.onerror = (e) => {
    console.error("Error reading file:", e);
    showToast('error', 'Error', 'Failed to read the image file');
  };
  reader.readAsDataURL(file);
};

const getVehicleImageUrl = (index) => {
  if (props.vehicle && props.vehicle.images && props.vehicle.images.length > 0) {
    const image = props.vehicle.images.find(img => img.position === index);

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

          window.dispatchEvent(new CustomEvent('vehicle-status-updated', { detail: mergedVehicle }));
          emit('vehicle-updated', mergedVehicle);
        }
      })
      .catch(error => {
        console.error('Error updating status:', error);
        showToast('error', 'Error', 'Failed to update vehicle status');
        status.value = props.vehicle.status;
      });
  }
};

const createVehicle = () => {
  if (!selectedBrand.value || !model.value || !year.value || !plate.value) {
    showToast('error', 'Error', 'Please fill in all required fields');
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
    status: 'available'
  };

  const formData = new FormData();

  Object.keys(newVehicle).forEach(key => {
    formData.append(key, newVehicle[key]);
  });

  let hasPhotos = false;
  photos.value.forEach((photo, index) => {
    if (photo) {
      hasPhotos = true;
      const photoFile = dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      formData.append(`photos[${index}]`, photoFile);
      formData.append(`indices[${index}]`, index);
    }
  });

  axios.post('/vehicles/create', formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
    .then(response => {
      showToast('success', 'Success', 'Vehicle created successfully');
      resetForm();
      router.push('/dashboard/vehicles');
    })
    .catch(error => {
      console.error('Error creating vehicle:', error);
      showToast('error', 'Error', 'Failed to create vehicle');
    });
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

    if (props.vehicle && props.isViewMode) {
      selectedBrand.value = props.vehicle.make?.id || '';
      setTimeout(() => {
        model.value = props.vehicle.model?.id || '';
      }, 100);
    }
  } catch (error) {
    console.error('Error fetching vehicle makes and models:', error);
  }
};

const savePhotos = () => {
  const uploads = [];

  src.value.forEach((photo, index) => {
    if (photo && photo.startsWith('data:')) {
      const file = dataURLtoFile(photo, `vehicle_photo_${index}.jpg`);
      uploads.push({ file, index });
    }
  });

  const formData = new FormData();
  formData.append('vehicle_id', props.vehicle.id);

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

  isEditMode.value = true;

  originalData.value = {
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
    src: JSON.parse(JSON.stringify(src.value))
  };
};

const saveChanges = () => {
  const updatedVehicle = {
    id: props.vehicle.id,
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
    status: status.value
  };

  axios.put(`/vehicles/edit/${props.vehicle.id}`, updatedVehicle)
    .then(response => {
      isEditMode.value = false;

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