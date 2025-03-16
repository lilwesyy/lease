<template>
  <h3 class="font-semibold text-xl mb-3">Vehicle Damages</h3>
  <Button label="Add Damage" icon="pi pi-plus" @click="openAddDamageDialog" />

  <DataTable :value="damages" selectionMode="single" :metaKeySelection="false" dataKey="id" tableStyle="min-width: 50rem" @row-click="openDamageDetails">
    <Column field="damagePositionLabel" header="Damage Position" :body="getDamagePositionLabel"></Column>
    <Column field="damageSeverityLabel" header="Damage Severity" :body="getDamageSeverityLabel"></Column>
    <Column field="damageTypeLabel" header="Damage Type" :body="getDamageTypeLabel"></Column>
    <Column field="damageDescription" header="Damage Description"></Column>
  </DataTable>

  <Dialog :header="dialogHeader" v-model:visible="showDialog" :modal="true" :closable="true">
    <div class="p-fluid">
      <div class="p-field">
        <label for="damagePosition">Damage Position</label>
        <Select id="damagePosition" v-model="currentDamage.damagePosition" :options="damagePositions" optionLabel="label" optionValue="value" placeholder="Select a Position" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
      </div>
      <div class="p-field">
        <label for="damageSeverity">Damage Severity</label>
        <Select id="damageSeverity" v-model="currentDamage.damageSeverity" :options="damageSeverities" optionLabel="label" optionValue="value" placeholder="Select Severity" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
      </div>
      <div class="p-field">
        <label for="damageType">Damage Type</label>
        <Select id="damageType" v-model="currentDamage.damageType" :options="damageTypes" optionLabel="label" optionValue="value" placeholder="Select Type" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
      </div>
      <div class="p-field">
        <label for="damageDescription">Damage Description</label>
        <Textarea v-model="currentDamage.damageDescription" rows="5" cols="30" class="full-width" :class="{ 'select-readonly': isViewMode}"/>
      </div>
    </div>
    <div class="p-dialog-footer">
      <Button v-if="isViewMode" label="Edit" icon="pi pi-pencil" @click="enableEditMode" />
      <Button v-if="!isViewMode" label="Cancel" icon="pi pi-times" @click="showDialog = false" class="p-button-text" />
      <Button v-if="!isViewMode" label="Save" icon="pi pi-check" @click="saveDamage" />
      <Button v-if="!isViewMode && currentDamage.id" label="Delete" icon="pi pi-trash" @click="deleteDamage" class="p-button-danger" />
    </div>
  </Dialog>
</template>

<script>
import { ref, watch, getCurrentInstance } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Textarea from 'primevue/textarea';
import Select from 'primevue/select';

export default {
  name: 'VehicleDamage',
  props: {
    isViewMode: Boolean,
    isEditMode: Boolean,
    vehicle: Object,
    damages: Array
  },
  components: { DataTable, Column, Dialog, Button, Textarea, Select },
  setup(props) {
    const { proxy } = getCurrentInstance();
    const showDialog = ref(false);
    const isViewMode = ref(false);
    const isEditMode = ref(false);
    const dialogHeader = ref('');
    const damages = ref([...props.damages]);
    const currentDamage = ref({ id: null, damagePosition: '', damageSeverity: '', damageType: '', damageDescription: '' });

    const damagePositions = ref([
      { label: 'Front Bumper', value: 'front_bumper' },
      { label: 'Rear Bumper', value: 'rear_bumper' },
      { label: 'Left Fender', value: 'left_fender' },
      { label: 'Right Fender', value: 'right_fender' },
      { label: 'Front Left Door', value: 'front_left_door' },
      { label: 'Front Right Door', value: 'front_right_door' },
      { label: 'Rear Right Door', value: 'rear_right_door' },
      { label: 'Rear Left Door', value: 'rear_left_door' }
    ]);

    const damageSeverities = ref([
      { label: 'Minor', value: 'minor' },
      { label: 'Moderate', value: 'moderate' },
      { label: 'Severe', value: 'severe' },
      { label: 'Very Severe', value: 'very_severe' }
    ]);

    const damageTypes = ref([
      { label: 'Scratch', value: 'scratch' },
      { label: 'Dent', value: 'dent' },
      { label: 'Crack', value: 'crack' },
      { label: 'Broken', value: 'broken' }
    ]);

    const getDamagePositionLabel = (rowData) => {
      const position = damagePositions.value.find(pos => pos.value === rowData.damagePosition);
      return position ? position.label : rowData.damagePosition;
    };

    const getDamageSeverityLabel = (rowData) => {
      const severity = damageSeverities.value.find(sev => sev.value === rowData.damageSeverity);
      return severity ? severity.label : rowData.damageSeverity;
    };

    const getDamageTypeLabel = (rowData) => {
      const type = damageTypes.value.find(typ => typ.value === rowData.damageType);
      return type ? type.label : rowData.damageType;
    };

    const openAddDamageDialog = () => {
      dialogHeader.value = 'Add Damage';
      isViewMode.value = false;
      isEditMode.value = true;
      currentDamage.value = { id: null, damagePosition: '', damageSeverity: '', damageType: '', damageDescription: '' };
      showDialog.value = true;
    };

    const openDamageDetails = (event) => {
      dialogHeader.value = 'Damage Details';
      isViewMode.value = true;
      isEditMode.value = false;
      currentDamage.value = { ...event.data };
      showDialog.value = true;
    };

    const enableEditMode = () => {
      isViewMode.value = false;
      isEditMode.value = true;
    };

    const saveDamage = () => {
      if (isEditMode.value) {
        const index = damages.value.findIndex(damage => damage.id === currentDamage.value.id);
        if (index !== -1) damages.value[index] = { ...currentDamage.value };
      } else {
        damages.value.push({ ...currentDamage.value, id: Date.now() });
      }
      damages.value = [...damages.value];
      showDialog.value = false;
    };

    const deleteDamage = () => {
      damages.value = damages.value.filter(damage => damage.id !== currentDamage.value.id);
      showDialog.value = false;
    };

    watch(() => props.damages, (newDamages) => damages.value = [...newDamages]);

    return { 
      showDialog, 
      isViewMode, 
      isEditMode, 
      dialogHeader, 
      damages, 
      currentDamage, 
      damagePositions, 
      damageSeverities, 
      damageTypes, 
      openAddDamageDialog, 
      openDamageDetails, 
      enableEditMode, 
      saveDamage, 
      deleteDamage,
      getDamagePositionLabel,
      getDamageSeverityLabel,
      getDamageTypeLabel
    };
  }
};
</script>

<style>
.full-width { width: 100%; }
.select-readonly .p-dropdown, .select-readonly .p-inputtext { pointer-events: none; }
</style>