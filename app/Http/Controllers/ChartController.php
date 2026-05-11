<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChartController extends Controller
{
    /**
     * Données pour le graphique des tickets par statut
     */
    public function ticketsByStatus()
    {
        $data = [
            'pending' => Ticket::where('status', 'pending')->count(),
            'serving' => Ticket::where('status', 'serving')->count(),
            'completed' => Ticket::where('status', 'completed')->count(),
            'canceled' => Ticket::where('status', 'canceled')->count(),
        ];

        return response()->json($data);
    }

    /**
     * Données pour le graphique des tickets par service
     */
    public function ticketsByService()
    {
        $services = Service::withCount(['tickets' => function ($query) {
            $query->where('status', 'completed');
        }])->get();

        $data = [
            'labels' => $services->pluck('name'),
            'data' => $services->pluck('tickets_count'),
        ];

        return response()->json($data);
    }

    /**
     * Données pour le graphique des tickets par jour (7 derniers jours)
     */
    public function ticketsByDay()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');
            $data[] = Ticket::whereDate('created_at', $date->toDateString())->count();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Données pour le graphique des temps d'attente moyens
     */
    public function averageWaitTime()
    {
        $services = Service::with(['tickets' => function ($query) {
            $query->where('status', 'completed');
        }])->get();

        $data = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($services as $service) {
            if ($service->tickets->count() > 0) {
                $avgWait = $service->tickets->avg('estimated_wait_time');
                $data['labels'][] = $service->name;
                $data['data'][] = round($avgWait, 1);
            }
        }

        return response()->json($data);
    }

    /**
     * Données pour le graphique des tickets par heure
     */
    public function ticketsByHour()
    {
        $data = [];
        $labels = [];

        for ($hour = 8; $hour <= 18; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $data[] = Ticket::whereRaw('HOUR(created_at) = ?', [$hour])->count();
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Statistiques générales pour le dashboard
     */
    public function dashboardStats()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'today_tickets' => Ticket::whereDate('created_at', today())->count(),
            'pending_tickets' => Ticket::where('status', 'pending')->count(),
            'serving_tickets' => Ticket::where('status', 'serving')->count(),
            'completed_today' => Ticket::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'avg_wait_time' => round(Ticket::whereIn('status', ['pending', 'serving'])
                ->avg('estimated_wait_time') ?: 0, 1),
            'total_services' => Service::where('active', 1)->count(),
            'completion_rate' => $this->calculateCompletionRate(),
        ];

        return response()->json($stats);
    }

    /**
     * Calculer le taux de completion
     */
    private function calculateCompletionRate()
    {
        $total = Ticket::count();
        if ($total === 0) return 0;

        $completed = Ticket::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }
}
