<template>

    <h1 class="font-bold text-3xl">Booking List</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />

    <Card>
        <template #content>
          <header class="inline-header">
            <div class="left-controls">
              <Button label="Add Booking" icon="pi pi-plus" @click="$router.push('/dashboard/add-booking')" />
              <IconField>
                <InputIcon class="pi pi-search" />
                <InputText v-model="searchTerm" placeholder="Search" />
              </IconField>
            </div>
          </header>

          <div v-if="bookings.length > 0">
            <DataTable :value="bookings" selectionMode="single" class="p-datatable-no-gridlines" @row-click="onRowClick">
            <Column field="name" header="Name"></Column>
            <Column field="date" header="Date"></Column>
            <Column field="status" header="Status" :body="statusTemplate"></Column>
            <Column field="customerName" header="Customer"></Column>
            <Column field="vehicleName" header="Vehicle"></Column>
            <Column field="amount" header="Amount"></Column>
            <Column field="paymentStatus" header="Payment Status" :body="paymentStatusTemplate"></Column>
            </DataTable>
          </div>
          <div v-else>
            <p class="text-center text-gray-500 mt-5">No bookings found. Please add a booking.</p>
          </div>
        </template>
    </Card>

    <Dialog v-model="showDialog" header="Booking Details">
        <div v-if="selectedRow">
            <p><strong>Name:</strong> {{ selectedRow.name }}</p>
            <p><strong>Date:</strong> {{ selectedRow.date }}</p>
            <p><strong>Status:</strong> {{ selectedRow.status }}</p>
            <p><strong>Customer:</strong> {{ selectedRow.customerName }}</p>
            <p><strong>Vehicle:</strong> {{ selectedRow.vehicleName }}</p>
            <p><strong>Amount:</strong> {{ selectedRow.amount }}</p>
            <p><strong>Payment Status:</strong> {{ selectedRow.paymentStatus }}</p>
        </div>
    </Dialog>

</template>

<script>
import { ref, h } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Breadcrumb from 'primevue/breadcrumb';
import InputText from 'primevue/inputtext';
import Dialog from 'primevue/dialog';
import axios from 'axios';
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
    InputText,
    IconField,
    InputIcon,
    Dialog
  },
  data() {
    return {
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Booking List', url: '/dashboard/bookings' }
      ],
      bookings: [],
      searchTerm: '',
      showDialog: false, // Stato per il dialog
      selectedRow: null  // Dati della riga selezionata
    };
  },
  methods: {
    fetchBookings() {
    axios.post('/booking')
        .then(response => {
        this.bookings = response.data.map(booking => {
            // Estrai nome cliente
            const customerName = booking.customer ?
            `${booking.customer.firstName} ${booking.customer.lastName}` : 'N/A';

            // Estrai marca e modello del veicolo
            const vehicleName = booking.vehicle && booking.vehicle.make && booking.vehicle.model ?
            `${booking.vehicle.make.name} ${booking.vehicle.model.name}` : 'N/A';

            return {
            ...booking,
            customerName: customerName,
            vehicleName: vehicleName,
            customer: booking.customer,
            vehicle: booking.vehicle
            };
        });
        console.log("Bookings processed:", this.bookings);
        })
        .catch(error => {
        console.error('Error fetching bookings:', error);
        });
    },
    statusTemplate(slotProps) {
      const severity = {
        'Confirmed': 'success',
        'Pending': 'warning',
        'Cancelled': 'danger'
      };

      return h('span', { class: `p-tag p-tag-${severity[slotProps.data.status] || 'info'}` }, slotProps.data.status);
    },
    paymentStatusTemplate(slotProps) {
      const severity = {
        'Paid': 'success',
        'Unpaid': 'danger',
        'Refunded': 'warning'
      };

      return h('span', { class: `p-tag p-tag-${severity[slotProps.data.paymentStatus] || 'info'}` }, slotProps.data.paymentStatus);
    },
    // customerTemplate(slotProps) {
    //     const customer = slotProps.data.customer;
    //     if (!customer) return 'N/A';

    //     return `${customer.firstName} ${customer.lastName}`;
    // },
    // vehicleTemplate(slotProps) {
    //     const vehicle = slotProps.data.vehicle;
    //     if (!vehicle || !vehicle.make || !vehicle.model) return 'N/A';

    //     return h('div', { class: 'vehicle-info' }, [
    //         h('span', {}, `${vehicle.make.name} ${vehicle.model.name}`),
    //         vehicle.year ? h('div', { class: 'text-sm text-gray-500' }, `${vehicle.year} - ${vehicle.color}`) : null
    //     ]);
    // },
    onRowClick(rowData) {
      this.selectedRow = rowData; // Salva i dati della riga selezionata
      this.showDialog = true;    // Mostra il dialog
    }
  },
  mounted() {
    this.fetchBookings();
  }
};
</script>

<style scoped>
.custom-breadcrumb {
  margin-bottom: 1rem;
}

.inline-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.left-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}
</style>
