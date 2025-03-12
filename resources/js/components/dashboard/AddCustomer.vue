<template>
    <h1 class="font-bold text-3xl">Add Customer</h1>
    <Breadcrumb :model="items" class="custom-breadcrumb" />

    <Card>
        <template #content>
            <h1 class="font-bold text-2xl">Personal Data</h1>
            <p class="mb-2 text-gray-500">Fill the customer personal data</p>
            <div class="input-group-grid">
                <div class="input-group-row">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-user"></i>
                        </InputGroupAddon>
                        <InputText v-model="text1" placeholder="Name" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-user"></i>
                        </InputGroupAddon>
                        <InputText v-model="text1" placeholder="Surname" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-calendar"></i>
                        </InputGroupAddon>
                        <Calendar v-model="birthDate" placeholder="Birth Date" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-globe"></i>
                        </InputGroupAddon>
                        <Select v-model="selectedLanguage" :options="languages" optionLabel="label" optionValue="value" placeholder="Select a language" />
                    </InputGroup>
                </div>
                <div class="input-group-row">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-phone"></i>
                        </InputGroupAddon>
                        <InputText v-model="number" placeholder="Phone Number" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-envelope"></i>
                        </InputGroupAddon>
                        <InputText v-model="email" placeholder="Email" />
                    </InputGroup>
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-home"></i>
                        </InputGroupAddon>
                        <AutoComplete v-model="selectedAddress" :suggestions="filteredAddresses" completeMethod="searchAddress" placeholder="Address" />
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
                    <InputText v-model="driverLicenseNumber" placeholder="Serial Number" />
                </InputGroup>
            </div>
            <div>
                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-calendar"></i>
                    </InputGroupAddon>
                    <Calendar v-model="driverLicenseValidUntil" placeholder="Valid Until" />
                </InputGroup>
            </div>

            <div>
                <p>Identity Card</p>
            </div>
            <div></div>

            <div>
                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-id-card"></i>
                    </InputGroupAddon>
                    <InputText v-model="identityCardNumber" placeholder="Serial Number" />
                </InputGroup>
            </div>
            <div>
                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-calendar"></i>
                    </InputGroupAddon>
                    <Calendar v-model="identityCardValidUntil" placeholder="Valid Until" />
                </InputGroup>
            </div>
        </div>
        <p class="mt-3 text-gray-500">You can attach customer documents scans</p>
        <Button icon="pi pi-paperclip"  label="Attach Documents" />


            <h1 class="font-bold text-2xl mt-10">Card</h1>
            <p class="mb-2 text-gray-500">Fill the customer card info</p>

            <div class="card-input-row">
                <div class="card-number">
                    <InputGroup>
                        <InputGroupAddon>
                            <i class="pi pi-credit-card"></i>
                        </InputGroupAddon>
                        <!-- Card Number è di tipo 'number' -->
                        <InputText v-model="cardNumber" type="number" placeholder="Card Number" />
                    </InputGroup>
                </div>

                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-calendar"></i>
                    </InputGroupAddon>
                    <!-- MM/YYYY è di tipo 'text' per accettare il formato data -->
                    <InputText v-model="expirationDate" type="text" placeholder="MM/YYYY" />
                </InputGroup>

                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-lock"></i>
                    </InputGroupAddon>
                    <!-- CVV2 è di tipo 'number' -->
                    <InputText v-model="cvv" type="number" placeholder="CVV2" />
                </InputGroup>

                <InputGroup>
                    <InputGroupAddon>
                        <i class="pi pi-user"></i>
                    </InputGroupAddon>
                    <!-- Card Holder è di tipo 'text' -->
                    <InputText v-model="cardHolder" type="text" placeholder="Card Holder" />
                </InputGroup>
            </div>

            <p class="mt-3 text-gray-500">You can attach customer credi card scans</p>
            <Button icon="pi pi-paperclip"  label="Attach Documents" />

            <Button label="Create Customer" severity="contrast" class="ml-2" icon="pi pi-user" />


        </template>
    </Card>
</template>

<script>
import Breadcrumb from 'primevue/breadcrumb';
import Card from 'primevue/card';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import AutoComplete from 'primevue/autocomplete';
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Calendar from 'primevue/calendar';

export default {
  name: 'AddCustomer',
  components: {
    Breadcrumb,
    Card,
    Tabs,
    TabList,
    Tab,
    TabPanels,
    TabPanel,
    InputText,
    Select,
    AutoComplete,
    Divider,
    FileUpload,
    Button,
    Textarea,
    InputGroup,
    InputGroupAddon,
    Calendar
  },
  data() {
    return {
      items: [
        { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
        { label: 'Add Customer', url: '/dashboard/add-customer' },
      ],
      birthDate: null,
      selectedLanguage: null,
      languages: [
        { label: 'Italiano', value: 'it' },
        { label: 'Inglese', value: 'en' },
        { label: 'Rumeno', value: 'ro' }
      ],
      selectedAddress: null,
      filteredAddresses: []
    };
  },
  methods: {
    searchAddress(event) {
      // Implementa la logica per cercare gli indirizzi qui
      // Ad esempio, puoi fare una chiamata API per ottenere gli indirizzi
      this.filteredAddresses = [
        // Esempio di dati di indirizzo
        { label: 'Via Roma, 1', value: 'Via Roma, 1' },
        { label: 'Piazza del Duomo, 2', value: 'Piazza del Duomo, 2' }
      ];
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
    flex: 2; /* Rende il campo Card Number più largo rispetto agli altri */
}

</style>
