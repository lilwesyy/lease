<template>
  <h1 class="font-bold text-3xl">Calendar</h1>
  <Breadcrumb :model="items" class="custom-breadcrumb" />
  <Card>
    <template #content>
      <Button label="Add Booking" icon="pi pi-plus" @click="$router.push('/dashboard/add-booking')" />
      <FullCalendar :options="calendarOptions" class="calendar" />
    </template>
  </Card>
  <Dialog header="Add Event" v-model:visible="dialogVisible" :modal="true" :closable="false">
    <div class="p-fluid">
      <div class="p-field">
        <label for="title">Title</label>
        <InputText type="text" v-model="newEvent.title" />
      </div>
      <div class="p-field">
        <label for="date">Date</label>
        <DatePicker v-model="newEvent.dates" selectionMode="multiple" />
      </div>
      <div class="p-field">
        <Button @click="hideDialog" label="Cancel" severity="secondary" />
        <Button @click="addEvent" label="Add" />
      </div>
    </div>
  </Dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Card from 'primevue/card';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import Breadcrumb from 'primevue/breadcrumb';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import InputText from 'primevue/inputtext';

const items = ref([
  { label: 'Home', url: '/' },
  { label: 'Dashboard', url: '/dashboard/home' },
]);

const calendarOptions = ref({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  events: [],
  eventContent: (arg) => {
    return {
      html: `
        <div class="p-2">
          <div class="font-bold">${arg.event.extendedProps.vehicleName}</div>
          <div>${arg.event.extendedProps.customerName}</div>
          <div class="text-sm">${arg.event.extendedProps.locations}</div>
        </div>
      `
    }
  }
});

const dialogVisible = ref(false);
const newEvent = ref({ title: '', dates: [] });

const fetchBookings = async () => {
  try {
    const response = await axios.post('/booking');
    const bookings = response.data.map(booking => ({
      title: `${booking.vehicle.make.name} ${booking.vehicle.model.name}`,
      start: booking.startDate,
      end: booking.endDate,
      extendedProps: {
        vehicleName: `${booking.vehicle.make.name} ${booking.vehicle.model.name}`,
        customerName: `${booking.customer.firstName} ${booking.customer.lastName}`,
        locations: `${booking.pickup_location.name} → ${booking.delivery_location.name}`
      }
    }));
    calendarOptions.value.events = bookings;
  } catch (error) {
    console.error('Error fetching bookings:', error);
  }
};

const showDialog = () => {
  dialogVisible.value = true;
};

const hideDialog = () => {
  dialogVisible.value = false;
};

const addEvent = () => {
  newEvent.value.dates.forEach(date => {
    calendarOptions.value.events.push({ title: newEvent.value.title, date: date.toISOString().split('T')[0] });
  });
  newEvent.value = { title: '', dates: [] };
  hideDialog();
};

onMounted(() => {
  fetchBookings();
});
</script>

<style>
.calendar {
  max-height: 70vh;
  overflow-y: auto;
}

/* Add these styles for better event display */
.fc-event {
  padding: 4px;
}

.fc-event-title {
  white-space: normal !important;
  overflow: visible !important;
}
</style>