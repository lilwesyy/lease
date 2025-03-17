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
                          :src="slotProps.data.logo"
                          :alt="`${slotProps.data.make} logo`"
                          width="90"
                          height="90"
                          v-if="slotProps.data.logo"
                          style="margin-right: 8px"
                        />
                        <div>
                          <div>
                            {{ slotProps.data.make }} {{ slotProps.data.model }}
                          </div>
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
              <p class="m-0">
                Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                accusantium doloremque laudantium, totam rem aperiam, eaque ipsa
                quae ab illo inventore veritatis et quasi architecto beatae vitae
                dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit
                aspernatur aut odit aut fugit, sed quia consequuntur magni dolores
                eos qui ratione voluptatem sequi nesciunt. Consectetur, adipisci
                velit, sed quia non numquam eius modi.
              </p>
            </TabPanel>
            <TabPanel value="2">
              <p class="m-0">
                At vero eos et accusamus et iusto odio dignissimos ducimus qui
                blanditiis praesentium voluptatum deleniti atque corrupti quos
                dolores et quas molestias excepturi sint occaecati cupiditate non
                provident, similique sunt in culpa qui officia deserunt mollitia
                animi, id est laborum et dolorum fuga. Et harum quidem rerum
                facilis est et expedita distinctio. Nam libero tempore, cum soluta
                nobis est eligendi optio cumque nihil impedit quo minus.
              </p>
            </TabPanel>
          </TabPanels>
        </Tabs>
      </template>
    </Card>
  </template>

  <script>
  import { ref, onMounted } from 'vue';
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
      Select
    },
    setup() {
      const vehicles = ref([]);
      const selectedVehicleId = ref(null);
      const deliveryLocation = ref('');
      const pickupLocation = ref('');
      const locations = ref([
        { label: 'Location 1', value: 'location1' },
        { label: 'Location 2', value: 'location2' },
        { label: 'Location 3', value: 'location3' }
      ]);

      const fetchVehicles = async () => {
        try {
          const response = await axios.post('/vehicles');
          vehicles.value = response.data.filter(vehicle => vehicle.status.toLowerCase() === 'available');
        } catch (error) {
          console.error('Error fetching vehicles:', error);
        }
      };

      onMounted(fetchVehicles);

      return {
        vehicles,
        selectedVehicleId,
        deliveryLocation,
        pickupLocation,
        locations
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
  </style>
