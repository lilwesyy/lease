<template>
    <div class="language-switcher">
      <Button
        v-for="lang in availableLocales"
        :key="lang.code"
        :label="lang.name"
        :class="{ active: currentLocale === lang.code }"
        @click="changeLocale(lang.code)"
        class="p-button-text"
      />
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { useI18n } from 'vue-i18n';
  import Button from 'primevue/button';
  
  const { locale } = useI18n();
  
  const availableLocales = [
    { code: 'en', name: 'English' },
    { code: 'it', name: 'Italiano' },
  ];
  
  const currentLocale = computed(() => locale.value);
  
  const changeLocale = (code) => {
    locale.value = code;
    localStorage.setItem('locale', code);
  };
  </script>
  
  <style scoped>
  .language-switcher {
    display: flex;
    gap: 0.5rem;
  }
  
  .active {
    font-weight: bold;
    text-decoration: underline;
  }
  </style>