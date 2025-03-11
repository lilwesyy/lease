<template>
    <div>
      <DataTable :value="bookings" showGridlines tableStyle="min-width: 50rem">
        <Column field="id" header="ID"></Column>
        <Column field="name" header="Name"></Column>
        <Column field="date" header="Date"></Column>
        <Column field="status" header="Status" :body="statusTemplate"></Column>
        <Column field="customer" header="Customer"></Column>
        <Column field="amount" header="Amount"></Column>
        <Column field="paymentStatus" header="Payment Status" :body="paymentStatusTemplate"></Column>
      </DataTable>
    </div>
  </template>

  <script>
  import { ref, h } from 'vue';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Button from 'primevue/button';
  import 'primeicons/primeicons.css';

  export default {
    name: 'Bookings',
    components: {
      DataTable,
      Column,
      Button
    },
    data() {
      return {
        bookings: [
          { id: 1, name: 'Booking 1', date: '2025-02-17', status: 'Confirmed', customer: 'John Doe', amount: 100, paymentStatus: 'Paid' },
          { id: 2, name: 'Booking 2', date: '2025-02-18', status: 'Pending', customer: 'Jane Smith', amount: 200, paymentStatus: 'Unpaid' },
          { id: 3, name: 'Booking 3', date: '2025-02-19', status: 'Cancelled', customer: 'Alice Johnson', amount: 150, paymentStatus: 'Refunded' }
        ]
      };
    },
    methods: {
      statusTemplate(rowData) {
        const iconMap = {
          Confirmed: 'pi pi-check-circle text-green-500',
          Pending: 'pi pi-clock text-yellow-500',
          Cancelled: 'pi pi-times-circle text-red-500'
        };
        return h('i', { class: iconMap[rowData.status] || '' });
      },
      paymentStatusTemplate(rowData) {
        const iconMap = {
          Paid: 'pi pi-dollar text-green-500',
          Unpaid: 'pi pi-exclamation-circle text-red-500',
          Refunded: 'pi pi-undo text-blue-500'
        };
        return h('i', { class: iconMap[rowData.paymentStatus] || '' });
      },
      editBooking(booking) {
        console.log('Modifica prenotazione:', booking);
      },
      deleteBooking(booking) {
        console.log('Elimina prenotazione:', booking);
      }
    }
  };
  </script>

  <style scoped>
  .table-title {
    margin-bottom: 1rem;
    font-size: 1.5rem;
    font-weight: bold;
  }
  .p-button {
    margin-right: 0.5rem;
  }
  </style>
