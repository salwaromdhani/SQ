@extends('layouts.app')

@section('title', 'File d\'Attente en Temps Réel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 rounded-[2rem] border border-white/10 bg-slate-950/90 p-8 shadow-[0_30px_80px_-35px_rgba(15,23,42,0.95)] backdrop-blur-xl">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white">File d'Attente en Temps Réel</h1>
                <p class="mt-3 text-white/70">Suivez l'activité de la file, consultez vos tickets et recevez une estimation précise.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('client.tickets.create') }}" class="btn-primary">Prendre un ticket</a>
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/15">Retour à l'accueil</a>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-4">
        <div class="card-esprit p-6">
            <p class="text-sm uppercase tracking-[0.24em] text-white/60">En attente</p>
            <p id="total-pending" class="mt-4 text-4xl font-bold text-white">{{ $tickets->where('status', 'pending')->count() }}</p>
        </div>
        <div class="card-esprit p-6">
            <p class="text-sm uppercase tracking-[0.24em] text-white/60">En cours</p>
            <p id="total-serving" class="mt-4 text-4xl font-bold text-white">{{ $tickets->where('status', 'serving')->count() }}</p>
        </div>
        <div class="card-esprit p-6">
            <p class="text-sm uppercase tracking-[0.24em] text-white/60">Terminés aujourd'hui</p>
            <p id="total-completed" class="mt-4 text-4xl font-bold text-white">{{ \App\Models\Ticket::where('status', 'completed')->whereDate('created_at', today())->count() }}</p>
        </div>
        <div class="card-esprit p-6">
            <p class="text-sm uppercase tracking-[0.24em] text-white/60">Temps moyen</p>
            <p id="avg-wait" class="mt-4 text-4xl font-bold text-white">{{ $tickets->avg('estimated_wait_time') ?: 0 }}</p>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-[2rem] border border-white/10 shadow-[0_30px_80px_-35px_rgba(15,23,42,0.95)]">
        <div class="bg-slate-950/95 px-8 py-5 border-b border-white/10">
            <h2 class="text-2xl font-semibold text-white">Tickets Actifs</h2>
        </div>
        <div class="overflow-x-auto bg-slate-900/95">
            <table class="min-w-full divide-y divide-white/10" id="tickets-table">
                <thead class="bg-slate-950/95 text-slate-400">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Numéro</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Service</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Position</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Attente</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Créé</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-950/90 divide-y divide-white/10">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-900/80">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $ticket->ticket_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">{{ $ticket->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">{{ $ticket->service->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ticket->status === 'pending')
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">En attente</span>
                            @elseif($ticket->status === 'serving')
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">En cours</span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700">Terminé</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">
                            @if($ticket->status === 'pending')
                                {{ \App\Models\Ticket::where('service_id', $ticket->service_id)->where('status', 'pending')->where('created_at', '<=', $ticket->created_at)->count() }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-200">{{ $ticket->estimated_wait_time }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">{{ $ticket->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Aucun ticket actif dans la file d'attente.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Mise à jour automatique toutes les 10 secondes
setInterval(function() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        }
    })
    .then(response => response.text())
    .then(html => {
        // Extraire et mettre à jour le tableau
        const parser = new DOMParser();
        const newDoc = parser.parseFromString(html, 'text/html');
        const newTable = newDoc.querySelector('#tickets-table tbody');
        const currentTable = document.querySelector('#tickets-table tbody');

        if (newTable && currentTable) {
            currentTable.innerHTML = newTable.innerHTML;
        }

        // Mettre à jour les statistiques
        const stats = ['total-pending', 'total-serving', 'total-completed', 'avg-wait'];
        stats.forEach(id => {
            const newStat = newDoc.querySelector('#' + id);
            const currentStat = document.querySelector('#' + id);
            if (newStat && currentStat) {
                currentStat.textContent = newStat.textContent;
            }
        });
    })
    .catch(error => console.log('Erreur de mise à jour:', error));
}, 10000); // Toutes les 10 secondes
</script>
@endsection