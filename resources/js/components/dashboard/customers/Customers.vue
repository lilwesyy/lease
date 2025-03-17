<template>
    <h1 class="font-bold text-3xl">Customer List</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />
    <div>
      <Card>
        <template #content>
          <header class="p-d-flex p-ai-center p-gap-2 inline-header">
            <Button label="Add Customer" icon="pi pi-plus" @click="$router.push('/dashboard/add-customer')" />
            <IconField class="p-d-flex p-ai-center">
              <InputIcon class="pi pi-search" />
              <InputText v-model="value1" placeholder="Search" />
            </IconField>
          </header>

          <div v-if="clients.length > 0">
            <DataTable :value="clients" selectionMode="single" dataKey="id" @row-select="showClientDetails" class="p-datatable-no-gridlines">
              <Column header="Name">
                <template #body="slotProps">
                  <div>
                    {{ slotProps.data.firstName }} {{ slotProps.data.lastName }}
                    <div class="text-sm text-gray-500">{{ slotProps.data.email }}</div>
                  </div>
                </template>
              </Column>
              <Column field="phone" header="Phone" />
              <Column field="address" header="Address" />
              <Column header="Documents">
                <template #body="">
                  <i class="pi pi-file"></i>
                </template>
              </Column>
            </DataTable>
          </div>
          <div v-else>
            <p class="text-center text-gray-500 mt-5">No customers found. Please add a customer.</p>
          </div>
        </template>
      </Card>

      <Dialog v-model:visible="dialogVisible" v-if="selectedClient" header="Client Details" :modal="true" :style="{ width: '50vw' }">
        <AddCustomer :client="selectedClient" :isViewMode="true" @close="handleClose" />
      </Dialog>

      <ConfirmPopup />
    </div>
  </template>

  <script>
  import { ref, watch, onMounted } from 'vue';
  import axios from 'axios';
  import DataTable from 'primevue/datatable';
  import Column from 'primevue/column';
  import Dialog from 'primevue/dialog';
  import Button from 'primevue/button';
  import TabView from 'primevue/tabview';
  import TabPanel from 'primevue/tabpanel';
  import InputGroup from 'primevue/inputgroup';
  import InputGroupAddon from 'primevue/inputgroupaddon';
  import ConfirmPopup from 'primevue/confirmpopup';
  import { useConfirm } from "primevue/useconfirm";
  import InputText from 'primevue/inputtext';
  import Tooltip from 'primevue/tooltip';
  import Breadcrumb from 'primevue/breadcrumb';
  import Card from 'primevue/card';
  import InputIcon from 'primevue/inputicon';
  import IconField from 'primevue/iconfield';
  import AddCustomer from './AddCustomer.vue';

  export default {
    name: 'Clients',
    components: {
      DataTable,
      Column,
      Dialog,
      Button,
      TabView,
      TabPanel,
      InputGroup,
      InputGroupAddon,
      ConfirmPopup,
      InputText,
      Breadcrumb,
      Card,
      InputIcon,
      IconField,
      AddCustomer
    },
    directives: {
      tooltip: Tooltip
    },
    setup() {
      const clients = ref([]);
      const selectedClient = ref({});
      const dialogVisible = ref(false);
      const isEditing = ref(false);
      const metaKey = ref(true);
      const value1 = ref('');

      const confirm = useConfirm();

      const fetchClients = async () => {
        try {
          const response = await axios.post('/customer');
          clients.value = response.data;
        } catch (error) {
          console.error('Error fetching clients:', error);
        }
      };

      const showClientDetails = (event) => {
        selectedClient.value = event.data || {};
        dialogVisible.value = true;
        isEditing.value = false;
      };

      const editClient = () => {
        isEditing.value = true;
      };

      const confirmDeleteClient = (event, client) => {
        confirm.require({
          target: event.currentTarget,
          message: 'Are you sure you want to delete this client?',
          header: 'Confirmation',
          icon: 'pi pi-exclamation-triangle',
          acceptLabel: 'Delete',
          acceptClass: 'p-button-danger',
          rejectLabel: 'Cancel',
          rejectClass: 'p-button-secondary',
          accept: () => deleteClient(client),
          reject: () => console.log('Delete cancelled')
        });
      };

      const deleteClient = (client) => {
        clients.value = clients.value.filter(c => c.id !== client.id);
        dialogVisible.value = false;
        console.log('Client deleted:', client);
      };

      const handleClose = () => {
        dialogVisible.value = false;
        fetchClients();
      };

      watch(dialogVisible, (newVal) => {
        if (!newVal) {
          selectedClient.value = null;
          fetchClients();
        }
      });

      const items = [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Customer List', url: '/dashboard/clients' }
      ];

      onMounted(() => {
        fetchClients();
      });

      return {
        clients,
        dialogVisible,
        selectedClient,
        showClientDetails,
        editClient,
        confirmDeleteClient,
        deleteClient,
        isEditing,
        metaKey,
        confirm,
        items,
        value1,
        handleClose
      };
    }
  };
  </script>

  <style scoped>
  .input-group-spacing {
    margin-bottom: 1rem;
  }

  .input-group-row {
    display: flex;
    gap: 1rem;
  }

  label {
    display: block;
    margin-bottom: 0.5rem;
  }

  .full-width {
    width: 100%;
  }
  </style>
