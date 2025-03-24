<template>
    <!-- Sidebar -->
    <div
      :class="['sidebar', { 'sidebar-open': isSidebarOpen }]"
      class="w-64 flex flex-col justify-between m-6 rounded-lg mobile-mode"
    >
      <div>
        <div class="font-xl text-center p-4">
          <!-- <h1 class="font-bold text-4xl">Lease</h1> -->
          <img src="/logo.png" alt="" />
          <!-- <h2 class="text-xl text-gray-500 company-name">Rental Company Name</h2> -->
        </div>
        <Divider />
        <PanelMenu :model="menuItems" class="w-full">
          <template #item="{ item }">
            <router-link
              v-if="item.to"
              v-slot="{ href, navigate }"
              :to="item.to"
              custom
            >
              <a
                v-ripple
                class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                :href="href"
                @click="navigate"
              >
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
                <span
                  v-if="item.items"
                  class="pi pi-angle-down text-primary ml-auto"
                />
              </a>
            </router-link>
            <a
              v-else
              v-ripple
              class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
              :href="item.url"
              :target="item.target"
            >
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
              <span
                v-if="item.items"
                class="pi pi-angle-down text-primary ml-auto"
              />
            </a>
          </template>
        </PanelMenu>
      </div>
      <div class="mb-4">
        <PanelMenu :model="bottomMenuItems" class="w-full">
          <template #item="{ item }">
            <router-link
              v-if="item.to"
              v-slot="{ href, navigate }"
              :to="item.to"
              custom
            >
              <a
                v-ripple
                class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
                :href="href"
                @click="navigate"
              >
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
                <span
                  v-if="item.items"
                  class="pi pi-angle-down text-primary ml-auto"
                />
              </a>
            </router-link>
            <a
              v-else
              v-ripple
              class="flex items-center cursor-pointer text-surface-700 dark:text-surface-0 px-4 py-2"
              :href="item.url"
              :target="item.target"
            >
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
              <span
                v-if="item.items"
                class="pi pi-angle-down text-primary ml-auto"
              />
            </a>
          </template>
        </PanelMenu>
        <Divider />
        <div class="flex items-center justify-center">
          <Avatar :label="userInitial" class="mr-2" size="large" shape="circle" />
          <div class="text-center">
            <div class="font-bold">{{ userName }}</div>
            <div class="text-sm text-gray-500">{{ userRole }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toggle Button for Mobile -->

    <!-- <Dialog header="Confirm Logout" v-model:visible="showLogoutDialog" modal>
      <span>Are you sure you want to log out?</span>
      <template #footer>
        <div class="w-full flex justify-center">
          <Button
            label="Yes"
            icon="pi pi-check"
            severity="danger"
            class="w-full text-lg py-3"
            @click="logout"
          />
        </div>
      </template>
    </Dialog> -->

    <ConfirmDialog></ConfirmDialog>

    <!-- Main Content -->
    <div class="main-content flex-1 flex flex-col main-content-bg">
      <Menubar :model="menuItemsMobile" class="menubar-desktop-mode"></Menubar>
      <Menubar :model="navbarItems" class="mobile-mode"></Menubar>

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
  import axios from 'axios';
  import ConfirmDialog from 'primevue/confirmdialog';
  import { mapGetters } from 'vuex';

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
      Dialog,
      ConfirmDialog
    },
    directives: {
      ripple: Ripple // Register the ripple directive
    },
    data() {
      return {
        showLogoutDialog: false,
        isSidebarOpen: false,
        userName: '',
        userRole: '',
        menuItemsMobile: [
          {
            label: 'Dashboard',
            icon: 'pi pi-fw pi-home',
            url: '/dashboard/home'
          },
          {
            label: 'Calendar',
            icon: 'pi pi-fw pi-calendar',
            url: '/dashboard/calendar'
          },
          {
            label: 'Customers',
            icon: 'pi pi-fw pi-users',
            items: [
              {
                label: 'Customer List',
                icon: 'pi pi-fw pi-list',
                url: '/dashboard/customers'
              },
              {
                label: 'Add Customer',
                icon: 'pi pi-fw pi-plus',
                url: '/dashboard/add-customer'
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
                url: '/dashboard/vehicles'
              },
              {
                label: 'Add Vehicle',
                icon: 'pi pi-fw pi-plus',
                url: '/dashboard/add-vehicles'
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
                url: '/dashboard/bookings'
              },
              {
                label: 'Add Booking',
                icon: 'pi pi-fw pi-plus',
                url: '/dashboard/add-booking'
              }
            ]
          },
          {
            label: 'Reports',
            icon: 'pi pi-fw pi-chart-line',
            url: '/dashboard/reports'
          },
        ],
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
          { label: 'Add Customer', icon: 'pi pi-fw pi-user-plus', url: '/dashboard/add-customer' },
          { label: 'Add Booking', icon: 'pi pi-fw pi-calendar-plus', url: '/dashboard/add-booking' },
          { label: 'Add Vehicle', icon: 'pi pi-fw pi-car', url: '/dashboard/add-vehicles' },

        ]
        
      };
    },
    computed: {
      ...mapGetters(['user']),
      userInitial() {
        return this.userName ? this.userName.charAt(0) : '';
      }
    },
    watch: {
      $route(to) {
        this.updatePageTitle(to);
      }
    },
    methods: {
        async fetchUserData() {
            try {
    const response = await axios.post('/user'); // Endpoint per ottenere i dati dell'utente
    const userData = response.data.user;

    // Imposta il nome e il ruolo
    this.userName = userData.name;
    this.userRole = userData.roles.length > 0
      ? userData.roles[0].role.charAt(0).toUpperCase() + userData.roles[0].role.slice(1)
      : 'N/A'; // Prendi il primo ruolo e capitalizza la prima lettera
  } catch (error) {
    console.error('Errore nel recupero dei dati utente:', error);
  }
    },

      updatePageTitle(route) {
        this.pageTitle = `Dashboard - ${route.meta.title || 'Dashboard'}`;
      },
      confirmLogout() {
        this.$confirm.require({
            message: 'Are you sure you want to log out?',
            header: 'Confirm Logout',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            rejectClass: 'p-button-secondary',
            accept: () => {
            this.logout();
            },
            reject: () => {
            // Optional: handle rejection
            }
        });
     },
      async logout() {
        try {
          await axios.post('/logout');
          localStorage.removeItem('authToken');
          this.showLogoutDialog = false;
          this.$router.push('/login');
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
      this.fetchUserData();
    //   this.userName = localStorage.getItem('name'); // Recupera il nome dell'utente da localStorage
    }
  };
  </script>

  <style>
  .p-panelmenu-panel {
    border-width: 0px !important;
  }
  .p-menubar {
    border: none !important;
  }
  .main-content-bg {
    background-color: #f0f0f08f;
  }

  .scrollable-content {
    overflow-y: auto;
    max-height: calc(100vh - 2%); /* Assicura che il contenuto possa scrollare */
    margin-top: 2%;
  }

  .main-content {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Occupa tutta l'altezza della viewport */
    overflow-y: auto; /* Modificato da hidden ad auto per abilitare lo scroll */
  }

  .main-content .container {
    flex: 1; /* Permette al contenuto di adattarsi */
    display: flex;
    flex-direction: column;
    overflow-y: auto; /* Abilita lo scroll */
    min-height: 0; /* Evita problemi di overflow con flex */
  }

  @media (max-width: 500px) {
  .mobile-mode {
    display: none !important;
  }
}
  @media (min-width: 500px) {
  .menubar-desktop-mode {
    display: none !important;
  }
  }
  

  </style>
