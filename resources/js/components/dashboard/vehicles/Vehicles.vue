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

      <Dialog v-model:visible="showDetailsModal" header="Vehicle Details" modal :style="{ width: '70vw'}">
        <AddVehicle :vehicle="selectedVehicle" :isViewMode="true"/>
      </Dialog>
    </div>
  </template>

  <script>
  import { ref, onMounted } from 'vue';
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
      const vehicles = ref([]);
      const showModal = ref(false);
      const showDetailsModal = ref(false);
      const selectedVehicle = ref(null);

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
        { label: 'Vehicles List', url: '/dashboard/vehicles' }
      ];

      const fetchVehicles = async () => {
        try {
          const response = await axios.post('/vehicles');
          vehicles.value = response.data;
        } catch (error) {
          console.error('Error fetching vehicles:', error);
        }
      };

      onMounted(() => {
        fetchVehicles();
      });

      return {
        capitalize,
        value1,
        vehicles,
        showModal,
        showDetailsModal,
        selectedVehicle,
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
  .isviewed {
    box-shadow: none !important;
  }
  .inline-header {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  </style>
