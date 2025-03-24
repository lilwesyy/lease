<template>
  <router-view></router-view>
  <Toast position="bottom-right" />
</template>

<script>
import Toast from 'primevue/toast';

export default {
  components: {
    Toast
  }
};
</script>