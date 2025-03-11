<template>
  <div>
    <header class="p-d-flex p-jc-between p-ai-center">
      <Button label="Add Vehicle" icon="pi pi-plus" @click="showModal = true" />
    </header>
    <DataTable :value="vehicles" selectionMode="single" class="p-datatable-gridlines" @row-click="onRowClick">
      <Column field="id" header="ID" />
      <Column header="Image" :body="imageUrl" />
      <Column field="make" header="Make" />
      <Column field="model" header="Model" />
      <Column field="year" header="Year" />
      <Column field="engine" header="Engine" />
      <Column field="seats" header="Seats" />
      <Column field="transmission" header="Transmission" />
      <Column field="mileage" header="Mileage" />
      <Column field="color" header="Color" />
      <Column field="dailyPrice" header="Daily Price" />
      <Column field="status" header="Status" />
    </DataTable>

    <AddVehicleDialog
      :showModal="showModal"
      :newVehicle="newVehicle"
      :addVehicle="addVehicle"
      @update:showModal="showModal = $event"
    />

    <Dialog :visible="showDetailsModal" header="Vehicle Details" modal @update:visible="showDetailsModal = $event" :style="{ width: '50vw'}">
      <div v-if="selectedVehicle">
        <TabView>
          <TabPanel header="Details">
            <p><strong>Make:</strong> {{ selectedVehicle.make }}</p>
            <p><strong>Model:</strong> {{ selectedVehicle.model }}</p>
            <p><strong>Year:</strong> {{ selectedVehicle.year }}</p>
            <p><strong>Engine:</strong> {{ selectedVehicle.engine }}</p>
            <p><strong>Seats:</strong> {{ selectedVehicle.seats }}</p>
            <p><strong>Transmission:</strong> {{ selectedVehicle.transmission }}</p>
            <p><strong>Mileage:</strong> {{ selectedVehicle.mileage }}</p>
            <p><strong>Color:</strong> {{ selectedVehicle.color }}</p>
            <p><strong>Daily Price:</strong> {{ selectedVehicle.dailyPrice }}</p>
            <p><strong>Status:</strong> {{ selectedVehicle.status }}</p>
          </TabPanel>
          <TabPanel header="Image">
            <Image :src="selectedVehicle.imageUrl" :alt="`${selectedVehicle.make} ${selectedVehicle.model}`" width="200" />
          </TabPanel>
        </TabView>
      </div>
    </Dialog>
  </div>
</template>

<script>
import { ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import AddVehicleDialog from './AddVehicleDialog.vue';

export default {
  name: 'Vehicles',
  components: {
    DataTable,
    Column,
    Button,
    Dialog,
    Image,
    TabView,
    TabPanel,
    AddVehicleDialog
  },
  setup() {
    const vehicles = ref([
      { id: 1, make: 'Toyota', model: 'Corolla', year: 2020, engine: '1.8L I4', seats: 5, transmission: 'Automatic', mileage: '20,000 km', color: 'White', dailyPrice: '$50', status: 'Available', imageUrl: 'https://media.ed.edmunds-media.com/toyota/corolla-hatchback/2020/oem/2020_toyota_corolla-hatchback_4dr-hatchback_nightshade-edition_fq_oem_1_1600.jpg' },
      { id: 2, make: 'Honda', model: 'Civic', year: 2019, engine: '2.0L I4', seats: 5, transmission: 'Manual', mileage: '30,000 km', color: 'Black', dailyPrice: '$45', status: 'Unavailable', imageUrl: 'https://www.cnet.com/a/img/resize/df2edfd143035974957c05a677468f2fbd767569/hub/2019/05/20/95e44f16-4eda-427d-aae4-1df5309547f6/2019-honda-civic-touring-sedan-1.jpg?auto=webp&width=768' },
      { id: 3, make: 'Ford', model: 'Focus', year: 2018, engine: '2.0L I4', seats: 5, transmission: 'Automatic', mileage: '25,000 km', color: 'Blue', dailyPrice: '$40', status: 'Available', imageUrl: 'https://rcs.cdn.publieditor.it/w640/M1273_01.jpg' }
    ]);

    const showModal = ref(false);
    const showDetailsModal = ref(false);
    const selectedVehicle = ref(null);

    const newVehicle = ref({
      make: '', model: '', year: '', engine: '', seats: '', transmission: '', mileage: '', color: '', dailyPrice: '', status: '', imageUrl: ''
    });

    const addVehicle = () => {
      vehicles.value.push({ ...newVehicle.value, id: vehicles.value.length + 1 });
      showModal.value = false;
      newVehicle.value = { make: '', model: '', year: '', engine: '', seats: '', transmission: '', mileage: '', color: '', dailyPrice: '', status: '', imageUrl: '' };
    };

    const imageTemplate = (rowData) => {
      return `<img src="${rowData.imageUrl}" alt="${rowData.make} ${rowData.model}" width="200" />`;
    };

    const onRowClick = (event) => {
      selectedVehicle.value = event.data;
      showDetailsModal.value = true;
    };

    return {
      vehicles,
      showModal,
      showDetailsModal,
      selectedVehicle,
      newVehicle,
      addVehicle,
      imageTemplate,
      onRowClick
    };
  }
};
</script>

<style scoped>
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.p-datatable-gridlines {
  width: 100%;
}
</style>