<template>
  <!-- Sidebar -->
  <div :class="['sidebar', { 'sidebar-open': isSidebarOpen }]" class="w-64 flex flex-col justify-between m-6 rounded-lg">
    <div>
      <div class="font-xl text-center p-4">
        <!-- <h1 class="font-bold text-4xl">Lease</h1> -->
        <img src="/logo.png" alt="">
        <!-- <h2 class="text-xl text-gray-500 company-name">Rental Company Name</h2> -->
      </div>
      <Divider />
      <PanelMenu :model="menuItems" class="w-full">
        <template #item="{ item }">
          <router-link v-if="item.to" v-slot="{ href, navigate }" :to="item.to" custom>
            <a v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="href" @click="navigate">
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
              <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
            </a>
          </router-link>
          <a v-else v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="item.url" :target="item.target">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
            <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
          </a>
        </template>
      </PanelMenu>
    </div>
    <div class="mb-4">
      <PanelMenu :model="bottomMenuItems" class="w-full">
        <template #item="{ item }">
          <router-link v-if="item.to" v-slot="{ href, navigate }" :to="item.to" custom>
            <a v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="href" @click="navigate">
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
              <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
            </a>
          </router-link>
          <a v-else v-ripple class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2" :href="item.url" :target="item.target">
            <span :class="item.icon" />
            <span class="ml-2">{{ item.label }}</span>
            <span v-if="item.items" class="pi pi-angle-down text-primary ml-auto" />
          </a>
        </template>
      </PanelMenu>
      <Divider />
      <div class="flex items-center justify-center">
        <Avatar label="P" class="mr-2" size="large" shape="circle" />
        <div class="text-center">
          <div class="font-bold">John Doe</div>
          <div class="text-sm text-gray-500">Administrator</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Toggle Button for Mobile -->

  <Dialog header="Confirm Logout" v-model:visible="showLogoutDialog" modal>
    <span>Are you sure you want to log out?</span>
    <template #footer>
      <div class="w-full flex justify-center">
        <Button label="Yes" icon="pi pi-check" severity="danger" class="w-full text-lg py-3" @click="logout" />
      </div>
    </template>
  </Dialog>

  <!-- Main Content -->
  <div class="main-content flex-1 flex flex-col main-content-bg">
    <Menubar :model="navbarItems"></Menubar>

    <div class="p-4">
      <div class="container mx-auto max-w-7xl">
        <main class="content scrollable-content">
          <router-view></router-view>
        </main>
      </div>
    </div>
  </div>
</template>

<script>
import PanelMenu from 'primevue/panelmenu';
import InputText from 'primevue/inputtext';
import Badge from 'primevue/badge';
import Card from 'primevue/card';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import Avatar from 'primevue/avatar';
import Ripple from 'primevue/ripple';
import Menubar from 'primevue/menubar';
import Dialog from 'primevue/dialog';
import axios from 'axios'; // Importa axios per le richieste HTTP

