<template>
  <div>
    <DataTable :value="clients" selectionMode="single" dataKey="id" @row-select="showClientDetails" class="p-datatable-gridlines">
      <Column field="id" header="ID" />
      <Column field="firstName" header="First Name" />
      <Column field="lastName" header="Last Name" />
      <Column field="email" header="Email" />
      <Column field="phone" header="Phone" />
      <Column field="status" header="Status" />
    </DataTable>

    <Dialog v-model:visible="dialogVisible" v-if="selectedClient" header="Client Details" :modal="true" :style="{ width: '50vw' }">
      <TabView>
        <TabPanel header="Client Info">
          <div v-if="selectedClient">
            <div class="input-group-row">
              <div class="input-group-spacing full-width">
                <label for="firstName">First Name</label>
                <InputGroup class="full-width">
                  <InputGroupAddon><i class="pi pi-user"></i></InputGroupAddon>
                  <InputText id="firstName" v-model="selectedClient.firstName" placeholder="First Name" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
                </InputGroup>
              </div>

              <div class="input-group-spacing full-width">
                <label for="lastName">Last Name</label>
                <InputGroup class="full-width">
                  <InputGroupAddon><i class="pi pi-user"></i></InputGroupAddon>
                  <InputText id="lastName" v-model="selectedClient.lastName" placeholder="Last Name" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
                </InputGroup>
              </div>
            </div>

            <div class="input-group-row">
              <div class="input-group-spacing full-width">
                <label for="email">Email</label>
                <InputGroup class="full-width">
                  <InputGroupAddon><i class="pi pi-envelope"></i></InputGroupAddon>
                  <InputText id="email" v-model="selectedClient.email" placeholder="Email" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
                </InputGroup>
              </div>

              <div class="input-group-spacing full-width">
                <label for="phone">Phone</label>
                <InputGroup class="full-width">
                  <InputGroupAddon><i class="pi pi-phone"></i></InputGroupAddon>
                  <InputText id="phone" v-model="selectedClient.phone" placeholder="Phone" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
                </InputGroup>
              </div>
            </div>

            <Button icon="pi pi-pencil" class="p-button-rounded p-button-secondary mr-2" @click="editClient" />
            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="confirmDeleteClient($event, selectedClient)" />
          </div>
        </TabPanel>

        <TabPanel header="Documents">
        <div v-if="selectedClient">
          <div class="input-group-row">
            <div class="input-group-spacing full-width">
              <label for="licenseNumber">License Number</label>
              <InputGroup class="full-width">
                <InputGroupAddon><i class="pi pi-id-card"></i></InputGroupAddon>
                <InputText id="licenseNumber" v-model="selectedClient.licenseNumber" placeholder="License Number" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
              </InputGroup>
            </div>

            <div class="input-group-spacing full-width">
              <label for="nationalID">National ID</label>
              <InputGroup class="full-width">
                <InputGroupAddon><i class="pi pi-id-card"></i></InputGroupAddon>
                <InputText id="nationalID" v-model="selectedClient.nationalID" placeholder="National ID" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
              </InputGroup>
            </div>
          </div>

          <div class="input-group-row">
            <div class="input-group-spacing full-width">
              <label for="passport">Passport</label>
              <InputGroup class="full-width">
                <InputGroupAddon><i class="pi pi-id-card"></i></InputGroupAddon>
                <InputText id="passport" v-model="selectedClient.passport" placeholder="Passport" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
              </InputGroup>
            </div>

            <div class="input-group-spacing full-width">
              <label for="utilityBill">Utility Bill</label>
              <InputGroup class="full-width">
                <InputGroupAddon><i class="pi pi-id-card"></i></InputGroupAddon>
                <InputText id="utilityBill" v-model="selectedClient.utilityBill" placeholder="Utility Bill" class="full-width" :readonly="!isEditing" v-tooltip="!isEditing ? 'Click the edit button to unlock' : ''" />
              </InputGroup>
            </div>
          </div>
        </div>
      </TabPanel>

        <TabPanel header="Bookings">
          <div v-if="selectedClient">
            <DataTable :value="selectedClient.bookings" class="p-datatable-gridlines">
              <Column field="id" header="Booking ID" />
              <Column field="vehicle" header="Vehicle" />
              <Column field="date" header="Date" />
            </DataTable>
          </div>
        </TabPanel>
      </TabView>
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
    InputText
  },
  directives: {
    tooltip: Tooltip
  },
  setup() {
    const clients = ref([
      { id: 1, firstName: 'John', lastName: 'Doe', email: 'john.doe@example.com', phone: '123-456-7890', status: 'Active', licenseNumber: 'D1234567', nationalID: 'N1234567', passport: 'P1234567', utilityBill: 'U1234567', bookings: [{ id: 101, vehicle: 'Toyota Camry', date: '2025-01-15' }, { id: 102, vehicle: 'Honda Accord', date: '2025-02-10' }, { id: 103, vehicle: 'Ford Mustang', date: '2025-03-05' }] },
      { id: 2, firstName: 'Jane', lastName: 'Smith', email: 'jane.smith@example.com', phone: '987-654-3210', status: 'Inactive', licenseNumber: 'S7654321', nationalID: 'N7654321', passport: 'P7654321', utilityBill: 'U7654321', bookings: [{ id: 104, vehicle: 'Chevrolet Malibu', date: '2025-04-20' }, { id: 107, vehicle: 'Ford Focus', date: '2025-05-10' }, { id: 108, vehicle: 'Tesla Model 3', date: '2025-06-15' }] },
      { id: 3, firstName: 'Michael', lastName: 'Johnson', email: 'michael.johnson@example.com', phone: '456-789-1234', status: 'Active', licenseNumber: 'J1234567', nationalID: 'N1234568', passport: 'P1234568', utilityBill: 'U1234568', bookings: [{ id: 105, vehicle: 'Nissan Altima', date: '2025-05-15' }, { id: 109, vehicle: 'Chevrolet Impala', date: '2025-07-20' }, { id: 110, vehicle: 'Hyundai Sonata', date: '2025-08-25' }] },
      { id: 4, firstName: 'Emily', lastName: 'Davis', email: 'emily.davis@example.com', phone: '321-654-9870', status: 'Inactive', licenseNumber: 'D7654321', nationalID: 'N7654322', passport: 'P7654322', utilityBill: 'U7654322', bookings: [{ id: 106, vehicle: 'BMW 3 Series', date: '2025-06-10' }, { id: 111, vehicle: 'Audi A4', date: '2025-09-05' }, { id: 112, vehicle: 'Mercedes-Benz C-Class', date: '2025-10-10' }] }
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
      confirm
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