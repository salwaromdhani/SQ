<?php

namespace App\Http\Controllers;

use App\Mail\TicketArrivalNotification;
use App\Mail\TicketCreated;
use App\Mail\TicketTurnNotification;
use App\Models\Ticket;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    /**
     * Afficher la liste des tickets
     */
    public function index()
    {
        $tickets = Ticket::with('service')->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $services = Service::all();
        return view('tickets.create', compact('services'));
    }

    /**
     * Créer un nouveau ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255|regex:/^[\p{L}\p{M}\s\-\.\']+$/u', // Permet lettres accentuées, espaces, tirets, points, apostrophes
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'service_id' => 'required|exists:services,id',
        ]);

        // Générer un numéro de ticket unique
        $ticketNumber = 'TKT-' . strtoupper(substr(uniqid(), -6));

        // Calculer le temps d'attente estimé
        $waitingTickets = Ticket::where('service_id', $request->service_id)
            ->whereIn('status', ['pending', 'serving'])
            ->count();

        $estimatedTime = $waitingTickets * 5; // 5 minutes par ticket

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'service_id' => $validated['service_id'],
            'status' => 'pending',
            'priority' => 'normal',
            'estimated_wait_time' => $estimatedTime,
        ]);

        Mail::to($ticket->email)->send(new TicketCreated($ticket));

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Ticket créé avec succès ! Un email de confirmation a été envoyé.');
    }
    /**
     * Afficher un ticket spécifique
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('service');
        
        // Calculer la position dans la file
        $position = Ticket::where('service_id', $ticket->service_id)
            ->where('status', 'pending')
            ->where('created_at', '<=', $ticket->created_at)
            ->count();

        return view('tickets.show', compact('ticket', 'position'));
    }

    /**
     * Marquer un ticket comme "en cours de service"
     */
    public function serve(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'serving',
            'started_at' => now(),
        ]);

        Mail::to($ticket->email)->send(new TicketTurnNotification($ticket));

        return redirect()->route('admin.tickets.index')
            ->with('success', "Le ticket {$ticket->ticket_number} est maintenant en cours de service");
    }

    /**
     * Marquer un ticket comme "terminé"
     */
    public function complete(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.tickets.index')
            ->with('success', "Le ticket {$ticket->ticket_number} a été terminé");
    }

    /**
     * Supprimer un ticket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket supprimé');
    }

    /**
     * API pour vérifier le statut du ticket (utilisé par JavaScript)
     */
    public function apiStatus(Ticket $ticket)
    {
        $position = Ticket::where('service_id', $ticket->service_id)
            ->where('status', 'pending')
            ->where('created_at', '<=', $ticket->created_at)
            ->count();

        if ($ticket->shouldNotifyArrival()) {
            Mail::to($ticket->email)->send(new TicketArrivalNotification($ticket));
            $ticket->update(['arrival_notified_at' => now()]);
            $ticket->refresh();
        }

        return response()->json([
            'status' => $ticket->status,
            'position' => $position,
            'ticket_number' => $ticket->ticket_number,
            'estimated_wait_time' => $ticket->estimated_wait_time,
            'started_at' => $ticket->started_at,
            'completed_at' => $ticket->completed_at,
            'should_notify' => $ticket->shouldNotifyArrival(),
        ]);
    }

    /**
     * Afficher la file d'attente en temps réel
     */
    public function liveQueue()
    {
        $tickets = Ticket::with(['service'])
            ->whereIn('status', ['pending', 'serving'])
            ->latest()
            ->get();

        return view('tickets.queue', compact('tickets'));
    }
}
