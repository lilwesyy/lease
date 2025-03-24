<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserimento di dati nella tabella tickets
        DB::table('tickets')->insert([
            [
                'id' => 1, // Explicitly set the ID
                'subject' => 'Problema con il login',
                'description' => 'Non riesco ad accedere al mio account.',
                'status' => 'Open',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2, // Explicitly set the ID
                'subject' => 'Errore durante il pagamento',
                'description' => 'Ricevo un errore quando provo a effettuare un pagamento.',
                'status' => 'In process',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Inserimento di dati nella tabella ticket_responses
        DB::table('ticket_responses')->insert([
            [
                'ticket_id' => 1,
                'user_name' => 'Admin',
                'text' => 'Stiamo esaminando il problema.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ticket_id' => 2,
                'user_name' => 'Support',
                'text' => 'Abbiamo bisogno di ulteriori dettagli sul problema.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}