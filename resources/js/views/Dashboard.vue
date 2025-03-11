<template>
    <div>
      <div class="flex h-screen">
        <Dash />
      </div> 
    </div>
  </template>

  <script>
  import Sidebar from '../components/Sidebar.vue';
  import TopBar from '../components/TopBar.vue';
  import Dash from '../components/Dash.vue';
  import { ref } from 'vue';
  import { Calendar, Button, Dialog, DataTable, Column } from 'primevue';

  export default {
    name: 'Dashboard',
    components: {
      Sidebar,
      TopBar,
      Dash
    },
  }
  </script>

  <style scoped>
  .content {
    /* margin-left: 270px; Adjust based on sidebar width */
    padding: 20px;
  }
  </style>
