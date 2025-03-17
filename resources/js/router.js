import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import store from './store'; // Importa lo store Vuex
import Login from '@/views/Login.vue';
import Home from '@/views/Home.vue';
import NotFound from '@/views/NotFound.vue';
import Dashboard from '@/views/Dashboard.vue';
import Bookings from '@/components/dashboard/bookings/Bookings.vue';
import AddBooking from '@/components/dashboard/bookings/AddBooking.vue';
import Customers from '@/components/dashboard/customers/Customers.vue';
import Reports from '@/components/dashboard/Reports.vue';
import Settings from '@/components/dashboard/Settings.vue';
import Vehicles from '@/components/dashboard/vehicles/Vehicles.vue';
import Dash from '@/components/Dash.vue';
import Help from '@/components/dashboard/Help.vue';
import HomeDash from '@/components/dashboard/Home.vue';
import AddVehicle from '@/components/dashboard/vehicles/AddVehicle.vue';
import AddCustomer from '@/components/dashboard/customers/AddCustomer.vue';
import Calendar from '@/components/dashboard/Calendar.vue';

const routes = [
  {
    path: '/',
    component: Home,
    meta: { title: 'Home - Lease', requiresAuth: false }
  },
  {
    path: '/login',
    component: Login,
    meta: { title: 'Login - Lease', requiresAuth: false }
  },
  {
    path: '/dashboard',
    component: Dashboard,
    meta: { title: 'Dashboard - Lease', requiresAuth: true },
    redirect: '/dashboard/home',
    children: [
      { path: 'bookings', component: Bookings, meta: { title: 'Bookings', requiresAuth: true } },
      { path: 'add-booking', component: AddBooking, meta: { title: 'Add Booking', requiresAuth: true } },
      { path: 'home', component: HomeDash, meta: { title: 'Home', requiresAuth: true } },
      { path: 'customers', component: Customers, meta: { title: 'Customers', requiresAuth: true } },
      { path: 'add-customer', component: AddCustomer, meta: { title: 'Add Customer', requiresAuth: true } },
      { path: 'reports', component: Reports, meta: { title: 'Reports', requiresAuth: true } },
      { path: 'settings', component: Settings, meta: { title: 'Settings', requiresAuth: true } },
      { path: 'vehicles', component: Vehicles, meta: { title: 'Vehicles', requiresAuth: true } },
      { path: 'add-vehicles', component: AddVehicle, meta: { title: 'Add Vehicles', requiresAuth: true } },
      { path: 'help', component: Help, meta: { title: 'Help', requiresAuth: true } },
      { path: 'calendar', component: Calendar, meta: { title: 'Calendar', requiresAuth: true } },
    ]
  },
  {
    path: '/:catchAll(.*)',
    component: NotFound,
    meta: { title: '404 - Page Not Found' }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// 🚀 Middleware per controllare l'autenticazione
router.beforeEach(async (to, from, next) => {
//   console.log(`Navigating to: ${to.path}`);
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('authToken');
    if (!token) {
      console.log('No auth token found');
      return next('/login');
    }
    try {
      await store.dispatch('fetchUser');
      const user = store.getters.user;
      if (user) {
        // console.log('User is authenticated');
        next(); // Se l'utente è autenticato, accede alla pagina
      } else {
        // console.log('User is not authenticated');
        next('/login'); // Se non autenticato, viene reindirizzato al login
      }
    } catch (error) {
      console.log('Error during authentication check', error);
      next('/login');
    }
  } else {
    next(); // Se non serve autenticazione, continua normalmente
  }
});

export default router;
