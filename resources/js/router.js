import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import Home from '@/views/Home.vue';
import NotFound from '@/views/NotFound.vue';
import Dashboard from '@/views/Dashboard.vue';
import Bookings from '@/components/dashboard/Bookings.vue';
import Clients from '@/components/dashboard/Clients.vue';
import Reports from '@/components/dashboard/Reports.vue';
import Settings from '@/components/dashboard/Settings.vue';
import Vehicles from '@/components/dashboard/Vehicles.vue';
import Dash from '@/components/Dash.vue';
import Help from '@/components/dashboard/Help.vue';
import HomeDash from '@/components/dashboard/Home.vue';

const routes = [
  {
    path: '/',
    component: Home,
    meta: { title: 'Home - Lease'}
  },
  {
    path: '/login',
    component: Login,
    meta: { title: 'Login - Lease'}
  },
  {
    path: '/dash',
    component: Dash,
    meta: { title: 'Dash - Lease'}
  },
  {
    path: '/dashboard',
    component: Dashboard,
    meta: { title: 'Dashboard - Lease'},
    redirect: '/dashboard/home',
    children: [
      { path: 'bookings', name: 'dashboard-bookings', component: Bookings, meta: { title: 'Bookings'} },
      { path: 'home', name: 'dashboard-home', component: HomeDash, meta: { title: 'Home'} },
      { path: 'clients', name: 'dashboard-clients', component: Clients, meta: { title: 'Clients'} },
      { path: 'reports', name: 'dashboard-reports', component: Reports, meta: { title: 'Reports'} },
      { path: 'settings', name: 'dashboard-settings', component: Settings, meta: { title: 'Settings'} },
      { path: 'vehicles', name: 'dashboard-vehicles', component: Vehicles, meta: { title: 'Vehicles'} },
      { path: 'help', name: 'dashboard-help', component: Help, meta: { title: 'Help'} },
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

export default router;
