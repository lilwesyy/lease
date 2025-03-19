<template>
    <h1 v-if="!isViewMode" class="font-bold text-3xl">Add Customer</h1>
    <h1 v-else-if="isEditMode" class="font-bold text-3xl">Edit Customer</h1>
    <h1 v-else class="font-bold text-3xl">View Customer</h1>

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

  <script>
  import Breadcrumb from 'primevue/breadcrumb';
  import Card from 'primevue/card';
  import InputText from 'primevue/inputtext';
  import Select from 'primevue/select';
  import AutoComplete from 'primevue/autocomplete';
  import Button from 'primevue/button';
  import InputGroup from 'primevue/inputgroup';
  import InputGroupAddon from 'primevue/inputgroupaddon';
  import DatePicker from 'primevue/datepicker';
  import axios from 'axios';
  import ConfirmDialog from 'primevue/confirmdialog';

  export default {
    name: 'AddCustomer',
    props: {
      isViewMode: {
        type: Boolean,
        default: false
      },
      client: {
        type: Object,
        required: false,
        default: () => ({})
      }
    },
    components: {
      Breadcrumb,
      Card,
      InputText,
      Select,
      AutoComplete,
      Button,
      InputGroup,
      InputGroupAddon,
      DatePicker,
      ConfirmDialog
    },
    emits: ['close'],
    data() {
      return {
        isEditMode: false,
        firstName: '',
        lastName: '',
        birthDate: null,
        selectedLanguage: null,
        phone: '',
        email: '',
        selectedAddress: null,
        filteredAddresses: [],
        driverLicenseNumber: '',
        driverLicenseValidUntil: null,
        identityCardNumber: '',
        identityCardValidUntil: null,
        cardNumber: '',
        expirationDate: '',
        cvv: '',
        cardHolder: '',
        items: [
          { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
          { label: 'Add Customer', url: '/dashboard/add-customer' }
        ],
        languages: [
          { label: 'Italian', value: 'it' },
          { label: 'English', value: 'en' },
          { label: 'Romanian', value: 'ro' }
        ]
      };
    },
    computed: {
      breadcrumbItems() {
        return [
          { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
          {
            label: this.isViewMode ? (this.isEditMode ? 'Edit Customer' : 'View Customer') : 'Add Customer',
            url: this.isViewMode ? `/dashboard/customers/${this.client?.id || ''}` : '/dashboard/add-customer'
          }
        ];
      }
    },
    watch: {
      client: {
        immediate: true,
        handler(newClient) {
          if (newClient && Object.keys(newClient).length > 0) {
            this.firstName = newClient.firstName || '';
            this.lastName = newClient.lastName || '';
            this.birthDate = newClient.birthDate || null;
            this.selectedLanguage = newClient.language || null;
            this.phone = newClient.phone || '';
            this.email = newClient.email || '';
            this.selectedAddress = newClient.address || '';
            this.driverLicenseNumber = newClient.licenseNumber || '';
            this.driverLicenseValidUntil = newClient.licenseValidUntil || null;
            this.identityCardNumber = newClient.nationalID || '';
            this.identityCardValidUntil = newClient.identityCardValidUntil || null;
            this.cardNumber = newClient.cardNumber || '';
            this.expirationDate = newClient.expirationDate || '';
            this.cvv = newClient.cvv || '';
            this.cardHolder = newClient.cardHolder || '';
          }
        }
      }
    },
    methods: {
        async deleteCustomer() {
        this.$confirm.require({
            message: 'Are you sure you want to delete this customer?',
            header: 'Confirmation',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            rejectClass: 'p-button-secondary',
            accept: async () => {
            try {
                await axios.delete(`/customer/delete/${this.client.id}`);
                this.$router.push('/dashboard/customers');
                this.$showToast('success', 'Success', 'Customer deleted successfully');
                this.$emit('close');
            } catch (error) {
                this.$showToast('error', 'Error', 'Failed to delete customer');
            }
            },
        });
        },
      async createCustomer() {
        try {
          const response = await axios.post('/customer/create', {
            firstName: this.firstName,
            lastName: this.lastName,
            birthDate: this.birthDate,
            language: this.selectedLanguage,
            phone: this.phone,
            email: this.email,
            address: this.selectedAddress,
            licenseNumber: this.driverLicenseNumber,
            licenseValidUntil: this.driverLicenseValidUntil,
            nationalID: this.identityCardNumber,
            identityCardValidUntil: this.identityCardValidUntil,
            cardNumber: this.cardNumber,
            expirationDate: this.expirationDate,
            cvv: this.cvv,
            cardHolder: this.cardHolder
          });
          this.$router.push('/dashboard/customers');
          this.$showToast('success', 'Success', 'Customer created successfully');
        } catch (error) {
          this.$showToast('error', 'Error', 'Failed to create customer');
        }
      },
      async editCustomer() {
        try {
          const response = await axios.put(`/customer/edit/${this.client.id}`, {
            firstName: this.firstName,
            lastName: this.lastName,
            birthDate: this.birthDate,
            language: this.selectedLanguage,
            phone: this.phone,
            email: this.email,
            address: this.selectedAddress,
            licenseNumber: this.driverLicenseNumber,
            licenseValidUntil: this.driverLicenseValidUntil,
            nationalID: this.identityCardNumber,
            identityCardValidUntil: this.identityCardValidUntil,
            cardNumber: this.cardNumber,
            expirationDate: this.expirationDate,
            cvv: this.cvv,
            cardHolder: this.cardHolder
          });
          this.$router.push('/dashboard/customers');
          this.$showToast('success', 'Success', 'Customer updated successfully');
        } catch (error) {
          this.$showToast('error', 'Error', 'Failed to update customer');
        }
      },
      searchAddress(event) {
        const service = new google.maps.places.AutocompleteService();
        service.getPlacePredictions({ input: event.query }, (predictions, status) => {
          if (status !== google.maps.places.PlacesServiceStatus.OK) {
            console.error('Error fetching address predictions:', status);
            return;
          }
          this.filteredAddresses = predictions.map(prediction => ({
            label: prediction.description,
            value: prediction.description
          }));
        });
      },
      enableEditMode() {
        this.isEditMode = true;
        this.originalData = { ...this.$data };
      },
      saveChanges() {
        this.isEditMode = false;
        this.editCustomer();
      },
      cancelEditMode() {
        Object.assign(this.$data, this.originalData);
        this.isEditMode = false;
      }
    }
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
