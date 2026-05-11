@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Administration</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Tableau de bord administrateur</h1>
        <p class="mt-3 text-slate-600">Toutes les fonctions de gestion sont accessibles depuis ici. Suivez les tickets, gérez les services, les agents et consultez l'historique.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Fonctionnalités de l'administrateur</h2>
            <ul class="mt-6 space-y-4 text-slate-700">
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">1</span>
                    <span>Se connecter à l'espace admin sécurisé.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">2</span>
                    <span>Gérer les services disponibles et leur activation.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">3</span>
                    <span>Gérer les agents et leurs affectations aux services.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">4</span>
                    <span>Gérer les tickets en attente, en cours ou terminés.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">5</span>
                    <span>Appeler un ticket pour le passer en service.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">6</span>
                    <span>Clôturer un ticket une fois le service terminé.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">7</span>
                    <span>Consulter l'historique des actions et des modifications de tickets.</span>
                </li>
            </ul>
        </div>

        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Statistiques et Graphiques</h2>

            <!-- Graphiques -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Graphique des tickets par statut -->
                <div>
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Tickets par statut</h3>
                    <canvas id="statusChart" width="400" height="200"></canvas>
                </div>

                <!-- Graphique des tickets par jour -->
                <div>
                    <h3 class="text-lg font-medium text-slate-900 mb-4">Tickets des 7 derniers jours</h3>
                    <canvas id="dailyChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Métriques principales -->
            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEE2E2] to-[#FEF2F2] p-5 text-center">
                    <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Total tickets</p>
                    <p class="mt-3 text-3xl font-semibold text-[#B91C1C]" id="totalTickets">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF] p-5 text-center">
                    <p class="text-sm uppercase tracking-[0.24em] text-[#2563EB]">En attente</p>
                    <p class="mt-3 text-3xl font-semibold text-[#2563EB]" id="pendingTickets">{{ $stats['pending'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#D1FAE5] to-[#ECFDF5] p-5 text-center">
                    <p class="text-sm uppercase tracking-[0.24em] text-[#16A34A]">En service</p>
                    <p class="mt-3 text-3xl font-semibold text-[#16A34A]" id="servingTickets">{{ $stats['serving'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEF3C7] to-[#FFFBEB] p-5 text-center">
                    <p class="text-sm uppercase tracking-[0.24em] text-[#D97706]">Temps moyen</p>
                    <p class="mt-3 text-3xl font-semibold text-[#D97706]" id="avgWaitTime">{{ $stats['average_wait'] }} min</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <a href="{{ route('admin.services.index') }}" class="card-panel hover:border-[#B91C1C]">
            <div class="text-xl font-semibold text-slate-900">Services</div>
            <p class="mt-3 text-slate-600">Créer, modifier et activer les services disponibles.</p>
        </a>
        <a href="{{ route('admin.agents.index') }}" class="card-panel hover:border-[#B91C1C]">
            <div class="text-xl font-semibold text-slate-900">Agents</div>
            <p class="mt-3 text-slate-600">Gérer les agents, leurs rôles et leurs affectations.</p>
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="card-panel hover:border-[#B91C1C]">
            <div class="text-xl font-semibold text-slate-900">Tickets</div>
            <p class="mt-3 text-slate-600">Voir, appeler ou clôturer les tickets en file.</p>
        </a>
        <a href="{{ route('admin.ticket-logs.index') }}" class="card-panel hover:border-[#B91C1C]">
            <div class="text-xl font-semibold text-slate-900">Historique</div>
            <p class="mt-3 text-slate-600">Consulter l'historique complet des actions sur les tickets.</p>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des tickets par statut
    fetch('/api/charts/tickets-by-status')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['En attente', 'En service', 'Terminés', 'Annulés'],
                    datasets: [{
                        data: [data.pending, data.serving, data.completed, data.canceled],
                        backgroundColor: [
                            '#F59E0B', // Orange pour en attente
                            '#3B82F6', // Bleu pour en service
                            '#10B981', // Vert pour terminés
                            '#EF4444'  // Rouge pour annulés
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        });

    // Graphique des tickets par jour
    fetch('/api/charts/tickets-by-day')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('dailyChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Tickets créés',
                        data: data.data,
                        borderColor: '#B91C1C',
                        backgroundColor: 'rgba(185, 28, 28, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });

    // Mise à jour automatique des métriques toutes les 30 secondes
    function updateMetrics() {
        fetch('/api/charts/dashboard-stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalTickets').textContent = data.total_tickets;
                document.getElementById('pendingTickets').textContent = data.pending_tickets;
                document.getElementById('servingTickets').textContent = data.serving_tickets;
                document.getElementById('avgWaitTime').textContent = data.avg_wait_time + ' min';
            });
    }

    // Mise à jour initiale et toutes les 30 secondes
    updateMetrics();
    setInterval(updateMetrics, 30000);
});
</script>
@endsection
