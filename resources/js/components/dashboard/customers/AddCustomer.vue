<template>
  <h1 v-if="!isViewMode" class="font-bold text-3xl">Add Customer</h1>
  <h1 v-else-if="isEditMode" class="font-bold text-3xl">Edit Customer</h1>
  <h1 v-else class="font-bold text-3xl">View Customer</h1>

  <Breadcrumb :model="breadcrumbItems" class="custom-breadcrumb" />

  <Dialog v-model:visible="documentUploadDialogVisible" header="Upload Documents" :style="{width: '50vw'}" 
          :modal="true" :closable="true" :closeOnEscape="true" :dismissableMask="true">
    <FileUpload 
      ref="documentUploader"
      name="documents"
      :customUpload="true"
      @uploader="uploadDocumentsWithAxios" 
      :multiple="true"
      accept="image/*,application/pdf"
      :maxFileSize="10000000"
      chooseLabel="Select Files"
      uploadLabel="Upload"
      cancelLabel="Cancel"
      :auto="false">
      <template #empty>
        <p>Drag and drop files here to upload.</p>
      </template>
    </FileUpload>
    <template #footer>
      <Button label="Close" icon="pi pi-times" @click="documentUploadDialogVisible = false" class="p-button-secondary" />
      <Button label="Upload" icon="pi pi-upload" @click="triggerDocumentUpload" class="p-button-primary" />
    </template>
  </Dialog>

  <Dialog v-model:visible="cardUploadDialogVisible" header="Upload Card Documents" :style="{width: '50vw'}" 
          :modal="true" :closable="true" :closeOnEscape="true" :dismissableMask="true">
          <FileUpload 
  ref="cardUploader"
  name="cardDocuments"
  :customUpload="true"
  @uploader="uploadCardDocumentsWithAxios" 
  :multiple="true"
  accept="image/*,application/pdf"
  :maxFileSize="10000000"
  chooseLabel="Select Files"
  uploadLabel="Upload"
  cancelLabel="Cancel"
  :auto="false">
  <template #empty>
    <p>Drag and drop card scans here to upload.</p>
  </template>
</FileUpload>
    <template #footer>
      <Button label="Close" icon="pi pi-times" @click="cardUploadDialogVisible = false" class="p-button-secondary" />
      <Button label="Upload" icon="pi pi-upload" @click="uploadCardDocuments" class="p-button-primary" />
    </template>
  </Dialog>

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
      <Button icon="pi pi-paperclip" label="Attach Documents" :disabled="isViewMode && !isEditMode" 
      severity="contrast" @click="showDocumentUploadDialog"/>

      <div v-if="documentFiles.length > 0" class="mt-4">
        <h2 class="font-bold text-lg">Attached Documents</h2>
        <div class="document-list">
          <div v-for="doc in documentFiles" :key="doc.id" class="document-item">
            <div class="document-info">
              <i class="pi pi-file mr-2"></i>
              <span>{{ doc.filename || doc.name }}</span>
            </div>
            <div class="document-actions">
              <Button icon="pi pi-eye" @click="previewDocument(doc)" class="p-button-text p-button-sm" tooltip="View" />
              <Button v-if="isEditMode" icon="pi pi-trash" @click.stop="deleteDocument(doc.id, 'identity')" class="p-button-text p-button-danger p-button-sm" tooltip="Delete" />
            </div>
          </div>
        </div>
      </div>

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
      <Button icon="pi pi-paperclip" label="Attach Documents" :disabled="isViewMode && !isEditMode" 
      severity="contrast" @click="showCardUploadDialog"/>

      <div v-if="cardFiles.length > 0" class="mt-4">
        <h2 class="font-bold text-lg">Attached Card Documents</h2>
        <div class="document-list">
          <div v-for="doc in cardFiles" :key="doc.id" class="document-item">
            <div class="document-info">
              <i class="pi pi-file mr-2"></i>
              <span>{{ doc.filename || doc.name }}</span>
            </div>
            <div class="document-actions">
              <Button icon="pi pi-eye" @click="previewDocument(doc)" class="p-button-text p-button-sm" tooltip="View" />
              <Button v-if="isEditMode" icon="pi pi-trash" @click.stop="deleteDocument(doc.id, 'card')" class="p-button-text p-button-danger p-button-sm" tooltip="Delete" />
            </div>
          </div>
        </div>
      </div>

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

  <Dialog v-model:visible="documentPreviewVisible" :header="previewingDocument.filename || 'Document Preview'" :style="{width: '50vw'}" 
        :modal="true" :closable="true" :closeOnEscape="true" :dismissableMask="true">
  <div class="document-preview-container">
    <!-- Loading indicator -->
    <div v-if="!previewingDocument.url" class="flex flex-column align-items-center py-4">
      <i class="pi pi-spin pi-spinner text-3xl mb-2"></i>
      <p>Loading document...</p>
    </div>
    
    <!-- PDF viewer -->
    <iframe v-else-if="previewingDocument.mime_type && previewingDocument.mime_type.includes('pdf')" 
            :src="previewingDocument.url" width="100%" height="500px" frameborder="0"></iframe>
    
    <!-- Image viewer with improved JPEG/JPG handling -->
    <div v-else-if="isImageFile(previewingDocument)" class="flex justify-content-center w-full">
      <img :src="previewingDocument.url" class="max-w-full max-h-[500px] object-contain" 
      @error="handleImageError" />
    </div>
    
    <!-- Fallback for unsupported types -->
    <div v-else class="text-center py-8">
      <i class="pi pi-file text-5xl mb-3 text-primary"></i>
      <p>This document type cannot be previewed directly.</p>
      <Button icon="pi pi-download" label="Download" @click="downloadDocument(previewingDocument)" class="mt-4" />
    </div>
  </div>
