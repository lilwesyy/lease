<template>
  <div class="flex items-center justify-center min-h-screen">
    <div class="flex flex-col items-center w-full sm:w-80">
      <div class="text-center mb-8">
        <!-- <img src="/public/img/logo.png" alt="logo" class="w-20 mx-auto" /> -->
        <h1 class="text-2xl font-bold">Welcome Back</h1>
        <p class="text-gray-600">Please log in to your account</p>
      </div>

      <form @submit.prevent="onFormSubmit" class="flex flex-col gap-4 w-full">
        <div class="flex flex-col gap-1">
          <InputText
            v-model="initialValues.username"
            name="username"
            type="text"
            placeholder="Email"
            fluid
            @input="validateField('username')"
          />
          <Message v-if="formErrors.username && formErrors.username.length > 0" severity="error" size="small" variant="simple">
            <ul class="my-0 px-0 flex flex-col gap-1">
              <li v-for="(error, index) of formErrors.username" :key="index">{{ error }}</li>
            </ul>
          </Message>
        </div>

        <div class="flex flex-col gap-1">
          <Password
            v-model="initialValues.password"
            name="password"
            placeholder="Password"
            :feedback="false"
            toggleMask
            fluid
            @input="validateField('password')"
          />
          <Message v-if="formErrors.password && formErrors.password.length > 0" severity="error" size="small" variant="simple">
            <ul class="my-0 px-0 flex flex-col gap-1">
              <li v-for="(error, index) of formErrors.password" :key="index">{{ error }}</li>
            </ul>
          </Message>
        </div>

        <div class="flex items-center justify-between">
          <Checkbox v-model="checked" binary />
          <label for="rememberMe" class="ml-2"> Remember me? </label>
          <a href="#" class="text-sm text-blue-500 hover:underline forgot-password">Forgot Password?</a>
        </div>

        <Button type="submit" severity="secondary" label="Log In" />
        <Message v-if="formErrors.general && formErrors.general.length > 0" severity="error" size="small" variant="simple">
          <ul class="my-0 px-0 flex flex-col gap-1">
            <li v-for="(error, index) of formErrors.general" :key="index">{{ error }}</li>
          </ul>
        </Message>
      </form>
    </div>
  </div>
</template>

<script>
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import { Form } from '@primevue/forms';
import Password from 'primevue/password';
import Message from 'primevue/message';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  name: 'Login',
  components: {
    InputText,
    Button,
    Checkbox,
    Form,
    Password,
    Message
  },
  data() {
    return {
      initialValues: { username: '', password: '' },
      formErrors: {
        username: [],
        password: [],
        general: []
      },
      checked: false
    };
  },
  methods: {
    validateField(field) {
      console.log(`Validating field: ${field}`);
      this.formErrors[field] = [];

      if (field === 'username') {
        if (!this.initialValues.username) {
          this.formErrors[field].push('Username is required');
        }
      }

      if (field === 'password') {
        if (!this.initialValues.password) {
          this.formErrors[field].push('Password is required');
        } else if (this.initialValues.password.length < 6) {
          this.formErrors[field].push('Password must be at least 6 characters');
        }
      }
    },

    async onFormSubmit(event) {
      console.log('Form submit triggered');
      this.validateField('username');
      this.validateField('password');

      if (this.formErrors.username.length === 0 && this.formErrors.password.length === 0) {
        try {
          console.log('Sending request...');
          const response = await axios.post('/login', {
            email: this.initialValues.username,
            password: this.initialValues.password,
          }, { withCredentials: true }); // Assicurati di inviare i cookie

          console.log('Login successful:', response.data);
          // Salva il token o gestisci lo stato di autenticazione
          this.$router.push('/dashboard'); // Reindirizza l'utente alla dashboard
        } catch (error) {
          console.error('Login failed:', error.response ? error.response.data : error);
          this.formErrors.general = ['Login failed. Please check your credentials and try again.'];
        }
      } else {
        console.log('Form validation failed:', this.formErrors);
      }
    }
  }
};
</script>

<style scoped>
.min-h-screen {
  min-height: 90vh;
}
.forgot-password {
  margin-left: auto;
}
.ml-2 {
  margin-left: 0.5rem;
}
</style>