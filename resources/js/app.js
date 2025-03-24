import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import App from './App.vue';
import router from './router';
import 'primeicons/primeicons.css';
import '../css/app.css';
import ToastPlugin from './plugins/toast';
import Tooltip from 'primevue/tooltip';

const app = createApp(App);

app.use(PrimeVue, {
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.my-app-dark',
        }
    }
});

app.use(router);
app.use(ToastService);
app.use(ToastPlugin);
app.use(ConfirmationService);
app.directive('tooltip', Tooltip);
app.mount('#app');
