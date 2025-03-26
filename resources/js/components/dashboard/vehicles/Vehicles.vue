<template>
  <div>
    <h1 class="font-bold text-3xl">{{ t('vehicle.vehicleList') }}</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />
    <Card>
      <template #content>
        <header class="p-d-flex p-ai-center p-gap-2 inline-header">
          <Button :label="$t('vehicle.addButton')" icon="pi pi-plus" @click="$router.push('/dashboard/add-vehicles')" />
          <IconField class="p-d-flex p-ai-center">
              <InputIcon class="pi pi-search" />
                  <InputText
                  v-model="value1"
                  :placeholder="$t('vehicle.searchPlaceholder')"
                  @input="onSearchInput"
                  class="w-full"
                  />
              </IconField>
        </header>

        <div v-if="loading" class="flex justify-center p-4">
          <ProgressSpinner />
        </div>
        <div v-else-if="error" class="p-4 text-red-500 text-center">
          {{ error }}
        </div>
        <div v-else-if="vehicles.length > 0">
          <DataTable
            :value="filteredVehicles"
            selectionMode="single"
            class="p-datatable-no-gridlines"
            @row-click="onRowClick"
            :paginator="true"
            :rows="10"
            :rowsPerPageOptions="[5, 10, 20, 50]"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          >
            <Column :header="$t('vehicle.columns.brandModel')">
              <template #body="slotProps">
                <div style="display: flex; align-items: center;">
                  <img
                      :src="slotProps.data.make?.icon"
                      :alt="`${slotProps.data.make?.name} logo`"
                      width="90"
                      height="90"
                      v-if="slotProps.data.make?.icon"
                      style="margin-right: 8px;"
                      />
                  <div>
                    <div>{{ slotProps.data.make?.name }} {{ slotProps.data.model?.name }}</div>
                    <div style="font-size: 0.8em; color: grey;">
                      {{ slotProps.data.year }}, {{ capitalize(slotProps.data.fuel_type) }}, {{ capitalize(slotProps.data.transmission) }}
                    </div>
                  </div>
                </div>
              </template>
            </Column>
            <Column :header="$t('vehicle.columns.color')" style="width: 50px;">
              <template #body="slotProps">
                <div :style="{
                  backgroundColor: slotProps.data.color,
                  width: '20px',
                  height: '20px',
                  borderRadius: '50%',
                  border: slotProps.data.color.toLowerCase() === 'white' ? '1px solid grey' : 'none'
                }"></div>
              </template>
            </Column>
            <Column header="KM" style="width: 120px;">
              <template #body="slotProps">
                {{ formatNumber(slotProps.data.odometer) }} km
              </template>
            </Column>
            <Column field="plateNumber" :header="$t('vehicle.columns.plate')" style="width: 100px;" />
            <Column :header="$t('vehicle.columns.location')" style="width: 100px;">
              <template #body="slotProps">
                {{ slotProps.data.location?.name || 'Not specified' }}
              </template>
            </Column>
            <Column :header="$t('vehicle.columns.status')" style="width: 100px;">
              <template #body="slotProps">
                <Tag :value="capitalize(slotProps.data.status)" :severity="getSeverity(slotProps.data)" />
              </template>
            </Column>
          </DataTable>
        </div>
        <div v-else>
          <p class="text-center text-gray-500 mt-5">No vehicles found. Please add a vehicle.</p>
        </div>
      </template>
    </Card>

    <Dialog v-model:visible="showDetailsModal" header="Vehicle Details" modal :style="{ width: '70vw'}">
      <AddVehicle
        :vehicle="selectedVehicle"
        :isViewMode="true"
        @vehicle-updated="onVehicleUpdated"
        @vehicle-updated-photo="onVehicleUpdatedPhoto"
      />
    </Dialog>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import Tag from 'primevue/tag';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import ProgressSpinner from 'primevue/progressspinner';
import AddVehicle from './AddVehicle.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Define component name (optional in <script setup> but useful for debugging)
defineOptions({
name: 'VehiclesList'
});

