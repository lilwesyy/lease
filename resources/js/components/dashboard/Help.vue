<template>
  <div class="help-container">
    <Card>
      <template #title>Centro Assistenza</template>
      <template #content>
        <p>Benvenuto nel centro assistenza del CRM di autonoleggio. Qui troverai risposte alle domande più frequenti e supporto per l'utilizzo della piattaforma.</p>
        
        <Accordion>
          <AccordionTab header="Gestione Clienti">
            <p>Per aggiungere un nuovo cliente, vai alla sezione "Clienti" e clicca su "Aggiungi Cliente". Compila i campi richiesti e salva.</p>
          </AccordionTab>
          
          <AccordionTab header="Gestione Prenotazioni">
            <p>Le prenotazioni possono essere visualizzate e modificate nella sezione "Prenotazioni". Per creare una nuova prenotazione, clicca su "Nuova Prenotazione" e inserisci i dettagli richiesti.</p>
          </AccordionTab>
          
          <AccordionTab header="Fatturazione e Pagamenti">
            <p>Le fatture possono essere generate automaticamente al termine del noleggio. Accedi alla sezione "Fatture" per visualizzare, stampare o inviare le fatture ai clienti.</p>
          </AccordionTab>
          
          <AccordionTab header="Supporto Tecnico">
            <p>Se riscontri problemi tecnici, puoi contattare il supporto inviando un'email a <a href="mailto:support@crm-noleggio.com">support@crm-noleggio.com</a>.</p>
          </AccordionTab>
        </Accordion>
      </template>
    </Card>
  </div>
</template>

<script>
import Card from 'primevue/card';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';

export default {
  name: 'HelpPage',
  components: {
    Card,
    Accordion,
    AccordionTab
  }
};
</script>

<style scoped>
.help-container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
}
</style>