</Dialog>
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
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
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
const documentUploadDialogVisible = ref(false);
const cardUploadDialogVisible = ref(false);
const documentUploader = ref(null);
const cardUploader = ref(null);
const documentFiles = ref([]);
const cardFiles = ref([]);

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
const documentPreviewVisible = ref(false);
const previewingDocument = ref({});



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

const isImageFile = (document) => {
  if (!document) return false;

  // Controlla il tipo MIME
  if (document.type && document.type.includes('image')) {
    return true;
  }

  // Controlla l'estensione del file
  const url = document.url || '';
  return /\.(jpg|jpeg|png|gif|bmp|webp)$/i.test(url);
};

const handleImageError = (event) => {
  event.target.src = '/images/document-placeholder.png'; // Percorso dell'immagine di fallback
  event.target.alt = 'Anteprima non disponibile';
  toast.add({
    severity: 'warn',
    summary: 'Errore',
    detail: 'Impossibile caricare l\'anteprima del documento.',
    life: 3000,
  });
};


// New methods for document upload
const showDocumentUploadDialog = () => {
  documentUploadDialogVisible.value = true;
};

const showCardUploadDialog = () => {
  cardUploadDialogVisible.value = true;
};

const uploadDocuments = () => {
  if (documentUploader.value) {
    documentUploader.value.upload();
  }
};

const uploadCardDocuments = () => {
  if (cardUploader.value && cardUploader.value.files && cardUploader.value.files.length > 0) {
    cardUploader.value.upload();
  } else {
    toast.add({ 
      severity: 'info', 
      summary: 'Info', 
      detail: 'Please select files to upload', 
      life: 3000 
    });
  }
};

const uploadCardDocumentsWithAxios = async (event) => {
  const formData = new FormData();
  
  // Append each file to the FormData
  for (let file of event.files) {
    formData.append('cardDocuments[]', file);
  }
  
  // Add the customer ID if in edit mode
  if (props.isViewMode && props.client?.id) {
    formData.append('customer_id', props.client.id);
  }
  
  try {
    const response = await axios.post('/documents/upload-card', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    
    if (response.data.success) {
      cardFiles.value = [...cardFiles.value, ...response.data.files];
      toast.add({ 
        severity: 'success', 
        summary: 'Success', 
        detail: 'Card documents uploaded successfully', 
        life: 3000 
      });
      cardUploadDialogVisible.value = false;
      // Clear the uploader
      cardUploader.value.clear();
    } else {
      toast.add({ 
        severity: 'error', 
        summary: 'Error', 
        detail: response.data.message || 'Failed to upload card documents', 
        life: 3000 
      });
    }
  } catch (error) {
    toast.add({ 
      severity: 'error', 
      summary: 'Error', 
      detail: 'Failed to upload card documents: ' + (error.response?.data?.message || error.message), 
      life: 3000 
    });
  }
};

const uploadDocumentsWithAxios = async (event) => {
  const formData = new FormData();

  // Aggiungi i file al FormData
  for (let file of event.files) {
    formData.append('documents[]', file);
  }

  // Aggiungi l'ID del cliente se in modalità modifica
  if (props.isViewMode && props.client?.id) {
    formData.append('customer_id', props.client.id);
  }

  try {
    const response = await axios.post('/documents/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    if (response.data.success) {
      // Verifica che i file restituiti abbiano un tipo MIME valido
      documentFiles.value = [...documentFiles.value, ...response.data.files.map(file => ({
        ...file,
        mime_type: file.mime_type || 'application/octet-stream' // Tipo MIME di fallback
      }))];

      toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Documents uploaded successfully',
        life: 3000
      });
      documentUploadDialogVisible.value = false;
      documentUploader.value.clear();
    } else {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: response.data.message || 'Failed to upload documents',
        life: 3000
      });
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to upload documents: ' + (error.response?.data?.message || error.message),
      life: 3000
    });
  }
};

