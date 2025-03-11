import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ConfirmationService from 'primevue/confirmationservice';
import App from './App.vue';
import router from './router';
import 'primeicons/primeicons.css';
import '../css/app.css';
// import axios from 'axios';

const app = createApp(App);

// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.my-app-dark',
        }
    }
});

app.use(router);
app.use(ConfirmationService);
app.mount('#app');
