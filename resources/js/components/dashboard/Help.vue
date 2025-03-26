<template>
  <h1 class="font-bold text-3xl">Tickets</h1>
  <Breadcrumb :model="items" class="custom-breadcrumb" />
  <div class="help-container">
    <Card>
      <template #title>
        <div class="p-d-flex p-jc-between p-ai-center">
          <Button label="Create a ticket" icon="pi pi-plus" class="create-ticket" @click="showTicketDialog = true" />
        </div>
      </template>
      <template #content>
        <div v-if="tickets.length === 0" class="no-tickets-message">
          <p>No tickets available. Create a new ticket to get started.</p>
        </div>
        <div v-else>
          <Accordion>
            <AccordionTab v-for="ticket in tickets" :key="ticket.id" :header="`Ticket #${ticket.id} - ${ticket.subject} - Date: ${ticket.date} - Status: ${ticket.status}`">
              <div>
                <!-- Initial ticket description message -->
                <div class="p-mb-2 response-message response-customer">
                  <div class="p-d-flex p-ai-center user-ticket">
                    <strong class="user-name mr-2">{{ ticket.user?.name || storedUsername || 'Unknown User' }}</strong>
                    <Avatar icon="pi pi-user" class="p-mr-2" />
                  </div>
                  <p>{{ ticket.description }}</p>
                </div>
                
                <!-- Subsequent responses -->
                <div v-for="response in ticket.responses" :key="response.id" 
                     :class="[
                       'p-mb-2 response-message', 
                       response.user_name === ticket.user?.name ? 'response-customer' : 'response-tech'
                     ]">
                  <div class="p-d-flex p-ai-center user-ticket">
                    <Avatar icon="pi pi-user" class="p-mr-2" />
                    <strong class="user-name">{{ response.user_name }}</strong>
                  </div>
                  <p>{{ response.text }}</p>
                </div>
                <div class="p-field">
                  <IftaLabel>
                    <Textarea :id="`response-${ticket.id}`" v-model="responseInputs[ticket.id]" rows="5" cols="10" style="resize: none; width: 100%;" />
                    <label :for="`response-${ticket.id}`" style="font-size: 1rem;">Answer a text</label>
                  </IftaLabel>
                </div>
                <div class="p-d-flex p-jc-end">
                  <Button label="Send" class="p-ml-2" @click="addResponse(ticket)" />
                  <span><i class="pi pi-paperclip"></i>Select file(s)</span>
                </div>
              </div>
            </AccordionTab>
          </Accordion>
        </div>

        <Dialog header="Create an issue ticket" v-model:visible="showTicketDialog" :modal="true" :closable="true" class="custom-dialog" :style="{ width: '30vw' }">
          <form @submit.prevent="createTicket">
            <div class="flex flex-col gap-2">
              <label for="ticket-subject">Request Subject <span class="required">*</span></label>
              <InputText id="ticket-subject" v-model="newTicket.subject" aria-describedby="subject-help" required />
              <Message size="small" severity="secondary" variant="simple">This subject will be displayed in the list of tickets. Fill in this field with a meaningful name of the problem.</Message>
            </div>
            <div class="flex flex-col gap-2" style="margin-top: 20px;">
              <label for="ticket-description">Description <span class="required">*</span></label>
              <Textarea id="ticket-description" v-model="newTicket.description" rows="5" cols="30" required />
              <span><i class="pi pi-paperclip"></i>Select file(s)</span>
            </div>
            <div class="p-d-flex p-jc-center" style="margin-top: 20px;">
              <Button label="Send" @click="createTicket(ticket)" class="p-ml-2"/>
            </div>
          </form>
        </Dialog>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import axios from 'axios';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Avatar from 'primevue/avatar';
import Textarea from 'primevue/textarea';
import IftaLabel from 'primevue/iftalabel';
import Message from 'primevue/message';
import Breadcrumb from 'primevue/breadcrumb';

const showTicketDialog = ref(false);
const newTicket = reactive({
  subject: '',
  description: ''
});
const responseInputs = reactive({});  // Object to store responses for each ticket
const tickets = ref([]);
const loading = ref(false);
const error = ref(null);
const currentUser = ref(null);
const storedUsername = localStorage.getItem('name');

const items = ref([
  { label: 'Dashboard', url: '/dashboard/home', icon: 'pi pi-home' },
  { label: 'Tickets', url: '/dashboard/help' }
]);

