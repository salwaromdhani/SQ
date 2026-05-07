@extends('layouts.app')

@section('title', 'Votre Ticket')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto">
        <div class="card-esprit overflow-hidden">
            <div class="hero-panel px-8 py-10 text-center">
                <div class="mx-auto mb-8 inline-flex h-20 w-20 items-center justify-center rounded-[2.5rem] bg-white/10 text-white shadow-lg shadow-white/10">
                    <span class="text-4xl">🎫</span>
                </div>
                <h1 class="text-4xl font-bold text-white">Ticket créé avec succès !</h1>
                <p class="mt-3 text-soft">Votre ticket est enregistré et vous pouvez suivre l'évolution de votre file en direct.</p>
            </div>

            <div class="bg-slate-950/95 px-8 py-10">
                <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8 mb-8">
                    <div class="text-slate-400 text-sm uppercase tracking-[0.24em]">Numéro de ticket</div>
                    <div class="mt-4 text-5xl font-semibold tracking-tight text-white">{{ $ticket->ticket_number }}</div>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                        <p class="text-sm text-slate-400">Service</p>
                        <p class="mt-3 text-xl font-semibold text-white">{{ $ticket->service->name }}</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                        <p class="text-sm text-slate-400">Position dans la file</p>
                        <p class="mt-3 text-3xl font-semibold text-rose-300" data-position>#{{ $position }}</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                        <p class="text-sm text-slate-400">Temps d'attente estimé</p>
                        <p class="mt-3 text-3xl font-semibold text-emerald-300" data-time>{{ $ticket->estimated_wait_time }} min</p>
                    </div>
                    <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                        <p class="text-sm text-slate-400">Statut</p>
                        <p class="mt-3 text-xl font-semibold" data-status>
                            @if($ticket->status === 'pending')
                                <span class="text-amber-300">En attente</span>
                            @elseif($ticket->status === 'serving')
                                <span class="text-emerald-300">En cours</span>
                            @else
                                <span class="text-slate-400">Terminé</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div id="notification" class="hidden rounded-[1.75rem] border border-sky-500/20 bg-sky-500/10 p-5 mt-8 text-sky-100">
                    <p class="font-semibold">🔔 C'est votre tour !</p>
                    <p class="mt-2">Présentez-vous au guichet dès maintenant.</p>
                </div>

                <div class="mt-8 rounded-[1.75rem] border border-amber-300/20 bg-amber-300/10 p-5 text-amber-50">
                    <p class="font-semibold">📱 Vous serez notifié quand ce sera votre tour</p>
                    <p class="mt-2 text-sm text-white/80">Temps estimé : {{ $ticket->estimated_wait_time }} minutes.</p>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('client.tickets.create') }}" class="btn-esprit">Créer un autre ticket</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de vérification automatique -->
<script>
// Éléments à mettre à jour
const positionElement = document.querySelector('[data-position]');
const statusElement = document.querySelector('[data-status]');
const timeElement = document.querySelector('[data-time]');
const notificationElement = document.getElementById('notification');

// Vérifier le statut toutes les 8-10 secondes
setInterval(function() {
    fetch('{{ route("api.tickets.status", $ticket) }}')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour la position
            if (positionElement) {
                positionElement.textContent = '#' + data.position;
            }

            // Mettre à jour le temps estimé
            if (timeElement) {
                timeElement.textContent = data.estimated_wait_time + ' min';
            }

            // Mettre à jour le statut
            if (statusElement) {
                let statusText = '';
                let statusClass = '';
                if (data.status === 'pending') {
                    statusText = 'En attente';
                    statusClass = 'text-yellow-600';
                } else if (data.status === 'serving') {
                    statusText = 'En cours';
                    statusClass = 'text-green-600';
                } else {
                    statusText = 'Terminé';
                    statusClass = 'text-gray-600';
                }
                statusElement.textContent = statusText;
                statusElement.className = 'font-semibold ' + statusClass;
            }

            // Notification si c'est le tour
            if (data.status === 'serving' && !notificationElement.classList.contains('shown')) {
                notificationElement.classList.remove('hidden');
                notificationElement.classList.add('shown');

                // Notification navigateur
                if (Notification.permission === 'granted') {
                    new Notification('File d\'attente - ' + data.ticket_number, {
                        body: 'C\'est votre tour ! Présentez-vous au guichet.',
                        icon: '/images/logo.png'
                    });
                }

                // Son de notification
                playNotificationSound();
            }
        })
        .catch(error => console.log('Erreur de mise à jour:', error));
}, 8000); // Toutes les 8 secondes

function playNotificationSound() {
    // Son simple de notification
    const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE');
    audio.play().catch(e => console.log('Audio play failed:', e));
}

// Demander la permission pour les notifications au chargement
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}
</script>
@endsection