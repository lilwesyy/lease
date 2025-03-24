<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTicket()
    {
        try {
            $tickets = Ticket::with(['user', 'responses'])->get();
            return response()->json($tickets, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Errore durante il recupero dei ticket'], 500);
        }
    }

    public function createTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::create($validated);
        return response()->json($ticket, 201);
    }

    public function editTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'subject' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:Open,In process,Resolved,Closed',
        ]);

        $ticket->update($validated);
        return response()->json($ticket);
    }

    public function deleteTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully']);
    }

    public function respondTicket(Request $request, $ticketId)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $response = TicketResponse::create([
            'ticket_id' => $ticketId,
            'user_name' => $validated['user_name'],
            'text' => $validated['text'],
        ]);

        return response()->json($response, 201);
    }
}