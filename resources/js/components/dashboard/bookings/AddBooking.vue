<template>
  <h1 class="font-bold text-3xl">Add Booking</h1>
  <Breadcrumb :model="items" class="custom-breadcrumb" />

  <Card>
    <template #content>
      <TabView v-model:activeIndex="activeTabIndex">
        <TabPanel>
          <template #header>
            <div class="tab-header-content">
              <i class="pi pi-car mr-2"></i>
              <span>Date & Vehicle</span>
            </div>
          </template>
          <p class="mt-2 mb-2 grey">Fill out the info needed to create a new booking</p>
          <DatePicker
              v-model="dates"
              showIcon
              fluid
              iconDisplay="input"
              selectionMode="range"
              placeholder="Select Dates"
              :manualInput="false"
              @date-select="onDateSelect"
          />
          <div class="input-group-row">
            <InputGroup  v-tooltip="'Select the vehicle from the table to set delivery location'">
              <InputGroupAddon>
                <i class="pi pi-map-marker"></i>
              </InputGroupAddon>
              <Select v-model="deliveryLocation" :options="locations" optionLabel="label" placeholder="Delivery Location" class="select-readonly"
              />
            </InputGroup>
            <InputGroup>
              <InputGroupAddon>
                <i class="pi pi-map-marker"></i>
              </InputGroupAddon>
              <Select v-model="pickupLocation" :options="locations" optionLabel="label" placeholder="PickUp Location" />
            </InputGroup>
          </div>
          <a href="#" class="add-location-link mt-2 block" @click.prevent="openLocationModal">Missing location? Add New</a>
          <IconField class="p-d-flex p-ai-center mt-5">
            <InputIcon class="pi pi-search" />
            <InputText v-model="vehicleSearch" placeholder="Search Vehicles" />
            <Button label="Add Vehicle"  class="ml-3" icon="pi pi-plus" @click="$router.push('/dashboard/add-vehicles')" />
          </IconField>
          <div v-if="vehicles.length > 0">
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
            <Column header="Select" style="width: 50px">
              <template #body="slotProps">
                <RadioButton
                  v-model="selectedVehicleId"
                  :inputId="`vehicle-${slotProps.data.id}`"
                  :name="`selectedVehicle`"
                  :value="slotProps.data.id"
                  @change="updateDeliveryLocation(slotProps.data)"
                />
              </template>
            </Column>
              <Column header="Brand and Model">
                <template #body="slotProps">
                  <div style="display: flex; align-items: center">
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
                      <div style="font-size: 0.8em; color: grey">
                        {{ capitalize(slotProps.data.fuel_type) }},
                        {{ capitalize(slotProps.data.transmission) }}
                      </div>
                    </div>
                  </div>
                </template>
              </Column>
              <Column header="Color" style="width: 50px">
                <template #body="slotProps">
                  <div
                    :style="{
                      backgroundColor: slotProps.data.color,
                      width: '20px',
                      height: '20px',
                      borderRadius: '50%',
                      border: slotProps.data.color.toLowerCase() === 'white' ? '1px solid grey' : 'none'
                    }"
                  ></div>
                </template>
              </Column>
              <Column header="KM" style="width: 120px">
                <template #body="slotProps">
                  {{ formatNumber(slotProps.data.odometer) }} km
                </template>
              </Column>
              <Column
                field="plateNumber"
                header="Plate"
                style="width: 100px"
              />
              <Column header="Location" style="width: 100px;">
                <template #body="slotProps">
                  {{ slotProps.data.location?.name || 'Not specified' }}
                </template>
              </Column>
              <Column header="Status" style="width: 100px">
                <template #body="slotProps">
                  <Tag
                    :value="capitalize(slotProps.data.status)"
                    :severity="getSeverity(slotProps.data)"
                  />
                </template>
              </Column>
            </DataTable>
          </div>
          <div v-else>
            <p class="text-center text-gray-500 mt-5">
              No vehicles found. Please add a vehicle.
            </p>
          </div>

          <div class="flex justify-content-end mt-4">
            <Button
              label="Next"
              icon="pi pi-arrow-right"
              iconPos="right"
              :disabled="!isDateVehicleTabComplete"
              @click="goToCustomerTab"
              class="p-button-primary"
            />
          </div>
        </TabPanel>
        <TabPanel :disabled="!isDateVehicleTabComplete">
          <template #header>
            <div class="tab-header-content">
              <i class="pi pi-user mr-2"></i>
              <span>Customer</span>
            </div>
          </template>
          <IconField class="p-d-flex p-ai-center mb-3">
            <InputIcon class="pi pi-search" />
            <InputText v-model="clientSearch" placeholder="Search Customers" @keyup.enter="searchClients" />
            <Button label="Add New Customer" icon="pi pi-plus" @click="$router.push('/dashboard/add-customer')" class="ml-3" />
          </IconField>

          <div v-if="clients.length > 0">
            <DataTable
                :value="filteredClients"
                selectionMode="single"
                dataKey="id"
                @row-click="onClientRowClick"
                class="p-datatable-no-gridlines"
                :paginator="true"
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
                paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            >
              <Column header="Select" style="width: 50px">
                <template #body="slotProps">
                  <RadioButton
                    v-model="selectedClientId"
                    :inputId="`client-${slotProps.data.id}`"
                    :name="`selectedClient`"
                    :value="slotProps.data.id"
                  />
                </template>
              </Column>
              <Column header="Name">
                <template #body="slotProps">
                <div>
                    {{ slotProps.data.firstName }} {{ slotProps.data.lastName }}
                    <div class="text-sm text-gray-500">{{ slotProps.data.email }}</div>
                </div>
                </template>
              </Column>
              <Column field="phone" header="Phone" />
              <Column field="address" header="Address" />
              <Column header="Documents">
                <template #body="">
                <i class="pi pi-file"></i>
                </template>
              </Column>
            </DataTable>
          </div>
          <div v-else>
            <p class="text-center text-gray-500 mt-5">No customers found. Please add a customer.</p>
          </div>

          <div class="flex justify-content-between mt-4">
            <Button
              label="Previous"
              icon="pi pi-arrow-left"
              class="p-button-secondary"
              @click="goToDateVehicleTab"
            />
            <Button
              label="Next"
              icon="pi pi-arrow-right"
              iconPos="right"
              :disabled="!isCustomerTabComplete"
              @click="goToSummaryTab"
              class="p-button-primary"
            />
          </div>
        </TabPanel>
        <TabPanel :disabled="!isCustomerTabComplete">
          <template #header>
            <div class="tab-header-content">
              <i class="pi pi-list-check mr-2"></i>
              <span>Summary</span>
            </div>
          </template>
          <div v-if="selectedVehicleId && selectedClient && dates">
            <h2 class="font-bold text-xl mb-4">Booking Summary</h2>

            <div class="summary-row">
              <div class="summary-column">
                <h3 class="font-bold text-lg mb-2">Customer</h3>
                <div><strong>Name:</strong> {{ selectedClient.firstName }} {{ selectedClient.lastName }}</div>
                <div><strong>Email:</strong> {{ selectedClient.email }}</div>
                <div><strong>Phone:</strong> {{ selectedClient.phone }}</div>
              </div>

              <div class="summary-column">
                <h3 class="font-bold text-lg mb-2">Vehicle</h3>
                <div v-if="selectedVehicle">
                  <div><strong>Make & Model:</strong> {{ selectedVehicle.make?.name }} {{ selectedVehicle.model?.name }}</div>
                  <div><strong>Year:</strong> {{ selectedVehicle.year }}</div>
                  <div><strong>Plate:</strong> {{ selectedVehicle.plateNumber }}</div>
                </div>
              </div>
            </div>

            <div class="summary-section">
              <h3 class="font-bold text-lg mb-2">Booking Details</h3>
              <div><strong>From:</strong> {{ formatDate(dates[0]) }}</div>
              <div><strong>To:</strong> {{ formatDate(dates[1]) }}</div>
              <div><strong>Delivery Location:</strong> {{ getLocationName(deliveryLocation) }}</div>
              <div><strong>Pickup Location:</strong> {{ getLocationName(pickupLocation) }}</div>
              <div><strong>Duration:</strong> {{ calculateDuration() }} days</div>
            </div>

            <div class="flex justify-content-between mt-4">
              <Button
                label="Previous"
                icon="pi pi-arrow-left"
                class="p-button-secondary"
                @click="goToCustomerTab"
              />
              <Button
                label="Confirm Booking"
                icon="pi pi-check"
                @click="confirmBooking"
                class="p-button-success"
              />
            </div>
          </div>
          <div v-else>
            <p class="text-center text-red-500 mt-5">
              Please complete all previous steps before proceeding to booking confirmation.
            </p>
            <div class="flex justify-content-start mt-4">
              <Button
                label="Previous"
                icon="pi pi-arrow-left"
                class="p-button-secondary"
                @click="goToCustomerTab"
              />
            </div>
          </div>
        </TabPanel>
      </TabView>
    </template>
  </Card>

  <!-- Add Location Dialog -->
  <Dialog
    v-model:visible="showLocationDialog"
    header="Add New Location"
    :style="{ width: '450px' }"
    :modal="true"
    :closable="true"
  >
    <div class="input-group-column">
      <label for="locationName" class="mb-2">Location Name</label>
      <InputText id="locationName" v-model="newLocation.name" placeholder="Enter location name" class="w-full" />

      <label for="locationAddress" class="mb-2 mt-3">Address</label>
      <InputText id="locationAddress" v-model="newLocation.address" placeholder="Enter address" class="w-full" />

      <label for="locationCity" class="mb-2 mt-3">City</label>
      <InputText id="locationCity" v-model="newLocation.city" placeholder="Enter city" class="w-full" />
    </div>

    <template #footer>
      <Button label="Cancel" icon="pi pi-times" @click="closeLocationModal" class="p-button-text" />
      <Button label="Save" icon="pi pi-check" @click="saveLocation" autofocus />
    </template>
  </Dialog>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Breadcrumb from 'primevue/breadcrumb';
