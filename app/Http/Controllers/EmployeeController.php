<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Service;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    protected $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if (!$user || !$user->isEmployee()) {
                abort(403, 'Accès non autorisé. Espace employé uniquement.');
            }

            return $next($request);
        });

        $this->smsService = $smsService;
    }

    /**
     * Dashboard employé
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tickets en attente pour les services assignés à cet employé
        $pendingTickets = Ticket::with('service')
            ->where('status', 'pending')
            ->whereHas('service', function ($query) use ($user) {
                // Ici on pourrait filtrer par services assignés à l'employé
                // Pour l'instant, on montre tous les tickets en attente
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Statistiques
        $stats = [
            'pending_count' => $pendingTickets->count(),
            'serving_count' => Ticket::where('status', 'serving')->count(),
            'completed_today' => Ticket::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'avg_wait_time' => round(Ticket::whereIn('status', ['pending', 'serving'])
                ->avg('estimated_wait_time') ?: 0),
        ];

        return view('employee.dashboard', compact('pendingTickets', 'stats'));
    }

    /**
     * Appeler le prochain ticket
     */
    public function callNext(Request $request)
    {
        $serviceId = $request->input('service_id');

        $query = Ticket::where('status', 'pending');

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        $ticket = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$ticket) {
            return redirect()->back()->with('error', 'Aucun ticket en attente.');
        }

        // Marquer le ticket comme en cours de service
        $ticket->update([
            'status' => 'serving',
            'started_at' => now(),
        ]);

        // Créer un log
        $ticket->logs()->create([
            'action' => 'called',
            'description' => 'Ticket appelé par l\'employé ' . Auth::user()->name,
            'user_id' => Auth::id(),
        ]);

        // Envoyer notification SMS si le téléphone est disponible
        if ($ticket->phone_number) {
            $this->smsService->sendTicketCalledNotification($ticket, $ticket->phone_number);
        }

        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} appelé avec succès.");
    }

    /**
     * Terminer le service d'un ticket
     */
    public function completeService(Request $request, Ticket $ticket)
    {
        if ($ticket->status !== 'serving') {
            return redirect()->back()->with('error', 'Ce ticket n\'est pas en cours de service.');
        }

        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Créer un log
        $ticket->logs()->create([
            'action' => 'completed',
            'description' => 'Service terminé par l\'employé ' . Auth::user()->name,
            'user_id' => Auth::id(),
        ]);

        // Envoyer notification SMS si le téléphone est disponible
        if ($ticket->phone_number) {
            $this->smsService->sendTicketCompletedNotification($ticket, $ticket->phone_number);
        }

        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} marqué comme terminé.");
    }

    /**
     * Annuler un ticket
     */
    public function cancelTicket(Request $request, Ticket $ticket)
    {
        $reason = $request->input('reason', 'Annulé par l\'employé');

        $ticket->update(['status' => 'canceled']);

        // Créer un log
        $ticket->logs()->create([
            'action' => 'canceled',
            'description' => $reason,
            'user_id' => Auth::id(),
        ]);

        // Envoyer notification SMS si le téléphone est disponible
        if ($ticket->phone_number) {
            $this->smsService->sendTicketCanceledNotification($ticket, $ticket->phone_number);
        }

        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} annulé.");
    }

    /**
     * Voir les tickets en cours
     */
    public function servingTickets()
    {
        $tickets = Ticket::with('service')
            ->where('status', 'serving')
            ->orderBy('started_at', 'desc')
            ->get();

        return view('employee.serving', compact('tickets'));
    }
}
