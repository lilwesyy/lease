<template>

<h1 class="font-bold text-3xl">Booking List</h1>
<Breadcrumb :model="items" class="custom-breadcrumb" />

<Card>
    <template #content>
        <DataTable :value="bookings" tableStyle="min-width: 50rem">
        <Column field="id" header="ID"></Column>
        <Column field="name" header="Name"></Column>
        <Column field="date" header="Date"></Column>
        <Column field="status" header="Status" :body="statusTemplate"></Column>
        <Column field="customer" header="Customer"></Column>
        <Column field="amount" header="Amount"></Column>
        <Column field="paymentStatus" header="Payment Status" :body="paymentStatusTemplate"></Column>
      </DataTable>
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

  export default {
    name: 'Bookings',
    components: {
      DataTable,
      Column,
      Button,
      Card,
      Breadcrumb
    },
    data() {
      return {
        items: [
            { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
            { label: 'Booking List', url: '/dashboard/bookings' }
        ],
        bookings: [
          { id: 1, name: 'Booking 1', date: '2025-02-17', status: 'Confirmed', customer: 'John Doe', amount: 100, paymentStatus: 'Paid' },
          { id: 2, name: 'Booking 2', date: '2025-02-18', status: 'Pending', customer: 'Jane Smith', amount: 200, paymentStatus: 'Unpaid' },
          { id: 3, name: 'Booking 3', date: '2025-02-19', status: 'Cancelled', customer: 'Alice Johnson', amount: 150, paymentStatus: 'Refunded' }
        ]
      };
    },
  };
  </script>
