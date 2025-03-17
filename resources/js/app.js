import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import App from './App.vue';
import router from './router';
import 'primeicons/primeicons.css';
import '../css/app.css';
import store from './store';
import ToastPlugin from './plugins/toast';

const app = createApp(App);

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.my-app-dark',
        }
    }
});

app.use(store);
app.use(router);
app.use(ToastService);
app.use(ToastPlugin);
app.use(ConfirmationService);

app.mount('#app');
