<?php

namespace App\Http\Controllers;

use App\Mail\TicketArrivalNotification;
use App\Mail\TicketCreated;
use App\Mail\TicketTurnNotification;
use App\Models\Ticket;
use App\Models\Service;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    protected $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        $this->smsService = $smsService;
    }
    /**
     * Afficher la liste des tickets
     */
    public function index(Request $request)
    {
        $query = Ticket::with('service')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $statusMap = [
                'pending' => 'pending',
                'serving' => 'serving',
                'completed' => 'completed',
                'canceled' => 'canceled',
            ];
            if (isset($statusMap[$status])) {
                $query->where('status', $statusMap[$status]);
            }
        }

        $tickets = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'serving' => Ticket::where('status', 'serving')->count(),
            'completed' => Ticket::where('status', 'completed')->count(),
        ];

<<<<<<< HEAD
        return view('tickets.index', compact('tickets', 'stats'));
=======
        $view = str_contains($request->route()->getName(), 'admin.') ? 'admin.tickets.index' : 'tickets.index';

        return view($view, compact('tickets', 'stats'));
>>>>>>> feature/agents-module
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,serving,completed,canceled',
            'priority' => 'required|in:normal,urgent',
        ]);

        $ticket->update($validated);

        return redirect()->route('admin.tickets.index')
            ->with('success', "Le ticket {$ticket->ticket_number} a été mis à jour.");
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

<<<<<<< HEAD
        // Calculer le temps d'attente estimé
        $pendingTickets = Ticket::where('service_id', $request->service_id)
            ->where('status', 'pending')
            ->count();

        $hasActiveServing = Ticket::where('service_id', $request->service_id)
            ->where('status', 'serving')
            ->exists();

        $estimatedTime = ($pendingTickets + ($hasActiveServing ? 1 : 0)) * 5; // 5 minutes par étape de service

=======
        // Créer le ticket avec une estimation provisoire, puis recalculer la file entière
>>>>>>> feature/agents-module
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'],
            'service_id' => $validated['service_id'],
            'status' => 'pending',
            'priority' => 'normal',
            'estimated_wait_time' => 0,
        ]);

        $this->recalculateEstimatedWaitTimes($ticket->service_id);
        $ticket->refresh();

        Mail::to($ticket->email)->send(new TicketCreated($ticket));

        // Envoyer notification SMS si le téléphone est fourni
        if ($ticket->phone) {
            $this->smsService->sendTicketCreatedNotification($ticket, $ticket->phone);
        }

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

        $view = str_contains(request()->route()->getName(), 'admin.') ? 'admin.tickets.show' : 'tickets.show';

        return view($view, compact('ticket', 'position'));
    }

    /**
     * Marquer un ticket comme "en cours de service"
     */
    public function serve(Ticket $ticket)
    {
        Mail::to($ticket->email)->send(new TicketTurnNotification($ticket));

        $serviceId = $ticket->service_id;
        $ticket->delete();
        $this->recalculateEstimatedWaitTimes($serviceId);

        return redirect()->route('admin.tickets.index')
            ->with('success', "Le ticket {$ticket->ticket_number} a été notifié et supprimé de la file.");
    }

    /**
     * Marquer un ticket comme "terminé"
     */
    public function complete(Ticket $ticket)
    {
        $serviceId = $ticket->service_id;
        $ticket->delete();
        $this->recalculateEstimatedWaitTimes($serviceId);

        return redirect()->route('admin.tickets.index')
            ->with('success', "Le ticket {$ticket->ticket_number} a été supprimé de la base de données.");
    }

    /**
     * Supprimer un ticket
     */
    public function destroy(Ticket $ticket)
    {
        $serviceId = $ticket->service_id;
        $ticket->delete();
        $this->recalculateEstimatedWaitTimes($serviceId);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket supprimé');
    }

    protected function recalculateEstimatedWaitTimes(int $serviceId): void
    {
        $pendingTickets = Ticket::where('service_id', $serviceId)
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        foreach ($pendingTickets as $index => $pendingTicket) {
            $pendingTicket->update([
                'estimated_wait_time' => 5 * ($index + 1),
            ]);
        }
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
