<template>
    <div class="settings">
      <TabView>
        <TabPanel header="Profile">
          <div class="profile-settings">
            <h3>Profile</h3>
            <InputText v-model="user.firstName" placeholder="First Name" class="input-field" />
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
            <ProgressSpinner v-if="loading" />
            <div v-else class="user-cards">
              <div v-for="user in users" :key="user.id" class="user-card" @click="editUser(user)">
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
              <Select v-model="selectedUser.role" :options="roles" optionLabel="label" optionValue="value" placeholder="Select Role" />
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
  import { ref, onMounted } from 'vue';
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
  import ProgressSpinner from 'primevue/progressspinner';
  // import ConfirmPopup from 'primevue/confirmpopup';
  import { useConfirm } from "primevue/useconfirm";
  import axios from 'axios';
  import { useToast } from 'primevue/usetoast';

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
      ProgressSpinner,
      // ConfirmPopup
    },
    setup() {
      const toast = useToast();
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

      const users = ref([]);
      const loading = ref(true);
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

      // Funzione per caricare gli utenti dal backend
      const fetchUsers = async () => {
  try {
    loading.value = true;
    const response = await axios.post('/roles');

    if (response.data.status === 'success') {
      users.value = response.data.data.map(user => {
        const rawRole = user.roles.length > 0 ? user.roles[0] : 'User';
        const formattedRole = rawRole.charAt(0).toUpperCase() + rawRole.slice(1).toLowerCase();

        return {
          avatar: 'pi pi-user',
          name: user.name.split(' ')[0] || user.name,
          surname: user.name.split(' ').slice(1).join(' ') || '',
          email: user.email,
          id: user.id,
          role: formattedRole
        };
      });
    } else {
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load users', life: 3000 });
    }
  } catch (error) {
    console.error('Error fetching users:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to load users', life: 3000 });
  } finally {
    loading.value = false;
  }
};

      // Carica gli utenti quando il componente viene montato
      onMounted(() => {
        fetchUsers();
      });

      const saveProfile = () => {
        // Logica per salvare il profilo utente
        console.log('Profilo salvato', user.value);
        toast.add({ severity: 'success', summary: 'Success', detail: 'Profile saved successfully', life: 3000 });
      };

      const changePassword = () => {
        // Logica per cambiare la password
        if (passwords.value.new === passwords.value.confirm) {
          console.log('Password cambiata', passwords.value);
          toast.add({ severity: 'success', summary: 'Success', detail: 'Password changed successfully', life: 3000 });
          // Reset password fields
          passwords.value = {
            current: '',
            new: '',
            confirm: ''
          };
        } else {
          console.error('Le password non coincidono');
          toast.add({ severity: 'error', summary: 'Error', detail: 'Passwords do not match', life: 3000 });
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

      const saveUser = async () => {
        try {
          // Qui puoi implementare una chiamata API per aggiornare l'utente e il suo ruolo
          // await axios.post('/api/update-user-role', {
          //   userId: selectedUser.value.id,
          //   role: selectedUser.value.role
          // });

          // Aggiorna l'utente localmente (simulato)
          const index = users.value.findIndex(u => u.id === selectedUser.value.id);
          if (index !== -1) {
            users.value[index] = { ...selectedUser.value };
          }

          toast.add({ severity: 'success', summary: 'Success', detail: 'User updated successfully', life: 3000 });
          isDialogVisible.value = false;

          // Ricarica gli utenti per visualizzare le modifiche
          // fetchUsers(); // Uncommenti quando implementi la vera API
        } catch (error) {
          console.error('Error saving user:', error);
          toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update user', life: 3000 });
        }
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

      const deleteUser = async () => {
        try {
          // Qui puoi implementare una chiamata API per eliminare l'utente
          // await axios.delete(`/api/users/${selectedUser.value.id}`);

          // Elimina l'utente localmente
          users.value = users.value.filter(u => u.id !== selectedUser.value.id);

          toast.add({ severity: 'success', summary: 'Success', detail: 'User deleted successfully', life: 3000 });
          isDialogVisible.value = false;
        } catch (error) {
          console.error('Error deleting user:', error);
          toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete user', life: 3000 });
        }
      };

      return {
        user,
        passwords,
        users,
        loading,
        selectedUser,
        isDialogVisible,
        roles,
        saveProfile,
        changePassword,
        getRoleSeverity,
        editUser,
        saveUser,
        confirmDeleteUser,
        fetchUsers
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
    margin-top: 20px;
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

  .input-field {
    margin-bottom: 15px;
    width: 100%;
    max-width: 400px;
  }

  .save-button {
    margin-top: 10px;
  }
  </style>
