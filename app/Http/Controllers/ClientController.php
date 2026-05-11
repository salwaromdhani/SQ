<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $smsService;

    public function __construct(SmsNotificationService $smsService)
    {
        $this->middleware('auth');
        $this->smsService = $smsService;
    }

    /**
     * Dashboard client
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tickets actifs de l'utilisateur (par email)
        $activeTickets = Ticket::where('email', $user->email)
            ->whereIn('status', ['pending', 'serving'])
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques personnelles
        $stats = [
            'total_tickets' => Ticket::where('email', $user->email)->count(),
            'completed_tickets' => Ticket::where('email', $user->email)->where('status', 'completed')->count(),
            'avg_wait_time' => round(Ticket::where('email', $user->email)->avg('estimated_wait_time') ?: 0),
            'current_position' => $this->getCurrentPosition($user->email),
        ];

        // Historique récent
        $recentTickets = Ticket::where('email', $user->email)
            ->with('service')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('client.dashboard', compact('activeTickets', 'stats', 'recentTickets'));
    }

    /**
     * Historique complet des tickets
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = Ticket::where('email', $user->email)->with('service');

        // Filtrage par statut
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filtrage par service
        if ($request->service_id) {
            $query->where('service_id', $request->service_id);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('client.history', compact('tickets'));
    }

    /**
     * Profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        return view('client.profile', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Calculer la position actuelle dans la file
     */
    private function getCurrentPosition($email)
    {
        $userTickets = Ticket::where('email', $email)
            ->whereIn('status', ['pending', 'serving'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$userTickets) {
            return null;
        }

        // Compter combien de tickets sont devant
        $position = Ticket::where('service_id', $userTickets->service_id)
            ->where('status', 'pending')
            ->where('created_at', '<', $userTickets->created_at)
            ->count();

        return $position + 1; // +1 car on compte à partir de 1
    }
}
