<template>
  <Dialog header="Add Vehicle" :visible="showModal" @update:visible="updateShowModal" :modal="true" :closable="false" :style="{ width: '50vw'}">
    <div class="p-fluid grid-container">
      <div class="p-field" v-for="(field, index) in fields" :key="index">
        <label :for="field.id">{{ field.label }}</label>
        <InputGroup>
          <InputGroupAddon>
            <i :class="field.icon"></i>
          </InputGroupAddon>
          <InputText :id="field.id" v-model="newVehicle[field.model]" :placeholder="field.label" />
        </InputGroup>
      </div>
    </div>
    <div class="p-dialog-footer">
      <Button label="Cancel" icon="pi pi-times" @click="updateShowModal(false)" class="p-button-secondary p-button-text" />
      <Button label="Save" icon="pi pi-check" @click="addVehicle" class="p-button-primary" />
    </div>
  </Dialog>
</template>

<script>
import { ref } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';

export default {
  name: 'AddVehicleDialog',
  components: {
    Dialog,
    InputText,
    Button,
    InputGroup,
    InputGroupAddon
  },
  props: {
    showModal: {
      type: Boolean,
      required: true
    },
    newVehicle: {
      type: Object,
      required: true
    },
    addVehicle: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      fields: [
        { id: 'make', label: 'Make', model: 'make', icon: 'pi pi-car' },
        { id: 'model', label: 'Model', model: 'model', icon: 'pi pi-car' },
        { id: 'year', label: 'Year', model: 'year', icon: 'pi pi-calendar' },
        { id: 'engine', label: 'Engine', model: 'engine', icon: 'pi pi-cog' },
        { id: 'seats', label: 'Seats', model: 'seats', icon: 'pi pi-users' },
        { id: 'transmission', label: 'Transmission', model: 'transmission', icon: 'pi pi-cog' },
        { id: 'mileage', label: 'Mileage', model: 'mileage', icon: 'pi pi-map-marker' },
        { id: 'color', label: 'Color', model: 'color', icon: 'pi pi-palette' },
        { id: 'dailyPrice', label: 'Daily Price', model: 'dailyPrice', icon: 'pi pi-dollar' },
        { id: 'status', label: 'Status', model: 'status', icon: 'pi pi-info-circle' },
        { id: 'imageUrl', label: 'Image URL', model: 'imageUrl', icon: 'pi pi-image' }
      ]
    };
  },
  methods: {
    updateShowModal(value) {
      this.$emit('update:showModal', value);
    }
  }
}
</script>

<style scoped>
.p-dialog-footer {
  display: flex;
  justify-content: flex-end;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}

.p-inputgroup-addon {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>