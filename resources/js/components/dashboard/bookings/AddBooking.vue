<template>
    <h1 class="font-bold text-3xl">Add Booking</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />

    <Card>
      <template #content>
        <Tabs value="0">
          <TabList>
            <Tab value="0"><i class="pi pi-car mr-2"></i>Date & Vehicle</Tab>
            <Tab value="1"><i class="pi pi-user mr-2"></i>Customer</Tab>
            <Tab value="2"><i class="pi pi-list-check mr-2"></i>Summary</Tab>
          </TabList>
          <TabPanels>
            <TabPanel value="0">
              <p class="mt-2 mb-2 grey">Fill out the info needed to create a new booking</p>
              <DatePicker v-model="dates" showIcon fluid iconDisplay="input" selectionMode="range" placeholder="Select Dates" :manualInput="false" />

              <div class="input-group-row">
                <InputGroup>
                  <InputGroupAddon>
                    <i class="pi pi-map-marker"></i>
                  </InputGroupAddon>
                  <Select v-model="deliveryLocation" :options="locations" optionLabel="label" placeholder="Delivery Location" />
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
                <InputText v-model="vehicleSearch" placeholder="Search Vehicles" @keyup.enter="searchVehicles" />
                <Button label="Add Vehicle"  class="ml-3" icon="pi pi-plus" @click="$router.push('/dashboard/add-vehicles')" />
              </IconField>
              <div v-if="vehicles.length > 0">
                <DataTable
                  :value="vehicles"
                  selectionMode="single"
                  class="p-datatable-no-gridlines"
                  @row-click="onRowClick"
                >
                  <Column header="Select" style="width: 50px">
                    <template #body="slotProps">
                      <RadioButton
                        v-model="selectedVehicleId"
                        :inputId="`vehicle-${slotProps.data.id}`"
                        :name="`selectedVehicle`"
                        :value="slotProps.data.id"
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
                            {{ slotProps.data.year }},
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
                  <Column
                    field="location"
                    header="Location"
                    style="width: 100px"
                  />
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
            </TabPanel>
            <TabPanel value="1">
                <IconField class="p-d-flex p-ai-center mb-3">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="clientSearch" placeholder="Search Customers" @keyup.enter="searchClients" />
                    <Button label="Add New Customer" icon="pi pi-plus" @click="$router.push('/dashboard/add-customer')" class="ml-3" />

                </IconField>

                <div v-if="clients.length > 0">
                    <DataTable
                        :value="clients"
                        selectionMode="single"
                        dataKey="id"
                        @row-click="onClientRowClick"
                        class="p-datatable-no-gridlines"
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
            </TabPanel>
            <TabPanel value="2">
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
                      <div><strong>Make & Model:</strong> {{ selectedVehicle.make }} {{ selectedVehicle.model }}</div>
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

                <Button label="Confirm Booking" icon="pi pi-check" @click="confirmBooking" class="mt-4" />
              </div>
              <div v-else>
                <p class="text-center text-red-500 mt-5">
                  Please complete all previous steps before proceeding to booking confirmation.
                </p>
              </div>
            </TabPanel>
          </TabPanels>
        </Tabs>
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

<script>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Breadcrumb from 'primevue/breadcrumb';
import Tag from 'primevue/tag';
import RadioButton from 'primevue/radiobutton';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import DatePicker from 'primevue/datepicker';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Select from 'primevue/select';
import Dialog from 'primevue/dialog';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';

export default {
  name: 'Bookings',
  components: {
    DataTable,
    Column,
    Button,
    Card,
    Breadcrumb,
    Tag,
    RadioButton,
    Tabs,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
    DatePicker,
    InputText,
    InputGroup,
    InputGroupAddon,
    Select,
    Dialog,
    IconField,
    InputIcon
  },
  setup() {
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

    // Watch for changes in selectedClientId
    watch(selectedClientId, (newValue) => {
      if (newValue) {
        selectedClient.value = clients.value.find(c => c.id === newValue) || null;
        console.log('Client selected by ID:', selectedClient.value);
      }
    });

    // Computed property to get the selected vehicle
    const selectedVehicle = computed(() => {
      if (!selectedVehicleId.value) return null;
      return vehicles.value.find(v => v.id === selectedVehicleId.value) || null;
    });

    // Add location dialog
    const showLocationDialog = ref(false);
    const newLocation = ref({
      name: '',
      address: '',
      city: ''
    });

    // Fetch clients data
    const fetchClients = async (searchTerm = '') => {
      try {
        // You can add search parameter to filter clients
        const response = await axios.post('/customer', { search: searchTerm });
        clients.value = response.data;
      } catch (error) {
        console.error('Error fetching clients:', error);
      }
    };

    // Show client details when a row is selected
    const showClientDetails = (event) => {
      selectedClient.value = event.data;
      selectedClientId.value = event.data.id;
      console.log('Client selected:', event.data);
    };

    // Handle client row click
    const onClientRowClick = (event) => {
      selectedClientId.value = event.data.id;
      selectedClient.value = event.data;
      console.log('Client row clicked:', event.data);
    };

    // Search clients based on input
    const searchClients = () => {
      fetchClients(clientSearch.value);
    };

    // Search vehicles based on input
    const searchVehicles = () => {
      fetchVehicles(vehicleSearch.value);
    };

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
        console.log('Booking created successfully:', response.data);

        // Redirect to booking list or show success message
        // this.$router.push('/dashboard/bookings');
      } catch (error) {
        console.error('Error creating booking:', error);
      }
    };

    onMounted(() => {
      fetchVehicles();
      fetchLocations();
      fetchClients();
    });

    return {
      vehicles,
      selectedVehicleId,
      selectedVehicle,
      deliveryLocation,
      pickupLocation,
      locations,
      dates,
      showLocationDialog,
      newLocation,
      openLocationModal,
      closeLocationModal,
      saveLocation,
      clients,
      selectedClient,
      selectedClientId,
      showClientDetails,
      clientSearch,
      searchClients,
      vehicleSearch,
      searchVehicles,
      formatDate,
      getLocationName,
      calculateDuration,
      confirmBooking,
      onClientRowClick
    };
  },
  data() {
    return {
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Add Booking', url: '/dashboard/add-booking' }
      ]
    };
  },
  methods: {
    onRowClick(event) {
      this.selectedVehicleId = event.data.id;
      console.log('Row clicked:', event.data);
      // Handle row click event
    },
    formatNumber(value) {
      return new Intl.NumberFormat().format(value);
    },
    capitalize(value) {
      if (!value) return '';
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
    getSeverity(vehicle) {
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
    }
  }
};
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
</style>
