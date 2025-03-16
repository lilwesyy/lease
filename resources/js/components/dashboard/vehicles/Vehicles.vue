<template>
    <div>
      <h1 class="font-bold text-3xl">Vehicles List</h1>
      <Breadcrumb :model="items" class="custom-breadcrumb" />
      <Card>
        <template #content>
            <header class="p-d-flex p-ai-center p-gap-2 inline-header">
            <Button label="Add Vehicle" icon="pi pi-plus" @click="$router.push('/dashboard/add-vehicles')" />

            <IconField class="p-d-flex p-ai-center">
                <InputIcon class="pi pi-search" />
                <InputText v-model="value1" placeholder="Search" />
            </IconField>
            </header>

          <DataTable :value="vehicles" selectionMode="single" class="p-datatable-no-gridlines" @row-click="onRowClick">
            <Column header="Brand and Model">
              <template #body="slotProps">
                <div style="display: flex; align-items: center;">
                  <img :src="slotProps.data.logo" :alt="`${slotProps.data.make} logo`" width="90" height="90" v-if="slotProps.data.logo" style="margin-right: 8px;" />
                  <div>
                    <div>{{ slotProps.data.make }} {{ slotProps.data.model }}</div>
                    <div style="font-size: 0.8em; color: grey;">
                      {{ slotProps.data.year }}, {{ capitalize(slotProps.data.fuel_type) }}, {{ capitalize(slotProps.data.transmission) }}
                    </div>
                  </div>
                </div>
              </template>
            </Column>
            <Column header="Color" style="width: 50px;">
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
            <Column field="plateNumber" header="Plate" style="width: 100px;" />
            <Column field="location" header="Location" style="width: 100px;" />
            <Column header="Status" style="width: 100px;">
              <template #body="slotProps">
                <Tag :value="capitalize(slotProps.data.status)" :severity="getSeverity(slotProps.data)" />
              </template>
            </Column>
          </DataTable>
        </template>
      </Card>

      <Dialog :visible="showDetailsModal" header="Vehicle Details" modal @update:visible="showDetailsModal = $event" :style="{ width: '70vw'}">
        <AddVehicle :vehicle="selectedVehicle" :isViewMode="true"/>
      </Dialog>
    </div>
  </template>

  <script>
  import { ref } from 'vue';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Button from 'primevue/button';
  import Dialog from 'primevue/dialog';
  import TabView from 'primevue/tabview';
  import TabPanel from 'primevue/tabpanel';
  import Breadcrumb from 'primevue/breadcrumb';
  import Card from 'primevue/card';
  import Tag from 'primevue/tag';
  import IconField from 'primevue/iconfield';
  import InputIcon from 'primevue/inputicon';
  import InputText from 'primevue/inputtext';
  import Divider from 'primevue/divider';
  import AddVehicle from './AddVehicle.vue';
  
  export default {
    name: 'VehiclesList',
    components: {
      DataTable,
      Column,
      Button,
      Dialog,
      Breadcrumb,
      Card,
      Tag,
      IconField,
      InputIcon,
      InputText,
      TabView,
      TabPanel,
      Divider,
      AddVehicle
    },
    setup() {

    const capitalize = (str) => {
      if (!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1);
    };

    const formatNumber = (number) => {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
      const value1 = ref('');
      const vehicles = ref([
          {
            id: 1,
            logo: 'https://www.carlogos.org/car-logos/toyota-logo-2005-640.png',
            make: 'Toyota',
            model: 'Corolla',
            year: 2020,
            seats: 5,
            transmission: 'automatic',
            color: 'white',
            plateNumber: 'ABC1234',
            bodyType: 'sedan',
            location: 'Torino',
            dailyPrice: '$50',
            status: 'available',
            odometer: 120000,
            fuel_type: 'petrol',
            km_per_day: 100,
            extra_km_price: 0.5,
            basic_daily_price: 50,
            franchise: 500,
            imageUrl: 'https://media.ed.edmunds-media.com/toyota/corolla-hatchback/2020/oem/2020_toyota_corolla-hatchback_4dr-hatchback_nightshade-edition_fq_oem_1_1600.jpg',
            damages: [
            {
              damagePosition: 'front_bumper',
              damagePositionLabel: 'Front Bumper',
              damageSeverity: 'severe',
              damageSeverityLabel: 'Severe',
              damageType: 'dent',
              damageTypeLabel: 'Dent',
              damageDescription: 'Severe dent and scratches'
            }
            ]
          },
          {
            id: 2,
            logo: 'https://www.carlogos.org/car-logos/honda-logo-2000-full-640.png',
            make: 'Honda',
            model: 'Civic',
            year: 2019,
            seats: 5,
            transmission: 'manual',
            color: 'black',
            plateNumber: 'XYZ5678',
            bodyType: 'sedan',
            location: 'Milano',
            dailyPrice: '$45',
            status: 'unavailable',
            odometer: 90000,
            fuel_type: 'petrol',
            km_per_day: 80,
            extra_km_price: 0.4,
            basic_daily_price: 45,
            franchise: 400,
            imageUrl: 'https://www.cnet.com/a/img/resize/df2edfd143035974957c05a677468f2fbd767569/hub/2019/05/20/95e44f16-4eda-427d-aae4-1df5309547f6/2019-honda-civic-touring-sedan-1.jpg?auto=webp&width=768',
            damages: [
            {
              damagePosition: 'rear_bumper',
              damagePositionLabel: 'Rear Bumper',
              damageSeverity: 'moderate',
              damageSeverityLabel: 'Moderate',
              damageType: 'scratch',
              damageTypeLabel: 'Scratch',
              damageDescription: 'Moderate scratches'
            }
            ]
          },
          {
            id: 3,
            logo: 'https://www.carlogos.org/car-logos/ford-logo-2017-640.png',
            make: 'Ford',
            model: 'Focus',
            year: 2018,
            seats: 5,
            transmission: 'automatic',
            color: 'blue',
            plateNumber: 'LMN8765',
            bodyType: 'sedan',
            location: 'Torino',
            dailyPrice: '$40',
            status: 'maintenance',
            odometer: 100000,
            fuel_type: 'diesel',
            km_per_day: 120,
            extra_km_price: 0.6,
            basic_daily_price: 40,
            franchise: 450,
            imageUrl: 'https://rcs.cdn.publieditor.it/w640/M1273_01.jpg',
            damages: [
            {
              damagePosition: 'rear_bumper',
              damagePositionLabel: 'Rear Bumper',
              damageSeverity: 'moderate',
              damageSeverityLabel: 'Moderate',
              damageType: 'scratch',
              damageTypeLabel: 'Scratch',
              damageDescription: 'Moderate scratches'
            }
            ]
          }
        ]);

      const showModal = ref(false);
      const showDetailsModal = ref(false);
      const selectedVehicle = ref(null); // <-- aggiunto per memorizzare il veicolo selezionato

      const onRowClick = (event) => {
        selectedVehicle.value = event.data; // <-- memorizza i dati del veicolo selezionato
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
        { label: 'Vehicles List', url: '/dashboard/vehicles' }
      ];

      return {
        capitalize,
        value1,
        vehicles,
        showModal,
        showDetailsModal,
        selectedVehicle, // <-- esponi selectedVehicle
        onRowClick,
        addVehicle,
        getSeverity,
        formatNumber,
        items
      };
    }
  };
  </script>

  <style>
  .isviewed{
    box-shadow:none!important;
  }
  .inline-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    }
  </style>
