<template>
  <router-view></router-view>
  <Toast position="bottom-right" />
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Toast from 'primevue/toast';
import axios from 'axios';
import i18n from './i18n';
import { useStyle } from 'primevue/usestyle';

const appSettings = ref({
  lang: 'en',
  themeColor: ''
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
      
      if (appSettings.value.themeColor) {
        updatePrimaryColor(appSettings.value.themeColor);
        console.log('Theme color set to:', appSettings.value.themeColor);
      }
      
      localStorage.setItem('appSettings', JSON.stringify(appSettings.value));
      
      return appSettings.value;
    }
  } catch (error) {
    console.error('Error fetching app settings:', error);
    return null;
  }
};

const updatePrimaryColor = (color) => {
  // Rimuovi il carattere # se presente
  const hexColor = color.startsWith('#') ? color : `#${color}`;
  
  // Aggiorna le variabili CSS di PrimeVue
  document.documentElement.style.setProperty('--primary-color', hexColor);
  
  // Aggiorna anche le variabili di stato dei componenti PrimeVue
  document.documentElement.style.setProperty('--primary-color-text', '#ffffff');
  document.documentElement.style.setProperty('--focus-ring', `${hexColor}40`);
  
  // Calcola colori gradient più chiari per hover e focus
  const rgbColor = hexToRgb(hexColor);
  if (rgbColor) {
    // Crea una versione più chiara per hover
    const lighterColor = `rgba(${rgbColor.r}, ${rgbColor.g}, ${rgbColor.b}, 0.8)`;
    document.documentElement.style.setProperty('--primary-lighter', lighterColor);
  }
};

const hexToRgb = (hex) => {
  const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
};

onMounted(() => {
  console.log('App component mounted, loading settings...');
  fetchAppSettings();
});

defineExpose({
  appSettings,
  fetchAppSettings
});
</script>