// Funzione per recuperare l'utente corrente
const fetchCurrentUser = async () => {
  try {
    // Replace with your actual endpoint to get current user
    const response = await axios.get('/api/user');
    currentUser.value = response.data;
  } catch (err) {
    console.error('Errore durante il recupero dell\'utente:', err);
    // Use a default user if we can't get the current one
    currentUser.value = { id: 4, name: 'Current User' };
  }
};

// Funzione per recuperare i ticket tramite Axios
const fetchTickets = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await axios.post('/tickets');
    tickets.value = response.data.map(ticket => ({
      ...ticket,
      hasResponses: (ticket.responses?.length || 0) > 0,
      status: ticket.status || 'Open',
      date: new Date(ticket.created_at).toLocaleString(),
      responses: ticket.responses || []
    }));
    
    // Initialize response inputs for each ticket
    tickets.value.forEach(ticket => {
      responseInputs[ticket.id] = '';
    });
    
    // console.log('Tickets loaded:', tickets.value);
  } catch (err) {
    console.error('Errore durante il recupero dei ticket:', err);
    error.value = 'Impossibile caricare i ticket. Riprova più tardi.';
  } finally {
    loading.value = false;
  }
};

// Funzione per creare un nuovo ticket
const createTicket = async () => {
  try {
    const userId = localStorage.getItem('id');
    
    if (!userId) {
      // Show a login prompt or notification instead of throwing an error
      console.warn('User not authenticated. Please log in first.');
      return;
    }

    if (!newTicket.subject || !newTicket.description) {
      throw new Error('Please fill in all required fields');
    }

    const response = await axios.post('/tickets/create', {
      subject: newTicket.subject,
      description: newTicket.description,
      user_id: userId,
      status: 'Open'
    });
    
    const createdTicket = {
      ...response.data,
      hasResponses: false,
      date: new Date().toLocaleString(),
      responses: [],
      user: currentUser.value,
      status: 'Open'
    };
    
    tickets.value.unshift(createdTicket); // Add to beginning of array
    responseInputs[createdTicket.id] = '';
    
    // Reset form and close dialog
    newTicket.subject = '';
    newTicket.description = '';
    showTicketDialog.value = false;

  } catch (err) {
    console.error('Error creating ticket:', err);
  }
};

// Funzione per aggiungere una risposta
// ...existing code...
const addResponse = async (ticket) => {
  const responseText = responseInputs[ticket.id];
  
  if (responseText && responseText.trim()) {
    try {
      const response = await axios.post(`/tickets/${ticket.id}/respond`, {
        text: responseText,
        user_name: 'Technical Support'
      });
      
      // Add the response to the UI with the correct format
      if (response.data) {
        const newResponse = {
          id: response.data.id,
          user_name: currentUser.value.name,
          text: responseText,
          created_at: new Date().toISOString()
        };
        
        // Update the ticket's responses array
        ticket.responses = [...(ticket.responses || []), newResponse];
        
        // Clear the input field for this specific ticket
        responseInputs[ticket.id] = '';
      }
    } catch (err) {
      console.error('Error adding response:', err);
      // You might want to add error handling here, such as showing a toast message
    }
  }
};
// ...existing code...

// Recupera l'utente corrente e i ticket quando il componente viene montato
onMounted(async () => {
  await fetchCurrentUser();
  await fetchTickets();
});
</script>

<style scoped>
.help-container {
  margin: auto;
}
.create-ticket {
  margin-left: 2%;
}
.response-customer {
  text-align: right;
}
.response-tech {
  text-align: left;
}
.response-customer .p-d-flex {
  justify-content: flex-end;
}
.response-tech .p-d-flex {
  justify-content: flex-start;
}
.response-message {
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 10px;
}
.user-name {
  margin-left: 10px; /* Adjust the margin as needed */
}
.custom-dialog .p-d-flex.p-jc-center {
  display: flex;
  justify-content: center;
  width: 100%;
}
.required {
  color: red;
}
.p-d-flex.p-jc-center {
  justify-content: center;
}
.pi-paperclip{
  margin-right: 1%;
  margin-left: 1%;
}

.no-tickets-message {
  text-align: center;
  font-size: 1.2rem;
  color: #666;
  margin: 20px 0;
}
</style>