const capitalize = (str) => {
if (!str) return '';
return str.charAt(0).toUpperCase() + str.slice(1);
};

const formatNumber = (number) => {
if (!number) return '0';
return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const value1 = ref('');
const vehicles = ref([]);
const showModal = ref(false);
const showDetailsModal = ref(false);
const selectedVehicle = ref(null);
const loading = ref(false);
const error = ref(null);

// Filtered vehicles based on search input
const filteredVehicles = computed(() => {
if (!value1.value.trim()) return vehicles.value;

const searchTerm = value1.value.toLowerCase();
return vehicles.value.filter(vehicle => {
  return (
    (vehicle.make?.name || '').toLowerCase().includes(searchTerm) ||
    (vehicle.model?.name || '').toLowerCase().includes(searchTerm) ||
    (vehicle.plateNumber || '').toLowerCase().includes(searchTerm) ||
    (vehicle.location?.name || '').toLowerCase().includes(searchTerm) ||
    (vehicle.year?.toString() || '').includes(searchTerm) ||
    (vehicle.fuel_type || '').toLowerCase().includes(searchTerm) ||
    (vehicle.transmission || '').toLowerCase().includes(searchTerm) ||
    (vehicle.status || '').toLowerCase().includes(searchTerm)
  );
});
});

const onRowClick = (event) => {
selectedVehicle.value = event.data;
showDetailsModal.value = true;
};

const addVehicle = (vehicle) => {
vehicles.value.push(vehicle);
showModal.value = false;
};

const getSeverity = (vehicle) => {
if (vehicle.status === 'available') {
  return 'success';
} else if (vehicle.status === 'maintenance') {
  return 'warn';
} else {
  return 'danger';
}
};

const items = [
{ label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
{ label: t('vehicle.vehicleList'), url: '/dashboard/vehicles' }
];

const fetchVehicles = async () => {
loading.value = true;
error.value = null;

try {
  const response = await axios.post('/vehicles');
  vehicles.value = response.data;
} catch (err) {
  console.error('Error fetching vehicles:', err);
  error.value = 'Failed to load vehicles. Please try again later.';
} finally {
  loading.value = false;
}
};

const onVehicleUpdatedPhoto = () => {
fetchVehicles();
};

// Method to handle vehicle updates
const onVehicleUpdated = (updatedVehicle) => {
const index = vehicles.value.findIndex(v => v.id === updatedVehicle.id);
if (index !== -1) {
  // Update the specific vehicle in the table
  vehicles.value[index] = updatedVehicle;

  // Update the selected vehicle if it's the one that was modified
  if (selectedVehicle.value && selectedVehicle.value.id === updatedVehicle.id) {
    selectedVehicle.value = updatedVehicle;
  }
}
};

// Method to handle vehicle deletion
const onVehicleDeleted = (vehicleId) => {
// Remove the vehicle from the table
vehicles.value = vehicles.value.filter(v => v.id !== vehicleId);

// If the deleted vehicle is currently displayed in the details, close the modal
if (selectedVehicle.value && selectedVehicle.value.id === vehicleId) {
  showDetailsModal.value = false;
}
fetchVehicles();
};

onMounted(() => {
fetchVehicles();

// Store references to handlers
const vehicleUpdatedHandler = (e) => onVehicleUpdated(e.detail);
const vehicleDeletedHandler = (e) => onVehicleDeleted(e.detail);

// Add event listeners
window.addEventListener('vehicle-updated', vehicleUpdatedHandler);
window.addEventListener('vehicle-updated-photo', vehicleUpdatedHandler);
window.addEventListener('vehicle-deleted', vehicleDeletedHandler);

// Cleanup listeners on component unmount
onBeforeUnmount(() => {
  window.removeEventListener('vehicle-updated-photo', vehicleUpdatedHandler);
  window.removeEventListener('vehicle-updated', vehicleUpdatedHandler);
  window.removeEventListener('vehicle-deleted', vehicleDeletedHandler);
});
});
</script>

<style>
.isviewed {
box-shadow: none !important;
}
.inline-header {
display: flex;
align-items: center;
gap: 1rem;
}
</style>