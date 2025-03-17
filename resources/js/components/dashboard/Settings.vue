<template>
  <div class="settings">
    <TabView>
      <TabPanel header="Profile">
        <div class="profile-settings">
          <h3>Profile</h3>
          <InputText  v-model="user.firstName" placeholder="First Name" class="input-field" />
          <InputText v-model="user.lastName" placeholder="Last Name" class="input-field" />
          <InputText v-model="user.email" placeholder="Email" class="input-field" />
          <InputText v-model="user.phone" placeholder="Phone" class="input-field" />
          <Button label="Save" @click="saveProfile" class="save-button" />
        </div>
      </TabPanel>
      <TabPanel header="Change Password">
        <div class="password-settings">
          <h3>Change Password</h3>
          <div class="input-group">
            <label for="current-password">Current Password</label>
            <Password id="current-password" v-model="passwords.current" placeholder="Current Password" />
          </div>
          <div class="input-group">
            <label for="new-password">New Password</label>
            <Password id="new-password" v-model="passwords.new" placeholder="New Password" />
          </div>
          <div class="input-group">
            <label for="confirm-password">Confirm New Password</label>
            <Password id="confirm-password" v-model="passwords.confirm" placeholder="Confirm New Password" />
          </div>
          <Button label="Change Password" @click="changePassword" class="small-button" />
        </div>
      </TabPanel>
      <TabPanel header="Users">
        <div class="users-settings">
          <h3>Website Users</h3>
          <div class="user-cards">
            <div v-for="user in users" :key="user.name" class="user-card" @click="editUser(user)">
              <Avatar :icon="user.avatar" shape="circle" />
              <div class="user-info">
                <h4>{{ user.name }} {{ user.surname }}</h4>
                <Tag :value="user.role" :severity="getRoleSeverity(user.role)" />
              </div>
            </div>
          </div>
        </div>
      </TabPanel>
    </TabView>
    <Dialog header="Edit User" v-model:visible="isDialogVisible" :modal="true" :closable="false">
      <div class="dialog-content">
        <div class="field">
          <label for="name">First Name</label>
            <InputGroup>
              <InputGroupAddon>
                  <i class="pi pi-user"></i>
              </InputGroupAddon>
              <InputText v-model="selectedUser.name" placeholder="Username" />
          </InputGroup>
        </div>
        <div class="field">
          <label for="surname">Last Name</label>
          <InputGroup>
              <InputGroupAddon>
                  <i class="pi pi-user"></i>
              </InputGroupAddon>
              <InputText v-model="selectedUser.surname" placeholder="Username" />
          </InputGroup>
        </div>
        <div class="field">
          <label for="role">Role</label>
          <InputGroup>
            <InputGroupAddon>
                <i class="pi pi-briefcase"></i>
            </InputGroupAddon>
            <Select v-model="selectedUser.role" :options="roles" optionLabel="label" placeholder="Select Role" />
        </InputGroup>

        </div>
        <div class="dialog-buttons">
          <Button label="Save" icon="pi pi-check" @click="saveUser" />
          <Button label="Cancel" icon="pi pi-times" @click="isDialogVisible = false" class="p-button-secondary" />
          <Button label="Delete" icon="pi pi-trash" @click="confirmDeleteUser" class="p-button-danger" />
        </div>
      </div>
    </Dialog>
    <!-- <ConfirmPopup /> -->
  </div>
</template>

<script>
import { ref } from 'vue';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Button from 'primevue/button';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Avatar from 'primevue/avatar';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Select from 'primevue/select';
// import ConfirmPopup from 'primevue/confirmpopup';
import { useConfirm } from "primevue/useconfirm";

export default {
  components: {
    InputText,
    Password,
    Button,
    TabView,
    TabPanel,
    Avatar,
    Tag,
    Dialog,
    Dropdown,
    InputGroup,
    InputGroupAddon,
    Select,
    // ConfirmPopup
  },
  setup() {
    const user = ref({
      firstName: '',
      lastName: '',
      email: '',
      phone: ''
    });

    const passwords = ref({
      current: '',
      new: '',
      confirm: ''
    });

    const users = ref([
      { avatar: 'pi pi-user', name: 'John', surname: 'Smith', role: 'Admin' },
      { avatar: 'pi pi-user', name: 'Michael', surname: 'Green', role: 'User' },
      { avatar: 'pi pi-user', name: 'David', surname: 'White', role: 'Manager' },
      { avatar: 'pi pi-user', name: 'Emma', surname: 'Black', role: 'Support' },
      { avatar: 'pi pi-user', name: 'Alexander', surname: 'Yellow', role: 'Sales' },
    ]);

    const selectedUser = ref({});
    const isDialogVisible = ref(false);

    const roles = ref([
      { label: 'Admin', value: 'Admin' },
      { label: 'User', value: 'User' },
      { label: 'Manager', value: 'Manager' },
      { label: 'Support', value: 'Support' },
      { label: 'Sales', value: 'Sales' },
    ]);
    const confirm = useConfirm();

    const saveProfile = () => {
      // Logica per salvare il profilo utente
      console.log('Profilo salvato', user.value);
    };

    const changePassword = () => {
      // Logica per cambiare la password
      if (passwords.value.new === passwords.value.confirm) {
        console.log('Password cambiata', passwords.value);
      } else {
        console.error('Le password non coincidono');
      }
    };

    const getRoleSeverity = (role) => {
      switch (role) {
        case 'Admin':
          return 'contrast';
        case 'User':
          return 'info';
        case 'Manager':
          return 'danger';
        case 'Sales':
          return 'warn';
        case 'Support':
          return 'success';
        default:
          return 'secondary';
      }
    };

    const editUser = (user) => {
      selectedUser.value = { ...user };
      isDialogVisible.value = true;
    };

    const saveUser = () => {
      // Logica per salvare le modifiche dell'utente
      console.log('Utente salvato', selectedUser.value);
      isDialogVisible.value = false;
    };

    const confirmDeleteUser = (event) => {
      confirm.require({
        target: event.currentTarget,
        message: 'Are you sure you want to delete this user?',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Delete',
        rejectLabel: 'Cancel',
        acceptClass: 'p-button-danger',
        rejectClass: 'p-button-secondary',
        accept: () => {
          deleteUser();
        },
        reject: () => {
          console.log('Eliminazione annullata');
        }
      });
    };

    const deleteUser = () => {
      // Logica per eliminare l'utente
      users.value = users.value.filter(u => u !== selectedUser.value);
      console.log('Utente eliminato', selectedUser.value);
      isDialogVisible.value = false;
    };

    return {
      user,
      passwords,
      users,
      selectedUser,
      isDialogVisible,
      roles,
      saveProfile,
      changePassword,
      getRoleSeverity,
      editUser,
      saveUser,
      confirmDeleteUser
    };
  }
};
</script>

<style scoped>
.settings {
  padding: 20px;
}

.profile-settings,
.password-settings,
.other-settings,
.users-settings {
  margin-top: 20px;
}

h3 {
  margin-bottom: 10px;
}

.user-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px 10px; /* 20px per la spaziatura verticale, 10px per quella orizzontale */
}

.user-card {
  display: flex;
  align-items: center;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  width: 200px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.user-card:hover {
  background-color: #f0f0f0;
}

.user-info {
  margin-left: 10px;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.dialog-content {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.dialog-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
}


.password-settings {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.input-group label {
  font-weight: bold;
}

.small-button {
  width: 200px; /* Adjust the width as needed */
}
</style>
