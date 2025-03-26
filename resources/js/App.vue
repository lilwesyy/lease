<template>
  <router-view></router-view>
  <Toast position="bottom-right" />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Toast from 'primevue/toast';
import axios from 'axios';
import i18n from './i18n';

const appSettings = ref({
  lang: 'en'
});

const fetchAppSettings = async () => {
  try {
    const response = await axios.post('/settings');
    if (response.data && response.data.settings) {
      appSettings.value = response.data.settings;
      console.log('App settings loaded:', appSettings.value);
      
      if (appSettings.value.lang) {
        i18n.global.locale.value = appSettings.value.lang;
        localStorage.setItem('locale', appSettings.value.lang);
        console.log('Language set to:', appSettings.value.lang);
      }
      
      localStorage.setItem('appSettings', JSON.stringify(appSettings.value));
      
      return appSettings.value;
    }
  } catch (error) {
    console.error('Error fetching app settings:', error);
    return null;
  }
};

// Carica le impostazioni quando il componente viene montato
onMounted(() => {
  console.log('App component mounted, loading settings...');
  fetchAppSettings();
});

// Esporta le impostazioni e la funzione per renderle disponibili a livello globale
defineExpose({
  appSettings,
  fetchAppSettings
});
</script>