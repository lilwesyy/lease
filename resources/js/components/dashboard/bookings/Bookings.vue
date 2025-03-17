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
              <Column field="customer" header="Customer" :body="customerTemplate"></Column>
              <Column field="amount" header="Amount"></Column>
              <Column field="paymentStatus" header="Payment Status" :body="paymentStatusTemplate"></Column>
            </DataTable>
          </div>
          <div v-else>
            <p class="text-center text-gray-500 mt-5">No bookings found. Please add a booking.</p>
          </div>
        </template>
    </Card>

</template>

<script>
import { ref, h } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Breadcrumb from 'primevue/breadcrumb';
import InputText from 'primevue/inputtext';
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
    InputIcon
  },
  data() {
    return {
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Booking List', url: '/dashboard/bookings' }
      ],
      bookings: [],
      searchTerm: ''
    };
  },
  methods: {
    fetchBookings() {
      axios.post('/booking')
        .then(response => {
          // Map the response data to include customer name
          this.bookings = response.data.map(booking => {
            return {
              ...booking,
              // Adjust this based on your actual data structure
              customer: booking.user ? booking.user.name :
                      (booking.customer_data ? booking.customer_data.name : 'N/A')
            };
          });
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
    customerTemplate(slotProps) {
      // Adjust the data structure based on your actual API response
      const customer = slotProps.data.user || slotProps.data.customer_data;
      if (!customer) return 'N/A';

      return h('div', { class: 'customer-info' }, [
        h('span', {}, customer.name),
        customer.email ? h('div', { class: 'text-sm text-gray-500' }, customer.email) : null
      ]);
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