const onDocumentUpload = (event) => {
  const response = JSON.parse(event.xhr.response);
  if (response.success) {
    documentFiles.value = [...documentFiles.value, ...response.files];
    toast.add({ severity: 'success', summary: 'Success', detail: 'Documents uploaded successfully', life: 3000 });
    documentUploadDialogVisible.value = false;
  } else {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to upload documents', life: 3000 });
  }
};

const triggerDocumentUpload = () => {
  if (documentUploader.value && documentUploader.value.files && documentUploader.value.files.length > 0) {
    documentUploader.value.upload();
  } else {
    toast.add({ 
      severity: 'info', 
      summary: 'Info', 
      detail: 'Please select files to upload', 
      life: 3000 
    });
  }
};

const onCardUpload = (event) => {
  const response = JSON.parse(event.xhr.response);
  if (response.success) {
    cardFiles.value = [...cardFiles.value, ...response.files];
    toast.add({ severity: 'success', summary: 'Success', detail: 'Card documents uploaded successfully', life: 3000 });
    cardUploadDialogVisible.value = false;
  } else {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to upload card documents', life: 3000 });
  }
};

const loadCustomerDocuments = async (customerId) => {
  if (customerId) {
    try {
      const response = await axios.post(`/documents/customer/${customerId}`);
      if (response.data.success) {
        // Process identity documents
        documentFiles.value = (response.data.identity_documents || []).map(doc => ({
          ...doc,
          filename: doc.name,
          url: `/storage/${doc.path}`, // Costruisci l'URL corretto
          type: doc.type // Assicurati che il tipo MIME sia incluso
        }));
        
        // Process card documents
        cardFiles.value = (response.data.card_documents || []).map(doc => ({
          ...doc,
          filename: doc.name,
          url: `/storage/${doc.path}`, // Costruisci l'URL corretto
          type: doc.type // Assicurati che il tipo MIME sia incluso
        }));
      }
    } catch (error) {
      console.error('Failed to load customer documents:', error);
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Failed to load customer documents',
        life: 3000
      });
    }
  }
};

