<template>
  <div class="help-container">
    <Card>
      <template #title>
        <div class="p-d-flex p-jc-between p-ai-center">
          <span>Support tickets</span>
          <Button label="Create a ticket" icon="pi pi-plus" class="p-button-rounded p-button-success create-ticket" @click="showTicketDialog = true" />
        </div>
      </template>
      <template #content>
        <Accordion>
          <AccordionTab v-for="ticket in tickets" :key="ticket.id" :header="`Ticket #${ticket.id} - ${ticket.subject} - Date: ${ticket.date} - Status: ${ticket.status}`">
            <div>
              <div v-for="response in ticket.responses" :key="response.id" :class="{'response-customer': response.user === 'You', 'response-tech': response.user !== 'You', 'response-mirco': response.user === 'Mirco Carp'}" class="p-mb-2 response-message">
                <div class="p-d-flex p-ai-center user-ticket">
                  <Avatar icon="pi pi-user" class="p-mr-2" />
                  <strong class="user-name">{{ response.user }}</strong>
                </div>
                <p>{{ response.text }}</p>
              </div>
              <div class="p-field">
                <IftaLabel>
                  <Textarea id="description" v-model="value" rows="5" cols="10" style="resize: none; width: 100%;" />
                  <label for="description" style="font-size: 1rem;">Answer a text</label>
                </IftaLabel>
              </div>
              <div class="p-d-flex p-jc-end">
                <Button label="Send" class="p-ml-2" @click="addResponse(ticket)" />
                <span><i class="pi pi-paperclip"></i>Select file(s)</span>
              </div>
            </div>
          </AccordionTab>
        </Accordion>

        <Dialog header="Create an issue ticket" v-model:visible="showTicketDialog" :modal="true" :closable="true" class="custom-dialog" :style="{ width: '30vw' }">
          <form @submit.prevent="createTicket">
            <div class="flex flex-col gap-2">
              <label for="subject">Request Subject <span class="required">*</span></label>
              <InputText id="subject" v-model="newTicket.subject" aria-describedby="subject-help" required />
              <Message size="small" severity="secondary" variant="simple">This subject will be displayed in the list of tickets. Fill in this field with a meaningful name of the problem.</Message>
            </div>
            <div class="flex flex-col gap-2" style="margin-top: 20px;">
              <label for="description">Description <span class="required">*</span></label>
              <Textarea id="description" v-model="newTicket.description" rows="5" cols="30" required />
              <span><i class="pi pi-paperclip"></i>Select file(s)</span>
            </div>
            <div class="p-d-flex p-jc-center" style="margin-top: 20px;">
              <Button label="Send" type="submit" class="p-ml-2" />
            </div>
          </form>
        </Dialog>
      </template>
    </Card>
  </div>
</template>

<script>
import Card from 'primevue/card';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import Avatar from 'primevue/avatar';
import Textarea from 'primevue/textarea';
import IftaLabel from 'primevue/iftalabel';

export default {
  name: 'HelpPage',
  components: {
    Card,
    Button,
    DataTable,
    Column,
    Dialog,
    InputText,
    Accordion,
    AccordionTab,
    Avatar,
    Textarea,
    IftaLabel
  },
  data() {
    return {
      showTicketDialog: false,
      newTicket: {
        subject: '',
        description: ''
      },
      newResponse: '',
      tickets: [
        {
          id: 'N686298',
          subject: 'Verification',
          description: 'Can you please verify my ID?',
          date: '03/07/2025 5:24 PM',
          status: 'In process',
          responses: [
            {
              id: 1,
              user: 'Luca Bianchi',
              text: 'Can you please verify my ID?'
            },
            {
              id: 2,
              user: 'Technical support',
              text: 'Hi Mirco, your ID verification is currently being processed. You will receive an email with the result shortly. Thanks for your patience!'
            }
          ]
        },
        {
          id: 'N686769',
          subject: 'Lease payment issue',
          description: 'I am unable to make a payment for my current lease. Can you assist?',
          date: '03/12/2025 9:00 AM',
          status: 'In process',
          responses: [
            {
              id: 1,
              user: 'Luca Bianchi',
              text: 'I am unable to make a payment for my current lease. Can you assist?'
            },
            {
              id: 2,
              user: 'Technical support',
              text: 'Hi Giulia, we are currently reviewing the payment system. Please try again in a few minutes, or let us know if the issue persists.'
            },
            {
              id: 3,
              user: 'Luca Bianchi',
              text: 'Thank you! The payment went through successfully after trying again.'
            }
          ]
        },
        {
          id: 'N686872',
          subject: 'Car availability inquiry',
          description: 'I would like to know if the car I am interested in is still available for leasing.',
          date: '03/11/2025 4:32 PM',
          status: 'Open',
          responses: [
            {
              id: 1,
              user: 'Luca Bianchi',
              text: 'I would like to know if the car I am interested in is still available for leasing.'
            },
            {
              id: 2,
              user: 'Technical support',
              text: 'Hi Luca, the car you are interested in is still available for leasing. Would you like to proceed with the application?'
            },
            {
              id: 3,
              user: 'Luca Bianchi',
              text: 'Yes, I would like to proceed with the application. Please provide the next steps.'
            }
          ]
        }
      ]
    };
  },
  methods: {
    createTicket() {
      this.tickets.push({
        id: 'N' + Math.floor(Math.random() * 1000000),
        subject: this.newTicket.subject,
        description: this.newTicket.description,
        date: new Date().toLocaleString(),
        status: 'In process',
        responses: []
      });
      this.newTicket.subject = '';
      this.newTicket.description = '';
      this.showTicketDialog = false;
    },
    addResponse(ticket) {
      if (this.newResponse.trim()) {
        ticket.responses.push({
          id: ticket.responses.length + 1,
          user: 'You',
          text: this.newResponse
        });
        this.newResponse = '';
      }
    }
  }
};
</script>

<style scoped>
.help-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
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
</style>
