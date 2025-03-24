<template>
    <div class="settings">
        <h1 v-if="!isViewMode" class="font-bold text-3xl">Settings</h1>
        <Breadcrumb v-if="!isViewMode" :model="items" class="custom-breadcrumb" />

        <Card>
            <template #content>
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
                            <Button label="Add User" @click="openAddUserDialog" style="margin-bottom: 1rem;" />

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
            </template>
        </Card>

        <Dialog
    header="User"
    v-model:visible="isUserDialogVisible"
    :modal="true"
    :closable="false"
>
    <div class="dialog-content">
        <div class="field">
            <label>First Name</label>
            <InputGroup>
                <InputGroupAddon>
                    <i class="pi pi-user"></i>
                </InputGroupAddon>
                <InputText v-model="dialogUser.firstName" placeholder="First Name" />
            </InputGroup>
        </div>

        <div class="field">
            <label>Last Name</label>
            <InputGroup>
                <InputGroupAddon>
                    <i class="pi pi-user"></i>
                </InputGroupAddon>
                <InputText v-model="dialogUser.lastName" placeholder="Last Name" />
            </InputGroup>
        </div>

        <div class="field">
            <label>Email</label>
            <InputGroup>
                <InputGroupAddon>
                    <i class="pi pi-envelope"></i>
                </InputGroupAddon>
                <InputText v-model="dialogUser.email" placeholder="Email" />
            </InputGroup>
        </div>

        <div class="field">
            <label>Role</label>
            <InputGroup>
                <InputGroupAddon>
                    <i class="pi pi-briefcase"></i>
                </InputGroupAddon>
                <Select
                    v-model="dialogUser.role"
                    :options="roles"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select Role"
                />
            </InputGroup>
        </div>

        <div class="dialog-buttons">
            <Button
                :label="isEditing ? 'Save' : 'Add'"
                icon="pi pi-check"
                @click="isEditing ? saveExistingUser() : addNewUser()"
            />
            <Button
                label="Cancel"
                icon="pi pi-times"
                @click="isUserDialogVisible = false"
                class="p-button-secondary"
            />
            <Button
                v-if="isEditing"
                label="Delete"
                icon="pi pi-trash"
                class="p-button-danger"
                @click="confirmDeleteUser"
            />
        </div>
    </div>
</Dialog>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
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
import Card from 'primevue/card';
import { useConfirm } from "primevue/useconfirm";
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import Breadcrumb from 'primevue/breadcrumb';
import ConfirmDialog from 'primevue/confirmdialog';

// Defining props if needed
const isViewMode = ref(false);

// Breadcrumb items
const items = ref([
    { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
    { label: 'Settings', url: '/dashboard/settings' }
]);

// Setup toast and confirm hooks
const toast = useToast();
const confirm = useConfirm();

const isUserDialogVisible = ref(false)
const isEditing = ref(false)
const dialogUser = ref({ firstName: '', lastName: '', email: '', role: '' })

const openAddUserDialog = () => {
    isEditing.value = false
    dialogUser.value = { firstName: '', lastName: '', email: '', role: '' }
    isUserDialogVisible.value = true
}

// User data
const user = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: ''
});

// Password management
const passwords = ref({
    current: '',
    new: '',
    confirm: ''
});

const isAddUserDialogVisible = ref(false)
const newUser = ref({ firstName: '', lastName: '', email: '', role: '' })

// Users management
const users = ref([]);
const loading = ref(true);
const selectedUser = ref({});
const isDialogVisible = ref(false);

// Roles dropdown options
const roles = ref([
    { label: 'Administrator', value: 'Administrator' },
    { label: 'User', value: 'User' },
    { label: 'Manager', value: 'Manager' },
    { label: 'Support', value: 'Support' },
    { label: 'Sales', value: 'Sales' },
]);

const addUser = async () => {
    try {
        const response = await axios.post('/user/create', {
            name: newUser.value.firstName + ' ' + newUser.value.lastName,
            email: newUser.value.email,
            role: newUser.value.role
        })
        if (response.data.status === 'success') {
            toast.add({ severity: 'success', summary: 'Success', detail: 'User added successfully', life: 3000 })
            await fetchUsers()
            isAddUserDialogVisible.value = false
        } else {
            throw new Error(response.data.message || 'Failed to create user')
        }
    } catch (error) {
        console.error('Error creating user:', error)
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create user', life: 3000 })
    }
}

