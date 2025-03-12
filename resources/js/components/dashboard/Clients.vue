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
    </template>
    </Card>

    <Dialog v-model:visible="dialogVisible" v-if="selectedClient" header="Client Details" :modal="true" :style="{ width: '50vw' }">
        <AddCustomer :client="selectedClient" :isViewMode="true" />
    </Dialog>

    <ConfirmPopup />
  </div>
</template>

<script>
import { ref, watch } from 'vue';
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
    const clients = ref([
    {
        id: 1,
        firstName: 'John',
        lastName: 'Doe',
        language: 'English',
        address: 'Strada Altessano, 96/B, 10151 Torino TO',
        email: 'john.doe@example.com',
        phone: '123-456-7890',
        status: 'Active',
        licenseNumber: 'D1234567',
        licenseValidUntil: '24/10/2032',
        nationalID: 'N1234567',
        identityCardValidUntil: '19/98/2054',
        passport: 'P1234567',
        utilityBill: 'U1234567',
        birthDate: '1990-05-12',
        cardNumber: '1234567890123456',
        expirationDate: '12/25',
        cvv: '123',
        cardHolder: 'John Doe',
        language: 'en',
    },
    {
        id: 2,
        firstName: 'Jane',
        lastName: 'Smith',
        language: 'English',
        address: 'Via Bologna, 32, 10152 Torino TO',
        email: 'jane.smith@example.com',
        phone: '987-654-3210',
        status: 'Inactive',
        licenseNumber: 'S7654321',
        licenseValidUntil: '24/10/2032',
        nationalID: 'N7654321',
        identityCardValidUntil: '19/98/2054',
        passport: 'P7654321',
        utilityBill: 'U7654321',
        birthDate: '1985-02-20',
        cardNumber: '2345678901234567',
        expirationDate: '11/24',
        cvv: '234',
        cardHolder: 'Jane Smith',
        language: 'it',
    },
    {
        id: 3,
        firstName: 'Michael',
        lastName: 'Johnson',
        language: 'English',
        address: 'Corso Tortona, 4/A, 10153 Torino TO',
        email: 'michael.johnson@example.com',
        phone: '456-789-1234',
        status: 'Active',
        licenseNumber: 'J1234567',
        licenseValidUntil: '24/10/2032',
        nationalID: 'N1234568',
        identityCardValidUntil: '19/98/2054',
        passport: 'P1234568',
        utilityBill: 'U1234568',
        birthDate: '1992-08-10',
        cardNumber: '3456789012345678',
        expirationDate: '10/24',
        cvv: '345',
        cardHolder: 'Michael Johnson',
        language: 'en',
    },
    {
        id: 4,
        firstName: 'Emily',
        lastName: 'Davis',
        language: 'English',
        address: 'Str. del Pascolo, 61, 10156 Torino TO',
        email: 'emily.davis@example.com',
        phone: '321-654-9870',
        status: 'Inactive',
        licenseNumber: 'D7654321',
        licenseValidUntil: '24/10/2032',
        nationalID: 'N7654322',
        identityCardValidUntil: '19/98/2054',
        passport: 'P7654322',
        utilityBill: 'U7654322',
        birthDate: '1988-03-25',
        cardNumber: '4567 8901 2345 6789',
        expirationDate: '09/23',
        cvv: '456',
        cardHolder: 'Emily Davis',
        language: 'ro',
    }
    ]);


    const dialogVisible = ref(false);
    const selectedClient = ref(null);
    const isEditing = ref(false);
    const metaKey = ref(true);

    // **IMPORTANTE: Inizializza useConfirm() all'interno di setup**
    const confirm = useConfirm();

    const showClientDetails = (event) => {
      selectedClient.value = event.data;
      dialogVisible.value = true;
      isEditing.value = false; // Lock inputs when dialog is opened
    };

    const editClient = () => {
      isEditing.value = true; // Unlock inputs when edit button is pressed
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
      dialogVisible.value = false; // Chiudi il modale
      console.log('Client deleted:', client);
    };

    watch(dialogVisible, (newVal) => {
      if (!newVal) {
        selectedClient.value = null;
      }
    });

    const items = [
      { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
      { label: 'Customer List', url: '/dashboard/clients' }
    ];

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
      items
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
