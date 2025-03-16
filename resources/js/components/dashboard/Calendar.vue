<template>
    <h1 class="font-bold text-3xl">Calendar</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />
    <Card>
        <template #content>
          <Button @click="showDialog" label="New Booking" />
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
          <Button @click="hideDialog" label="Cancel" severity="secondary"  />
          <Button @click="addEvent" label="Add" />
        </div>
      </div>
    </Dialog>
  </template>
  
  <script>
  import { ref } from 'vue';
  import Card from 'primevue/card';
  import FullCalendar from '@fullcalendar/vue3';
  import dayGridPlugin from '@fullcalendar/daygrid';
  import Breadcrumb from 'primevue/breadcrumb';
  import Dialog from 'primevue/dialog';
  import Button from 'primevue/button';
  import DatePicker from 'primevue/datepicker';
  import InputText from 'primevue/inputtext';
  
  export default {
    name: 'Home',
    components: {
      Card,
      FullCalendar,
      Breadcrumb,
      Dialog,
      Button,
      DatePicker,
      InputText
    },
    setup() {
      const items = ref([
        { label: 'Home', url: '/' },
        { label: 'Dashboard', url: '/dashboard/home' },
      ]);
  
      const calendarOptions = ref({
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        events: [
          { title: 'BMW M3', date: '2025-03-14' },
          { title: 'Toyota Corolla', date: '2025-03-15' }
        ]
      });
  
      const dialogVisible = ref(false);
      const newEvent = ref({ title: '', dates: [] });
  
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
  
      return {
        items,
        calendarOptions,
        dialogVisible,
        newEvent,
        showDialog,
        hideDialog,
        addEvent
      };
    }
  };
  </script>
  
  <style scoped>
  .calendar {
    max-height: 70vh;
    overflow-y: auto;
  }
  </style>