<template>
  <!-- Sidebar -->
  <div class="w-64 flex flex-col justify-between m-6 rounded-lg">
    <div>
      <div class="font-xl text-center p-4">
        <h1 class="font-bold text-6xl">Lease</h1>
      </div>
      <Divider />
      <PanelMenu :model="menuItems" class="w-full">
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
    <div class="mb-4">
      <PanelMenu :model="bottomMenuItems" class="w-full">
        <template #item="{ item }">
          <router-link :to="item.to" v-ripple class="flex items-center px-4 py-2 cursor-pointer group" :class="{ 'settings-item': item.label === 'Settings' }">
            <span :class="[item.icon, 'text-primary group-hover:text-inherit']" />
            <span class="ml-2">{{ item.label }}</span>
            <Badge v-if="item.badge" class="ml-auto" :value="item.badge" />
            <span v-if="item.shortcut" class="ml-auto border border-surface rounded bg-emphasis text-muted-color text-xs p-1">{{ item.shortcut }}</span>
          </router-link>
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

  <!-- Main Content -->
  <div class="flex-1 flex flex-col">
    <div class="flex items-center justify-between p-4">
      <div class="text-2xl font-bold">{{ pageTitle }}</div>
      <div class="flex items-center gap-4">
        <span class="p-input-icon-left">
            <IconField>
                <InputIcon class="pi pi-search" />
                <InputText v-model="value1" placeholder="Search" />
            </IconField>
        </span>
        <Button icon="pi pi-bell" severity="info" aria-label="Save" />
      </div>
    </div>
    <Divider />

    <div class="p-4">
        <main class="content scrollable-content">
            <router-view></router-view>
        </main>
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
import Ripple from 'primevue/ripple'; // Import the ripple directive

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
    Avatar
  },
  directives: {
    ripple: Ripple // Register the ripple directive
  },
  data() {
    return {
      menuItems: [
        {
          label: 'Dashboard',
          icon: 'pi pi-fw pi-home',
          to: '/dashboard/home'
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
      ],
      bottomMenuItems: [
        { label: 'Help', icon: 'pi pi-question-circle', to: '/dashboard/help' },
        { label: 'Settings', icon: 'pi pi-cog', to: '/dashboard/settings' }
      ],
      notifications: [
        { label: 'Notification 1' },
        { label: 'Notification 2' },
        { label: 'Notification 3' }
      ],
      pageTitle: 'Dashboard'
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
  max-height: calc(101vh - 200px);
}
</style>