export default {
  components: {
    PanelMenu,
    InputText,
    Badge,
    Card,
    IconField,
    Button,
    Dropdown,
    InputIcon,
    Divider,
    Avatar,
    Menubar,
    Dialog
  },
  directives: {
    ripple: Ripple // Register the ripple directive
  },
  data() {
    return {
      showLogoutDialog: false,
      isSidebarOpen: false, // New data property for sidebar visibility
      menuItems: [
        {
          label: 'Dashboard',
          icon: 'pi pi-fw pi-home',
          to: '/dashboard/home'
        },
        {
          label: 'Calendar',
          icon: 'pi pi-fw pi-calendar',
          to: '/dashboard/calendar'
        },
        {
          label: 'Customers',
          icon: 'pi pi-fw pi-users',
          items: [
            {
              label: 'Customer List',
              icon: 'pi pi-fw pi-list',
              to: '/dashboard/customers'
            },
            {
              label: 'Add Customer',
              icon: 'pi pi-fw pi-plus',
              to: '/dashboard/add-customer'
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
              to: '/dashboard/add-vehicles'
            }
          ]
        },
        {
          label: 'Bookings',
          icon: 'pi pi-fw pi-book',
          items: [
            {
              label: 'Booking List',
              icon: 'pi pi-fw pi-list',
              to: '/dashboard/bookings'
            },
            {
              label: 'Add Booking',
              icon: 'pi pi-fw pi-plus',
              to: '/dashboard/add-booking'
            }
          ]
        },
        {
          label: 'Reports',
          icon: 'pi pi-fw pi-chart-line',
          to: '/dashboard/reports'
        },
      ],
      bottomMenuItems: [
        { label: 'Help', icon: 'pi pi-question-circle', to: '/dashboard/help' },
        { label: 'Settings', icon: 'pi pi-cog', to: '/dashboard/settings' },
        {
          label: 'Log Out',
          icon: 'pi pi-sign-out',
          command: () => {
            this.confirmLogout();
          }
        }
      ],
      notifications: [
        { label: 'Notification 1' },
        { label: 'Notification 2' },
        { label: 'Notification 3' }
      ],
      pageTitle: 'Dashboard',
      navbarItems: [
        { label: 'Bookings', icon: 'pi pi-fw pi-calendar', to: '/' },
        { label: 'New Booking', icon: 'pi pi-fw pi-plus', to: '/about' },
        { label: 'Vehicles', icon: 'pi pi-fw pi-car', to: '/dashboard/vehicles' },

      ]
    };
  },
  watch: {
    $route(to) {
      this.updatePageTitle(to);
    }
  },
  methods: {
    updatePageTitle(route) {
      this.pageTitle = `Dashboard - ${route.meta.title || 'Dashboard'}`;
    },
    confirmLogout() {
      this.showLogoutDialog = true;
    },
    async logout() {
      try {
        await axios.post('/api/logout'); // Chiamata all'API di logout
        this.showLogoutDialog = false;
        this.$router.push('/login'); // Reindirizza alla pagina di login
      } catch (error) {
        console.error('Logout failed:', error);
      }
    },
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    }
  },
  mounted() {
    this.updatePageTitle(this.$route);
  }
};
</script>

<style>
.scrollable-content {
  overflow-y: auto;
  max-height: calc(100vh - 2%); /* Assicura che il contenuto possa scrollare */
  margin-top: 2%;
}

.main-content {
  display: flex;
  flex-direction: column;
  height: 100vh; /* Occupa tutta l'altezza della viewport */
  overflow-y: auto; /* Previene scroll doppio */
}

.main-content .container {
  flex: 1; /* Permette al contenuto di adattarsi */
  display: flex;
  flex-direction: column;
  overflow-y: auto; /* Abilita lo scroll */
  min-height: 0; /* Evita problemi di overflow con flex */
}

.company-name {
  margin-top: 5%;
}

.main-content-bg {
  background-color: rgba(240, 240, 240, 0.925);
}

.p-panelmenu-panel:first-child {
  border-width: 0px !important;
  border-start-start-radius: 0px !important;
}

.p-panelmenu-panel {
  border-width: 0px !important;
  border-start-start-radius: 0px !important;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .sidebar {
      width: 100%;
      margin: 0;
      padding: 1rem;
      display: none; /* Nasconde la sidebar di default */
  }

  .sidebar-open {
      display: block; /* Mostra la sidebar quando è aperta */
  }

  .main-content {
      padding: 1rem;
  }

  .p-panelmenu-panel {
      border-width: 1px !important;
      border-radius: 0px !important;
  }

  .p-panelmenu-panel:first-child {
      border-width: 1px !important;
      border-radius: 0px !important;
  }

  .toggle-sidebar-btn {
      display: block; /* Mostra il pulsante per aprire la sidebar */
      margin: 1rem;
  }
}

@media (max-width: 425px) {
  .sidebar {
      width: 100%;
      margin: 0;
      padding: 0.5rem;
      display: none;
  }

  .sidebar-open {
      display: block;
  }

  .main-content {
      padding: 0.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
      height: 100vh;
  }

  .p-panelmenu-panel {
      border-width: 1px !important;
      border-radius: 0px !important;
  }

  .p-panelmenu-panel:first-child {
      border-width: 1px !important;
      border-radius: 0px !important;
  }

  .text-center h1 {
      font-size: 1.5rem;
  }

  .text-center .font-bold {
      font-size: 1rem;
  }

  .text-center .text-sm {
      font-size: 0.75rem;
  }

  .scrollable-content {
      flex: 1;
      overflow-y: auto;
  }

  .toggle-sidebar-btn {
      display: block;
      margin: 1rem;
  }
}

.p-menubar {
  height: 7%;
  border: 0 !important;
  border-radius: 0 !important;
}

</style>