import Tag from 'primevue/tag';
import RadioButton from 'primevue/radiobutton';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import DatePicker from 'primevue/datepicker';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Select from 'primevue/select';
import Dialog from 'primevue/dialog';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import ProgressSpinner from 'primevue/progressspinner';
import { useRouter } from 'vue-router';
import { useToast } from "primevue/usetoast";

// State management
const vehicles = ref([]);
const selectedVehicleId = ref(null);
const deliveryLocation = ref('');
const pickupLocation = ref('');
const locations = ref([]);
const dates = ref(null);
const clients = ref([]);
const selectedClient = ref(null);
const selectedClientId = ref(null);
const clientSearch = ref('');
const vehicleSearch = ref('');
const loading = ref(false);
const error = ref(null);
const activeTabIndex = ref(0);
const showLocationDialog = ref(false);
const router = useRouter();
const toast = useToast();

// Breadcrumb items
const items = ref([
{ label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
{ label: 'Add Booking', url: '/dashboard/add-booking' }
]);

// New location form data
const newLocation = ref({
name: '',
address: '',
city: ''
});

// Computed properties for tab validation
const isDateVehicleTabComplete = computed(() => {
return dates.value &&
       dates.value.length === 2 &&
       deliveryLocation.value &&
       pickupLocation.value &&
       selectedVehicleId.value;
});

const isCustomerTabComplete = computed(() => {
return selectedClientId.value !== null && selectedClient.value !== null;
});

// Computed property to get filtered clients based on search
const filteredClients = computed(() => {
if (!clientSearch.value.trim()) return clients.value;

const searchTerm = clientSearch.value.toLowerCase();
return clients.value.filter(client =>
  (client.firstName?.toLowerCase() || '').includes(searchTerm) ||
  (client.lastName?.toLowerCase() || '').includes(searchTerm) ||
  (client.email?.toLowerCase() || '').includes(searchTerm) ||
  (client.phone?.toLowerCase() || '').includes(searchTerm) ||
  (client.address?.toLowerCase() || '').includes(searchTerm)
);
});

// Computed property to get filtered vehicles based on search
const filteredVehicles = computed(() => {
if (!vehicleSearch.value.trim()) return vehicles.value;

const searchTerm = vehicleSearch.value.toLowerCase();
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

// Computed property to get the selected vehicle
const selectedVehicle = computed(() => {
if (!selectedVehicleId.value) return null;
return vehicles.value.find(v => v.id === selectedVehicleId.value) || null;
});

// Watch for changes in selected vehicle to update delivery location
watch(selectedVehicleId, (newId) => {
if (newId) {
  const vehicle = vehicles.value.find(v => v.id === newId);
  if (vehicle && vehicle.location) {
    const vehicleLocationId = vehicle.location.id;
    const locationOption = locations.value.find(loc => loc.value === vehicleLocationId);
    if (locationOption) {
      deliveryLocation.value = locationOption;
    }
  }
}
});

// Tab navigation methods
const goToDateVehicleTab = () => {
activeTabIndex.value = 0;
};

const goToCustomerTab = () => {
if (isDateVehicleTabComplete.value) {
  activeTabIndex.value = 1;
}
};

const goToSummaryTab = () => {
if (isCustomerTabComplete.value) {
  activeTabIndex.value = 2;
}
};

// Date picker event handler
const onDateSelect = (event) => {
if (event.value && event.value.length === 2) {
  event.target.hide();
}
};

// Location dialog methods
const openLocationModal = () => {
showLocationDialog.value = true;
};

const closeLocationModal = () => {
showLocationDialog.value = false;
// Reset form fields
newLocation.value = {
  name: '',
  address: '',
  city: ''
};
};

const saveLocation = async () => {
try {
  // Validate input fields
  if (!newLocation.value.name || !newLocation.value.address || !newLocation.value.city) {
    console.error('Please fill in all required fields');
    return;
  }

  // Submit the new location to the backend
  const response = await axios.post('/location/create', newLocation.value);

  // Add the new location to the locations list
  const createdLocation = response.data.location;
  locations.value.push({
    label: createdLocation.name,
    value: createdLocation.id
  });

  // Close the dialog
  closeLocationModal();

  // Optional: Show success message
  console.log('Location added successfully');
} catch (error) {
  console.error('Error adding location:', error);
  // Handle error
}
};

// Data fetching methods
const fetchVehicles = async (searchTerm = '') => {
try {
  const response = await axios.post('/vehicles', { search: searchTerm });
  vehicles.value = response.data.filter(vehicle => vehicle.status.toLowerCase() === 'available');
} catch (error) {
  console.error('Error fetching vehicles:', error);
}
};

const fetchLocations = async () => {
try {
  const response = await axios.post('/location');
  locations.value = response.data.map(location => {
    return {
      label: location.name,
      value: location.id
    };
  });
} catch (error) {
  console.error('Error fetching locations:', error);
}
};

const fetchClients = async () => {
loading.value = true;
error.value = null;

try {
  const response = await axios.post('/customer');
  clients.value = response.data;
} catch (err) {
  console.error('Error fetching clients:', err);
  error.value = 'Failed to load customers. Please try again later.';
} finally {
  loading.value = false;
}
};

// Search clients based on input
const searchClients = () => {
fetchClients(clientSearch.value);
};

// Event handlers
const onRowClick = (event) => {
selectedVehicleId.value = event.data.id;

// Update the delivery location if the vehicle has a location
if (event.data.location) {
  const vehicleLocationId = event.data.location.id;
  // Find the matching location in the locations dropdown options
  const locationOption = locations.value.find(loc => loc.value === vehicleLocationId);
  if (locationOption) {
    deliveryLocation.value = locationOption;
  }
}
};

const onClientRowClick = (event) => {
selectedClientId.value = event.data.id;
selectedClient.value = event.data;
};

// Helper functions
const updateDeliveryLocation = (vehicle) => {
if (vehicle.location) {
  const vehicleLocationId = vehicle.location.id;
  // Find the matching location in the locations dropdown options
  const locationOption = locations.value.find(loc => loc.value === vehicleLocationId);
  if (locationOption) {
    deliveryLocation.value = locationOption;
  }
}
};

const formatNumber = (value) => {
return new Intl.NumberFormat().format(value);
};

const capitalize = (value) => {
if (!value) return '';
return value.charAt(0).toUpperCase() + value.slice(1);
};

const getSeverity = (vehicle) => {
switch (vehicle.status.toLowerCase()) {
  case 'available':
    return 'success';
  case 'unavailable':
    return 'danger';
  case 'maintenance':
    return 'warning';
  default:
    return 'info';
}
};

// Format date for display
const formatDate = (date) => {
if (!date) return '';
return new Date(date).toLocaleDateString('en-US', {
  year: 'numeric',
  month: 'long',
  day: 'numeric'
});
};

// Get location name by ID
const getLocationName = (locationId) => {
if (!locationId) return 'Not specified';

// If locationId is an object with value property, use the value
const id = typeof locationId === 'object' ? locationId.value : locationId;

const location = locations.value.find(loc => loc.value === id);
return location ? location.label : 'Not specified';
};

// Calculate booking duration in days
const calculateDuration = () => {
if (!dates.value || !dates.value[0] || !dates.value[1]) return 0;

const start = new Date(dates.value[0]);
const end = new Date(dates.value[1]);
const diffTime = Math.abs(end - start);
const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

return diffDays;
};

// Submit the booking
const confirmBooking = async () => {
try {
  if (!selectedVehicleId.value || !selectedClient.value || !dates.value) {
    console.error('Please complete all required fields');
    return;
  }

  if (!deliveryLocation.value || !pickupLocation.value) {
    console.error('Please select both delivery and pickup locations');
    return;
  }

  const bookingData = {
    vehicleId: selectedVehicleId.value,
    customerId: selectedClient.value.id,
    startDate: dates.value[0],
    endDate: dates.value[1],
    deliveryLocationId: typeof deliveryLocation.value === 'object' ?
                      deliveryLocation.value.value :
                      deliveryLocation.value,
    pickupLocationId: typeof pickupLocation.value === 'object' ?
                   pickupLocation.value.value :
                   pickupLocation.value
  };

  // Submit booking to the backend
  const response = await axios.post('/booking/create', bookingData);

  // Show success message using toast service
  toast.add({
    severity: 'success',
    summary: 'Success',
    detail: 'Booking created successfully',
    life: 3000
  });

  // Wait a short moment before redirecting
  setTimeout(() => {
    router.push('/dashboard/bookings');
  }, 1000);

} catch (error) {
  console.error('Error creating booking:', error);
  // Show error message from response if available
  const errorMessage = error.response?.data?.message || 'Failed to create booking';
  toast.add({
    severity: 'error',
    summary: 'Error',
    detail: errorMessage,
    life: 3000
  });
}
};

// Initial data loading
onMounted(() => {
fetchVehicles();
fetchLocations();
fetchClients();
});
</script>

<style scoped>
.input-group-row {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.input-group-column {
  display: flex;
  flex-direction: column;
  margin-top: 1rem;
}

.mt-3 {
  margin-top: 1rem;
}

.mt-2 {
  margin-top: 0.5rem;
}

.mb-2 {
  margin-bottom: 0.5rem;
}

.mb-3 {
  margin-bottom: 1rem;
}

.block {
  display: block;
}

.w-full {
  width: 100%;
}

.add-location-link {
  color: var(--primary-color);
  font-size: 0.9rem;
  transition: color 0.3s;
}

.add-location-link:hover {
  transition: color 0.3s;
  text-decoration: underline;
  color:var(--p-primary-color);
}

.summary-section {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  border: 1px solid #e9ecef;
}

.text-sm {
  font-size: 0.875rem;
}

.text-gray-500 {
  color: #6c757d;
}

.text-red-500 {
  color: #dc3545;
}

.summary-row {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.summary-column {
  flex: 1;
  padding: 1rem;
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  border: 1px solid #e9ecef;
}

/* New styles for navigation */
.flex {
  display: flex;
}

.justify-content-between {
  justify-content: space-between;
}

.justify-content-end {
  justify-content: flex-end;
}

.justify-content-start {
  justify-content: flex-start;
}

.mt-4 {
  margin-top: 1.5rem;
}

/* Tab header styles */
.tab-header-content {
  display: flex;
  align-items: center;
}

/* Ensure the tabview is properly displayed */
:deep(.p-tabview) {
  width: 100%;
}

:deep(.p-tabview .p-tabview-nav) {
  display: flex;
  flex-wrap: nowrap;
  width: 100%;
  border-width: 0 0 1px 0;
  border-color: #dee2e6;
}

:deep(.p-tabview .p-tabview-nav li) {
  margin-right: 2px;
}

:deep(.p-tabview .p-tabview-panels) {
  padding: 1.5rem 0;
}

/* Style for DataTable to ensure it displays correctly */
:deep(.p-datatable) {
  width: 100%;
  overflow-x: auto;
}

.select-readonly {
  pointer-events: none;
  background-color: #f5f5f5;
}

:deep(.p-datatable-table) {
  min-width: 100%;
  table-layout: auto;
}

/* Style for disabled tabs */
:deep(.p-disabled) {
  opacity: 0.6;
  cursor: not-allowed;
  pointer-events: none;
}

/* Custom styles for the active tab */
:deep(.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link) {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

:deep(.p-tabview .p-tabview-nav li:not(.p-highlight):not(.p-disabled):hover .p-tabview-nav-link) {
  border-color: #dee2e6;
  color: #495057;
}

/* Make sure tables have proper spacing */
:deep(.p-datatable .p-datatable-thead > tr > th) {
  padding: 0.75rem 1rem;
  text-align: left;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
  padding: 0.75rem 1rem;
}
</style>