const createCustomer = async () => {
  try {
    const response = await axios.post('/customer/create', {
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
    
    // Get the new customer ID
    const customerId = response.data.customer.id;
    
    // If there are documents, attach them to the customer
    if (documentFiles.value.length > 0) {
      const documentIds = documentFiles.value.map(doc => doc.id).filter(id => id);
      if (documentIds.length > 0) {
        await axios.post(`/api/documents/attach/${customerId}`, {
          document_ids: documentIds
        });
      }
    }
    
    // If there are card documents, attach them to the customer
    if (cardFiles.value.length > 0) {
      const cardDocumentIds = cardFiles.value.map(doc => doc.id).filter(id => id);
      if (cardDocumentIds.length > 0) {
        await axios.post(`/api/documents/attach/${customerId}`, {
          document_ids: cardDocumentIds
        });
      }
    }
    
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
      cardHolder: cardHolder.value,
      documents: documentFiles.value,
      cardDocuments: cardFiles.value
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

const getFileTypeFromName = (filename) => {
  if (!filename) return 'Unknown';
  
  const extension = filename.split('.').pop().toLowerCase();
  
  switch(extension) {
    case 'jpg':
    case 'jpeg':
      return 'JPEG Image';
    case 'png':
      return 'PNG Image';
    case 'gif':
      return 'GIF Image';
    case 'pdf':
      return 'PDF Document';
    default:
      return extension.toUpperCase() + ' File';
  }
};

const constructFullUrl = (url, docId) => {
  if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
    return url;
  }

  const baseUrl = window.location.origin;

  if (url && url.startsWith('/')) {
    return `${baseUrl}${url}`;
  }

  if (url) {
    return `${baseUrl}/storage/${url}`; // Aggiungi il prefisso /storage/
  }

  if (docId) {
    return `${baseUrl}/documents/view/${docId}`;
  }

  return null; // Fallback se l'URL non può essere determinato
};

const previewDocument = async (doc) => {
  try {
    // Show loading state
    previewingDocument.value = { 
      loading: true,
      filename: doc.filename || doc.name || 'Document'
    };
    documentPreviewVisible.value = true;
    
    // Create a copy of the document object to avoid modifying the original
    const documentToPreview = { ...doc };
    
    // Construct full URL
    const fullUrl = constructFullUrl(doc.url, doc.id);
    if (!fullUrl) {
      throw new Error('Could not determine document URL');
    }
    
    // Add cache busting for images to prevent caching issues
    let previewUrl = fullUrl;
    if (isImageFile(documentToPreview)) {
      const separator = previewUrl.includes('?') ? '&' : '?';
      previewUrl = `${previewUrl}${separator}t=${Date.now()}`;
    }
    
    console.log('Previewing document with URL:', previewUrl);
    
    // For images, preload to verify they can be displayed
    if (isImageFile(documentToPreview)) {
      // Use a Promise to handle image loading
      await new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = resolve;
        img.onerror = reject;
        img.src = previewUrl;
      });
    }
    
    // Set the document with the constructed URL for preview
    previewingDocument.value = {
      ...documentToPreview,
      url: previewUrl,
      fullUrl: fullUrl // Store the clean URL without cache busting for download
    };
  } catch (error) {
    console.error('Error preparing document preview:', error);
    
    // Set error state but keep filename for display
    previewingDocument.value = {
      filename: doc.filename || doc.name || 'Document',
      id: doc.id,
      loadError: true
    };
    
    toast.add({
      severity: 'error',
      summary: 'Preview Error',
      detail: 'Failed to load document preview. Try downloading instead.',
      life: 3000
    });
  }
};

const deleteDocument = async (documentId, type, event) => {
  // If an event was passed, prevent its propagation
  if (event) {
    event.stopPropagation();
  }
  
  if (!documentId) return;
  
  confirm.require({
    message: 'Are you sure you want to delete this document?',
    header: 'Delete Document',
    icon: 'pi pi-exclamation-triangle',
    acceptClass: 'p-button-danger',
    accept: async () => {
      try {
        // Use POST method with _method parameter to simulate DELETE
        const response = await axios.post(`/documents/delete/${documentId}`, {
          _method: 'DELETE'
        });
        
        if (response.data.success) {
          // Remove from local state
          if (type === 'identity') {
            documentFiles.value = documentFiles.value.filter(doc => doc.id !== documentId);
          } else {
            cardFiles.value = cardFiles.value.filter(doc => doc.id !== documentId);
          }
          
          toast.add({ 
            severity: 'success', 
            summary: 'Success', 
            detail: 'Document deleted successfully', 
            life: 3000 
          });
        } else {
          toast.add({ 
            severity: 'error', 
            summary: 'Error', 
            detail: response.data.message || 'Failed to delete document', 
            life: 3000 
          });
        }
      } catch (error) {
        toast.add({ 
          severity: 'error', 
          summary: 'Error', 
          detail: 'Failed to delete document: ' + (error.response?.data?.message || error.message), 
          life: 3000 
        });
      }
    }
  });
};

const downloadDocument = async (doc) => {
  try {
    // Determine download URL (use stored fullUrl or construct it)
    let downloadUrl = doc.fullUrl || constructFullUrl(doc.url, doc.id);
    
    if (!downloadUrl) {
      throw new Error('Could not determine document URL for download');
    }
    
    console.log('Downloading document from URL:', downloadUrl);
    
    // For direct download, use fetch with blob response
    const response = await fetch(downloadUrl);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    // Get the blob from response
    const blob = await response.blob();
    
    // Create download link
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = doc.filename || doc.name || 'document';
    document.body.appendChild(link);
    link.click();
    
    // Cleanup
    setTimeout(() => {
      window.URL.revokeObjectURL(url);
      document.body.removeChild(link);
    }, 100);
    
    toast.add({
      severity: 'success',
      summary: 'Download Started',
      detail: `Downloading "${doc.filename || doc.name}"`,
      life: 3000
    });
  } catch (error) {
    console.error('Download error:', error);
    toast.add({
      severity: 'error',
      summary: 'Download Failed',
      detail: 'Unable to download the document. Please try again later.',
      life: 3000
    });
  }
};

watch(() => props.client, (newClient) => {
  if (newClient && newClient.id) {
    loadCustomerDocuments(newClient.id);
  }
}, { immediate: true });
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

.document-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 0.5rem;
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  padding: 0.5rem;
}

.document-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: white;
  border-radius: 0.25rem;
  padding: 0.5rem 1rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.document-info {
  display: flex;
  align-items: center;
}

.document-actions {
  display: flex;
  gap: 0.25rem;
}

.document-preview-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  background-color: #f5f5f5;
  border-radius: 0.25rem;
  overflow: hidden;
}
</style>