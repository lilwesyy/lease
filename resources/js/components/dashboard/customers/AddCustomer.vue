<template>
  <h1 v-if="!isViewMode" class="font-bold text-3xl">Add Customer</h1>
  <h1 v-else-if="isEditMode" class="font-bold text-3xl">Edit Customer</h1>
  <h1 v-else class="font-bold text-3xl">View Customer</h1>
  <Toast />

  <Breadcrumb :model="breadcrumbItems" class="custom-breadcrumb" />

  <ConfirmDialog />

  <Card class="isviewed">
    <template #content>
      <h1 class="font-bold text-2xl">Personal Data</h1>
      <p class="mb-2 text-gray-500">Fill the customer personal data</p>
      <div class="input-group-grid">
        <div class="input-group-row">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-user"></i>
            </InputGroupAddon>
            <InputText v-model="firstName" placeholder="First Name" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-user"></i>
            </InputGroupAddon>
            <InputText v-model="lastName" placeholder="Last Name" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-calendar"></i>
            </InputGroupAddon>
            <DatePicker v-model="birthDate" placeholder="Birth Date" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-globe"></i>
            </InputGroupAddon>
            <Select v-model="selectedLanguage" :options="languages" optionLabel="label" optionValue="value" placeholder="Select a language" :class="{ 'select-readonly': isViewMode && !isEditMode }"/>
          </InputGroup>
        </div>
        <div class="input-group-row">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-phone"></i>
            </InputGroupAddon>
            <InputText v-model="phone" placeholder="Phone Number" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-envelope"></i>
            </InputGroupAddon>
            <InputText v-model="email" placeholder="Email" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-home"></i>
            </InputGroupAddon>
            <AutoComplete
              v-model="selectedAddress"
              :suggestions="filteredAddresses"
              completeMethod="searchAddress"
              placeholder="Address"
              :readonly="isViewMode && !isEditMode"
              required
            />
          </InputGroup>
        </div>
      </div>

      <h1 class="font-bold text-2xl mt-10">Documents</h1>
      <p class="mb-2 text-gray-500">Fill the customer documents</p>

      <div class="documents-grid">
        <div>
          <p>Driver License</p>
        </div>
        <div></div>

        <div>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-id-card"></i>
            </InputGroupAddon>
            <InputText v-model="driverLicenseNumber" placeholder="Serial Number" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
        </div>
        <div>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-calendar"></i>
            </InputGroupAddon>
            <DatePicker v-model="driverLicenseValidUntil" placeholder="Valid Until" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
        </div>

        <div>
          <p>Identity Card / Passport</p>
        </div>
        <div></div>

        <div>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-id-card"></i>
            </InputGroupAddon>
            <InputText v-model="identityCardNumber" placeholder="Serial Number" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
        </div>
        <div>
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-calendar"></i>
            </InputGroupAddon>
            <DatePicker v-model="identityCardValidUntil" placeholder="Valid Until" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
        </div>
      </div>
      <p class="mt-3 text-gray-500">You can attach customer documents scans</p>
      <Button icon="pi pi-paperclip" label="Attach Documents" :disabled="isViewMode && !isEditMode" severity="contrast"/>

      <h1 class="font-bold text-2xl mt-10">Card</h1>
      <p class="mb-2 text-gray-500">Fill the customer card info</p>

      <div class="card-input-row">
        <div class="card-number">
          <InputGroup>
            <InputGroupAddon>
              <i class="pi pi-credit-card"></i>
            </InputGroupAddon>
            <InputText v-model="cardNumber" type="number" placeholder="Card Number" :readonly="isViewMode && !isEditMode" required/>
          </InputGroup>
        </div>

        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-calendar"></i>
          </InputGroupAddon>
          <InputText v-model="expirationDate" type="text" placeholder="MM/YYYY" :readonly="isViewMode && !isEditMode" required/>
        </InputGroup>

        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-lock"></i>
          </InputGroupAddon>
          <InputText v-model="cvv" type="number" placeholder="CVV2" :readonly="isViewMode && !isEditMode" required/>
        </InputGroup>

        <InputGroup>
          <InputGroupAddon>
            <i class="pi pi-user"></i>
          </InputGroupAddon>
          <InputText v-model="cardHolder" type="text" placeholder="Card Holder" :readonly="isViewMode && !isEditMode" required/>
        </InputGroup>
      </div>

      <p class="mt-3 text-gray-500">You can attach customer credit card scans</p>
      <Button icon="pi pi-paperclip" label="Attach Documents" :disabled="isViewMode && !isEditMode" severity="contrast"/>

      <Button v-if="!isViewMode" @click="createCustomer" label="Create Customer" class="ml-2 mt-4" icon="pi pi-user" />

      <div v-if="isViewMode && !isEditMode">
        <Button @click="enableEditMode" label="Edit" icon="pi pi-pencil" class="p-button-primary mt-4" />
      </div>
      <div v-else-if="isEditMode">
        <Button @click="saveChanges" label="Save" icon="pi pi-check" class="p-button-success mt-4" />
        <Button @click="cancelEditMode" label="Cancel" icon="pi pi-times" class="p-button-secondary mt-4 ml-2" />
        <Button @click="deleteCustomer" label="Delete" icon="pi pi-trash" class="p-button-danger mt-4 ml-2" />
      </div>
    </template>
  </Card>
