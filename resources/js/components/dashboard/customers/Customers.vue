<template>
  <h1 class="font-bold text-3xl">Customer List</h1>
  <Breadcrumb :model="items" class="custom-breadcrumb" />
  <div>
    <Card>
      <template #content>
        <header class="p-d-flex p-ai-center p-gap-2 inline-header">
          <Button :label="$t('customers.addButton')" icon="pi pi-plus" @click="$router.push('/dashboard/add-customer')" />
          <IconField class="p-d-flex p-ai-center">
            <InputIcon class="pi pi-search" />
            <InputText v-model="value1" :placeholder="$t('customers.searchPlaceholder')" />
          </IconField>
        </header>

        <div v-if="loading" class="flex justify-center p-4">
          <ProgressSpinner />
        </div>
        <div v-else-if="error" class="p-4 text-red-500 text-center">
          {{ error }}
        </div>
        <div v-else-if="clients.length > 0">
          <DataTable
            :value="filteredClients"
            selectionMode="single"
            dataKey="id"
            @row-select="showClientDetails"
            class="p-datatable-no-gridlines"
            :paginator="true"
            :rows="10"
            :rowsPerPageOptions="[5, 10, 20, 50]"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          >
            <Column :header="$t('customers.columns.name')">
              <template #body="slotProps">
                <div>
                  {{ slotProps.data.firstName }} {{ slotProps.data.lastName }}
                  <div class="text-sm text-gray-500">{{ slotProps.data.email }}</div>
                </div>
              </template>
            </Column>
            <Column field="phone" :header="$t('customers.columns.phone')" />
            <Column field="address" :header="$t('customers.columns.address')" />
            <Column :header="$t('customers.columns.documents')">
              <template #body="slotProps">
                <Checkbox :model-value="slotProps.data.hasDocuments" :binary="true" disabled />
              </template>
            </Column>
            <Column :header="$t('customers.columns.privacy')">
              <template #body="slotProps">
                <Badge 
                  :value="slotProps.data.privacy ? $t('customers.privacy.signed') : $t('customers.privacy.notSigned')" 
                  :severity="slotProps.data.privacy ? 'success' : 'danger'" 
                  class="p-mr-2" 
                />
              </template>
            </Column>
          </DataTable>
        </div>
        <div v-else>
          <p class="text-center text-gray-500 mt-5">No customers found. Please add a customer.</p>
        </div>
      </template>
    </Card>

    <Dialog v-model:visible="dialogVisible" v-if="selectedClient" :header="$t('customers.clientDetails')" :modal="true" :style="{ width: '70vw' }">
      <AddCustomer :client="selectedClient" :isViewMode="true" @close="handleClose" />
    </Dialog>

  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import { useConfirm } from "primevue/useconfirm";
import InputText from 'primevue/inputtext';
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import ProgressSpinner from 'primevue/progressspinner';
import AddCustomer from './AddCustomer.vue';
import Checkbox from 'primevue/checkbox';
import Badge from 'primevue/badge';
import { useI18n } from 'vue-i18n'; 

const clients = ref([]);
const selectedClient = ref({});
const dialogVisible = ref(false);
const isEditing = ref(false);
const metaKey = ref(true);
const value1 = ref('');
const loading = ref(false);
const error = ref(null);
const { t } = useI18n();
const confirm = useConfirm();

// Filtro clienti in base alla ricerca
const filteredClients = computed(() => {
  if (!value1.value.trim()) return clients.value;

  const searchTerm = value1.value.toLowerCase();
  return clients.value.filter(client =>
    (client.firstName?.toLowerCase() || '').includes(searchTerm) ||
    (client.lastName?.toLowerCase() || '').includes(searchTerm) ||
    (client.email?.toLowerCase() || '').includes(searchTerm) ||
    (client.phone?.toLowerCase() || '').includes(searchTerm) ||
    (client.address?.toLowerCase() || '').includes(searchTerm)
  );
});

const fetchClients = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.post('/customer');
    clients.value = response.data.map(client => ({
      ...client,
      hasDocuments: (client.identity_documents?.length || 0) > 0 || (client.card_documents?.length || 0) > 0,
      privacy: client.privacy // Booleano true o false
    }));
  } catch (err) {
    console.error('Error fetching clients:', err);
    error.value = 'Failed to load customers. Please try again later.';
  } finally {
    loading.value = false;
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
  { label: t('customers.title'), url: '/dashboard/customers' }
];

onMounted(() => {
  fetchClients();
});
</script>

<script>
// Define component name for DevTools
export default {
  name: 'Clients',
}
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

.inline-header {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.text-green-500 {
  color: #22c55e; /* Verde per "signed" */
}

.text-red-500 {
  color: #ef4444; /* Rosso per "notsigned" */
}
</style>