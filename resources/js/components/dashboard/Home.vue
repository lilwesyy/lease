<template>
    <div class="dashboard">
      <div class="column">
        <h2>Last Bookings</h2>
        <p-card v-for="booking in lastBookings" :key="booking.id" class="card">
          <template #title>
            Booking ID: {{ booking.id }}
          </template>
          <template #content>
            {{ booking.details }}
          </template>
        </p-card>
      </div>
  
      <div class="column">
        <h2>Last Vehicles</h2>
        <p-card v-for="vehicle in lastVehicles" :key="vehicle.id" class="card">
          <template #title>
            Vehicle ID: {{ vehicle.id }}
          </template>
          <template #content>
            {{ vehicle.details }}
          </template>
        </p-card>
      </div>
  
      <div class="full-width">
        <h2>Calendar</h2>
        <p-calendar v-model="calendarDate"></p-calendar>
  
        <h2>Additional Info</h2>
        <p>{{ additionalInfo }}</p>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  import 'primeicons/primeicons.css';
  import Button from 'primevue/button';
  import Chart from 'primevue/chart';
  import Calendar from 'primevue/calendar';
  import Card from 'primevue/card';
  
  export default {
    name: 'Home',
    components: {
      'p-button': Button,
      'p-chart': Chart,
      'p-calendar': Calendar,
      'p-card': Card
    },
    setup() {
      const chartData = ref({
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [
          {
            label: 'My First dataset',
            backgroundColor: '#42A5F5',
            borderColor: '#1E88E5',
            data: [65, 59, 80, 81, 56, 55, 40]
          }
        ]
      });
  
      const handleClick = () => {
        alert('Button clicked!');
      };
  
      const lastBookings = ref([
        { id: 1, details: 'Booking for John Doe on 2025-02-18 at 10:00 AM' },
        { id: 2, details: 'Booking for Jane Smith on 2025-02-19 at 02:00 PM' },
        { id: 3, details: 'Booking for Alice Johnson on 2025-02-20 at 09:00 AM' }
      ]);
  
      const lastVehicles = ref([
        { id: 1, details: 'Vehicle: Tesla Model S, License Plate: ABC123' },
        { id: 2, details: 'Vehicle: BMW X5, License Plate: XYZ789' },
        { id: 3, details: 'Vehicle: Audi A4, License Plate: LMN456' }
      ]);
  
      const calendarDate = ref(null);
  
      const additionalInfo = ref('Some additional information about the dashboard.');
  
      return {
        chartData,
        handleClick,
        lastBookings,
        lastVehicles,
        calendarDate,
        additionalInfo
      };
    }
  };
  </script>
  
  <style scoped>
  .dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }
  
  .column {
    flex: 1;
    min-width: 300px;
  }
  
  .full-width {
    flex: 1 1 100%;
  }
  
  .card {
    margin-bottom: 20px;
  }
  
  h2 {
    color: #2c3e50;
    margin-bottom: 10px;
  }
  
  p-calendar {
    margin-bottom: 20px;
  }
  
  p {
    color: #34495e;
    font-size: 14px;
  }
  </style>