</template>

<script setup>
import { ref, computed, watch, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import AutoComplete from 'primevue/autocomplete';
import Button from 'primevue/button';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import DatePicker from 'primevue/datepicker';
import ConfirmDialog from 'primevue/confirmdialog';
import Toast from 'primevue/toast';
import axios from 'axios';

const props = defineProps({
  isViewMode: {
    type: Boolean,
    default: false
  },
  client: {
    type: Object,
    required: false,
    default: () => ({})
  }
});

const emit = defineEmits(['close']);
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

// Reactive state
const isEditMode = ref(false);
const firstName = ref('');
const lastName = ref('');
const birthDate = ref(null);
const selectedLanguage = ref(null);
const phone = ref('');
const email = ref('');
const selectedAddress = ref(null);
const filteredAddresses = ref([]);
const driverLicenseNumber = ref('');
const driverLicenseValidUntil = ref(null);
const identityCardNumber = ref('');
const identityCardValidUntil = ref(null);
const cardNumber = ref('');
const expirationDate = ref('');
const cvv = ref('');
const cardHolder = ref('');
const originalData = ref({});

// Non-reactive data
const languages = [
  { label: 'Italian', value: 'it' },
  { label: 'English', value: 'en' },
  { label: 'Romanian', value: 'ro' }
];

// Computed
const breadcrumbItems = computed(() => [
  { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
  {
    label: props.isViewMode ? (isEditMode.value ? 'Edit Customer' : 'View Customer') : 'Add Customer',
    url: props.isViewMode ? `/dashboard/customers/${props.client?.id || ''}` : '/dashboard/add-customer'
  }
]);

// Watchers
watch(() => props.client, (newClient) => {
  if (newClient && Object.keys(newClient).length > 0) {
    firstName.value = newClient.firstName || '';
    lastName.value = newClient.lastName || '';
    birthDate.value = newClient.birthDate || null;
    selectedLanguage.value = newClient.language || null;
    phone.value = newClient.phone || '';
    email.value = newClient.email || '';
    selectedAddress.value = newClient.address || '';
    driverLicenseNumber.value = newClient.licenseNumber || '';
    driverLicenseValidUntil.value = newClient.licenseValidUntil || null;
    identityCardNumber.value = newClient.nationalID || '';
    identityCardValidUntil.value = newClient.identityCardValidUntil || null;
    cardNumber.value = newClient.cardNumber || '';
    expirationDate.value = newClient.expirationDate || '';
    cvv.value = newClient.cvv || '';
    cardHolder.value = newClient.cardHolder || '';
  }
}, { immediate: true });

// Methods
const deleteCustomer = async () => {
  confirm.require({
    message: 'Are you sure you want to delete this customer?',
    header: 'Confirmation',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    rejectClass: 'p-button-secondary',
    accept: async () => {
      try {
        await axios.delete(`/customer/delete/${props.client.id}`);
        router.push('/dashboard/customers');
        toast.add({ severity: 'success', summary: 'Success', detail: 'Customer deleted successfully', life: 3000 });
        emit('close');
      } catch (error) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete customer', life: 3000 });
      }
    },
  });
};


const createCustomer = async () => {
  try {
    await axios.post('/customer/create', {
      firstName: firstName.value,
      lastName: lastName.value,
      birthDate: birthDate.value,
      language: selectedLanguage.value,
      phone: phone.value,
      email: email.value,
      address: selectedAddress.value,
      licenseNumber: driverLicenseNumber.value,
      licenseValidUntil: driverLicenseValidUntil.value,
      nationalID: identityCardNumber.value,
      identityCardValidUntil: identityCardValidUntil.value,
      cardNumber: cardNumber.value,
      expirationDate: expirationDate.value,
      cvv: cvv.value,
      cardHolder: cardHolder.value
    });
    router.push('/dashboard/customers');
    toast.add({ severity: 'success', summary: 'Success', detail: 'Customer created successfully', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create customer', life: 3000 });
  }
};


const editCustomer = async () => {
  try {
    await axios.put(`/customer/edit/${props.client.id}`, {
      firstName: firstName.value,
      lastName: lastName.value,
      birthDate: birthDate.value,
      language: selectedLanguage.value,
      phone: phone.value,
      email: email.value,
      address: selectedAddress.value,
      licenseNumber: driverLicenseNumber.value,
      licenseValidUntil: driverLicenseValidUntil.value,
      nationalID: identityCardNumber.value,
      identityCardValidUntil: identityCardValidUntil.value,
      cardNumber: cardNumber.value,
      expirationDate: expirationDate.value,
      cvv: cvv.value,
      cardHolder: cardHolder.value
    });
    router.push('/dashboard/customers');
    toast.add({ severity: 'success', summary: 'Success', detail: 'Customer updated successfully', life: 3000 });
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update customer', life: 3000 });
  }
};

const searchAddress = (event) => {
  const service = new google.maps.places.AutocompleteService();
  service.getPlacePredictions({ input: event.query }, (predictions, status) => {
    if (status !== google.maps.places.PlacesServiceStatus.OK) {
      console.error('Error fetching address predictions:', status);
      return;
    }
    filteredAddresses.value = predictions.map(prediction => ({
      label: prediction.description,
      value: prediction.description
    }));
  });
};

const enableEditMode = () => {
  isEditMode.value = true;
  originalData.value = {
    firstName: firstName.value,
    lastName: lastName.value,
    birthDate: birthDate.value,
    selectedLanguage: selectedLanguage.value,
    phone: phone.value,
    email: email.value,
    selectedAddress: selectedAddress.value,
    driverLicenseNumber: driverLicenseNumber.value,
    driverLicenseValidUntil: driverLicenseValidUntil.value,
    identityCardNumber: identityCardNumber.value,
    identityCardValidUntil: identityCardValidUntil.value,
    cardNumber: cardNumber.value,
    expirationDate: expirationDate.value,
    cvv: cvv.value,
    cardHolder: cardHolder.value,
    isEditMode: false
  };
};

const saveChanges = () => {
  isEditMode.value = false;
  editCustomer();
};

const cancelEditMode = () => {
  // Restore original values from backup
  firstName.value = originalData.value.firstName;
  lastName.value = originalData.value.lastName;
  birthDate.value = originalData.value.birthDate;
  selectedLanguage.value = originalData.value.selectedLanguage;
  phone.value = originalData.value.phone;
  email.value = originalData.value.email;
  selectedAddress.value = originalData.value.selectedAddress;
  driverLicenseNumber.value = originalData.value.driverLicenseNumber;
  driverLicenseValidUntil.value = originalData.value.driverLicenseValidUntil;
  identityCardNumber.value = originalData.value.identityCardNumber;
  identityCardValidUntil.value = originalData.value.identityCardValidUntil;
  cardNumber.value = originalData.value.cardNumber;
  expirationDate.value = originalData.value.expirationDate;
  cvv.value = originalData.value.cvv;
  cardHolder.value = originalData.value.cardHolder;
  isEditMode.value = false;
};
</script>

<style scoped>
.input-group-grid {
  display: grid;
  gap: 1rem;
}

.input-group-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.input-group-row:nth-child(2) {
  grid-template-columns: repeat(3, 1fr);
}

.documents-grid {
  display: grid;
  grid-template-columns: 2fr 1fr; /* Colonna sinistra più larga */
  gap: 1rem;
  align-items: center;
}

.documents-grid > div {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.card-input-row {
  display: flex;
  gap: 1rem; /* Aggiungi spazio tra gli input */
  flex-wrap: wrap; /* Consente di andare a capo se necessario */
}

.card-input-row > div {
  flex: 1;
  min-width: 150px; /* Imposta una larghezza minima per evitare che diventino troppo piccoli */
}

.card-number {
  flex: 3; /* Rende il campo Card Number più largo rispetto agli altri */
}
</style>