const addNewUser = async () => {
    try {
        // POST to create user
        const response = await axios.post('/user/create', {
            name: dialogUser.value.firstName + ' ' + dialogUser.value.lastName,
            email: dialogUser.value.email,
            role: dialogUser.value.role
        });
        if (response.data.status === 'success') {
            toast.add({ severity: 'success', summary: 'Success', detail: 'User added successfully', life: 3000 });
            
            // Refresh users and close the dialog
            await fetchUsers();
            isUserDialogVisible.value = false;
        } else {
            throw new Error(response.data.message || 'Failed to create user');
        }
    } catch (error) {
        console.error('Error creating user:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create user', life: 3000 });
    }
};

const saveExistingUser = async () => {
    try {
        const response = await axios.post('/roles/update', {
            user_id: selectedUser.value.id,
            role: dialogUser.value.role
        });
        if (response.data.status === 'success') {
            // Optionally show success message
            toast.add({ severity: 'success', summary: 'Success', detail: 'User updated successfully', life: 3000 });

            // Refresh users and close dialog
            await fetchUsers();
            isUserDialogVisible.value = false;
        } else {
            throw new Error(response.data.message || 'Failed to update user');
        }
    } catch (error) {
        console.error('Error updating user:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update user', life: 3000 });
    }
}

// Fetch users from API
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

// Save profile changes
const saveProfile = () => {
    console.log('Profilo salvato', user.value);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Profile saved successfully', life: 3000 });
};

// Change password
const changePassword = () => {
    if (passwords.value.new === passwords.value.confirm) {
        console.log('Password cambiata', passwords.value);
        toast.add({ severity: 'success', summary: 'Success', detail: 'Password changed successfully', life: 3000 });
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

// Get severity for role tag
const getRoleSeverity = (role) => {
    switch (role) {
        case 'Administrator':
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

// Edit user
const editUser = (user) => {
    isEditing.value = true
    dialogUser.value = {
        firstName: user.name,
        lastName: user.surname,
        email: user.email,
        role: user.role
    }
    selectedUser.value = { ...user } // store full info if needed
    isUserDialogVisible.value = true
}

// Save user changes
const saveUser = async () => {
    try {
        const response = await axios.post('/roles/update', {
            user_id: selectedUser.value.id,
            role: selectedUser.value.role
        });

        if (response.data.status === 'success') {
            const index = users.value.findIndex(u => u.id === selectedUser.value.id);
            if (index !== -1) {
                users.value[index] = { ...selectedUser.value };
            }

            toast.add({ severity: 'success', summary: 'Success', detail: 'User role updated successfully', life: 3000 });
            isDialogVisible.value = false;

            // Refresh the users list to ensure we have the latest data
            await fetchUsers();
        } else {
            throw new Error(response.data.message || 'Failed to update user role');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: error.response?.data?.message || 'Failed to update user role',
            life: 3000
        });
    }
};

// Confirm user deletion
const confirmDeleteUser = (event) => {
    confirm.require({
        target: event.currentTarget,
        header: 'Confirm Deletion',
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

// Delete user
const deleteUser = async () => {
    try {
        const response = await axios.delete(`/user/delete/${selectedUser.value.id}`);

        if (response.data.status === 'success') {
            // Refresh the user list
            await fetchUsers();

            // Close the dialog
            isUserDialogVisible.value = false;

            // Show success message
            toast.add({ severity: 'success', summary: 'Success', detail: 'User deleted successfully', life: 3000 });
        } else {
            throw new Error(response.data.message || 'Failed to delete user');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete user', life: 3000 });
    }
};

// Load data on component mount
onMounted(() => {
    fetchUsers();
});
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
    gap: 20px 10px;
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

.input-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.input-group label {
    font-weight: bold;
}

.small-button {
    width: 200px;
}

.input-field {
    margin-bottom: 15px;
    width: 100%;
    max-width: 400px;
}

.save-button {
    margin-top: 10px;
}

.p-card{
    box-shadow: none!important;
}
</style>