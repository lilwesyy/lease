<template>
    <div class="sidebar">
      <div class="logo">
        <!-- <img src="/public/img/logo.png" alt="Logo"> -->
         <h1 class="brand-name">Lease</h1>
      </div>
      <PanelMenu :model="items">
        <template #item="{ item }">
          <router-link :to="item.to" v-ripple class="flex items-center px-4 py-2 cursor-pointer group" :class="{ 'settings-item': item.label === 'Settings' }">
            <span :class="[item.icon, 'text-primary group-hover:text-inherit']" />
            <span class="ml-2">{{ item.label }}</span>
            <Badge v-if="item.badge" class="ml-auto" :value="item.badge" />
            <span v-if="item.shortcut" class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">{{ item.shortcut }}</span>
          </router-link>
        </template>
      </PanelMenu>
    </div>
  </template>

  <script>
  import 'primeicons/primeicons.css';
  import Card from 'primevue/card';
  import Button from 'primevue/button';
  import PanelMenu from 'primevue/panelmenu';
  import Badge from 'primevue/badge';

  export default {
    name: 'Sidebar',
    components: {
      Card,
      Button,
      PanelMenu,
      Badge
    },
    data() {
      return {
        items: [
          {
            label: 'Dashboard',
            icon: 'pi pi-fw pi-home',
            to: '/dashboard'
          },
          {
            label: 'Clients',
            icon: 'pi pi-fw pi-users',
            items: [
              {
                label: 'Client List',
                icon: 'pi pi-fw pi-list',
                to: '/dashboard/clients'
              },
              {
                label: 'Add Client',
                icon: 'pi pi-fw pi-user-plus',
                to: '/dashboard/clients/add'
              }
            ]
          },
          {
            label: 'Vehicles',
            icon: 'pi pi-fw pi-car',
            items: [
              {
                label: 'Vehicle List',
                icon: 'pi pi-fw pi-list',
                to: '/dashboard/vehicles'
              },
              {
                label: 'Add Vehicle',
                icon: 'pi pi-fw pi-plus',
                to: '/dashboard/vehicles/add'
              }
            ]
          },
          {
            label: 'Bookings',
            icon: 'pi pi-fw pi-calendar',
            items: [
              {
                label: 'Booking List',
                icon: 'pi pi-fw pi-list',
                to: '/dashboard/bookings'
              },
              {
                label: 'New Booking',
                icon: 'pi pi-fw pi-plus',
                to: '/dashboard/bookings/add'
              }
            ]
          },
          {
            label: 'Reports',
            icon: 'pi pi-fw pi-chart-line',
            to: '/dashboard/reports'
          },
          {
            label: 'Settings',
            icon: 'pi pi-fw pi-cog',
            to: '/dashboard/settings'
          }
        ]
      };
    }
  }
  </script>

  <style scoped>
  .sidebar {
    width: 250px;
    padding: 20px;
    background-color: #f8f9fa;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
  }

  .sidebar .logo img {
    max-width: 100%;
    height: auto;
    margin-bottom: 20px;
  }
  </style>
