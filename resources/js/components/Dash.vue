<template>
  <!-- Sidebar -->
  <div
    :class="['sidebar', { 'sidebar-open': isSidebarOpen }]"
    class="w-64 flex flex-col justify-between m-6 rounded-lg mobile-mode"
  >
    <div>
      <div class="font-xl text-center p-4">
        <img src="/logo.png" alt="" />
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

<script setup>
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
import { ref, computed, watch, onMounted, inject } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useI18n } from 'vue-i18n';

// Directive setup
const vRipple = Ripple;

// Composables
const store = useStore();
const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const { t } = useI18n();

// Reactive state
const showLogoutDialog = ref(false);
const isSidebarOpen = ref(false);
const userName = ref('');
const userRole = ref('');
const pageTitle = ref('Dashboard');

// Computed properties
const user = computed(() => store.getters.user);
const userInitial = computed(() => userName.value ? userName.value.charAt(0) : '');

// Menu items
const menuItemsMobile = ref([
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
]);

const menuItems = ref([
  {
    label: 'Dashboard',
    icon: 'pi pi-fw pi-home',
    to: '/dashboard/home'
  },
  {
    label: t('dash.calendar'),
    icon: 'pi pi-fw pi-calendar',
    to: '/dashboard/calendar'
  },
  {
    label: t('dash.customer'),
    icon: 'pi pi-fw pi-users',
    items: [
      {
        label: t('dash.listCustomer'),
        icon: 'pi pi-fw pi-list',
        to: '/dashboard/customers'
      },
      {
        label: t('dash.addCustomer'),
        icon: 'pi pi-fw pi-plus',
        to: '/dashboard/add-customer'
      }
    ]
  },
  {
    label: t('dash.vehicle'),
    icon: 'pi pi-fw pi-car',
    items: [
      {
        label: t('dash.vehicleList'),
        icon: 'pi pi-fw pi-list',
        to: '/dashboard/vehicles'
      },
      {
        label: t('dash.addVehicle'),
        icon: 'pi pi-fw pi-plus',
        to: '/dashboard/add-vehicles'
      }
    ]
  },
  {
    label: t('dash.booking'),
    icon: 'pi pi-fw pi-book',
    items: [
      {
        label: t('dash.bookingList'),
        icon: 'pi pi-fw pi-list',
        to: '/dashboard/bookings'
      },
      {
        label: t('dash.addBooking'),
        icon: 'pi pi-fw pi-plus',
        to: '/dashboard/add-booking'
      }
    ]
  },
  {
    label: t('dash.reports'),
    icon: 'pi pi-fw pi-chart-line',
    to: '/dashboard/reports'
  },
]);

const navbarItems = ref([
  { label: t('dash.addCustomer'), icon: 'pi pi-fw pi-user-plus', url: '/dashboard/add-customer' },
  { label: t('dash.addBooking'), icon: 'pi pi-fw pi-calendar-plus', url: '/dashboard/add-booking' },
  { label: t('dash.addVehicle'), icon: 'pi pi-fw pi-car', url: '/dashboard/add-vehicles' },
]);

// Methods
const fetchUserData = async () => {
  try {
    const response = await axios.post('/user');
    const userData = response.data.user;

    // Set name and role
    userName.value = userData.name;
    userRole.value = userData.roles.length > 0
      ? userData.roles[0].role.charAt(0).toUpperCase() + userData.roles[0].role.slice(1)
      : 'N/A';
  } catch (error) {
    console.error('Error fetching user data:', error);
  }
};

const updatePageTitle = (to) => {
  pageTitle.value = `Dashboard - ${to.meta.title || 'Dashboard'}`;
};

const confirmLogout = () => {
  confirm.require({
    message: t('dash.logOutMessage'),
    header: t('dash.logOutHeader'),
    icon: 'pi pi-exclamation-triangle',
    acceptLabel: t('dash.logOut'),
    rejectLabel: t('common.cancel'),
    acceptClass: 'p-button-danger',
    rejectClass: 'p-button-secondary',
    accept: () => {
      logout();
    },
    reject: () => {
      // Optional: handle rejection
    }
  });
};

const logout = async () => {
  try {
    await axios.post('/logout');
    localStorage.removeItem('authToken');
    showLogoutDialog.value = false;
    router.push('/login');
  } catch (error) {
    console.error('Logout failed:', error);
  }
};

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// Bottom menu items with command function
const bottomMenuItems = ref([
  { label: t('dash.help'), icon: 'pi pi-question-circle', to: '/dashboard/help' },
  { label: t('dash.settings'), icon: 'pi pi-cog', to: '/dashboard/settings' },
  {
    label: t('dash.logOut'),
    icon: 'pi pi-sign-out',
    command: confirmLogout
  }
]);

// Watchers
watch(() => route.path, (to) => {
  updatePageTitle(route);
}, { immediate: true });

// Lifecycle hooks
onMounted(() => {
  updatePageTitle(route);
  fetchUserData();
});
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