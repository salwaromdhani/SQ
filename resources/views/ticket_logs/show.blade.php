@extends('layouts.app')

@section('title', 'Votre Ticket - ' . $ticket->ticket_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Carte principale -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white text-center">
                <div class="text-6xl mb-4">🎫</div>
                <h1 class="text-4xl font-bold mb-2">File d'Attente Virtuelle</h1>
                <p class="text-blue-100">Gardez cette page ouverte pour recevoir les notifications</p>
            </div>

            <!-- Contenu -->
            <div class="p-8">
                <!-- Numéro de ticket -->
                <div class="text-center mb-8">
                    <p class="text-gray-600 mb-2">Votre numéro de ticket</p>
                    <div class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 text-white text-5xl font-bold px-8 py-4 rounded-lg shadow-lg">
                        {{ $ticket->ticket_number }}
                    </div>
                </div>

                <!-- Informations -->
                <div class="grid md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Service demandé</p>
                        <p class="font-semibold text-lg">{{ $ticket->service->name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Position dans la file</p>
                        <p class="font-semibold text-2xl text-blue-600">#{{ $position }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Temps d'attente estimé</p>
                        <p class="font-semibold text-lg text-orange-600">{{ $ticket->estimated_wait_time }} minutes</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Statut actuel</p>
                        <p class="font-semibold text-lg">
                            @if($ticket->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    ⏳ En attente
                                </span>
                            @elseif($ticket->status === 'serving')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    ✅ En cours
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    ✔️ Terminé
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Notification -->
                <div id="notification" class="hidden bg-green-50 border-l-4 border-green-500 p-6 mb-6 rounded-r-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-green-800"> C'est votre tour !</h3>
                            <p class="text-green-700 mt-1">
                                Présentez-vous au guichet <strong>{{ $ticket->service->name }}</strong> maintenant.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Alertes -->
                @if(session('success'))
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <p class="text-blue-700">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-4">
                    <a href="{{ route('client.tickets.create') }}" 
                       class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition text-center font-medium">
                        Créer un autre ticket
                    </a>
                    <a href="{{ route('home') }}" 
                       class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition text-center font-medium">
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">📱 Comment ça marche ?</h2>
            <ol class="space-y-3 text-gray-700">
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">1</span>
                    <span>Gardez cette page ouverte dans votre navigateur</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">2</span>
                    <span>Vous recevrez une notification quand ce sera votre tour</span>
                </li>
                <li class="flex items-start">
                    <span class="bg-blue-100 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0">3</span>
                    <span>Présentez-vous au guichet avec votre numéro de ticket</span>
                </li>
            </ol>
        </div>
    </div>
</div>

<!-- Script de vérification automatique -->
<script>
// Demander la permission pour les notifications
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

let lastStatus = '{{ $ticket->status }}';
let hasNotified = false;

// Vérifier le statut toutes les 10 secondes
setInterval(function() {
    fetch('{{ route("tickets.api.status", $ticket) }}')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour la position
            document.querySelector('.text-blue-600').textContent = '#' + data.position;
            
            // Vérifier si le statut a changé
            if (data.status !== lastStatus && !hasNotified) {
                lastStatus = data.status;
                
                if (data.status === 'serving') {
                    // Afficher la notification dans la page
                    document.getElementById('notification').classList.remove('hidden');
                    
                    // Notification navigateur
                    if (Notification.permission === 'granted') {
                        new Notification('🎫 File d\'attente - {{ $ticket->ticket_number }}', {
                            body: 'C\'est votre tour ! Présentez-vous au guichet {{ $ticket->service->name }}.',
                            icon: '/logo.png',
                            requireInteraction: true
                        });
                    }
                    
                    // Son de notification
                    playNotificationSound();
                    
                    hasNotified = true;
                }
            }
        })
        .catch(error => console.error('Erreur de vérification:', error));
}, 10000); // Toutes les 10 secondes

function playNotificationSound() {
    // Son de notification simple
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();
    
    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);
    
    oscillator.frequency.value = 800;
    oscillator.type = 'sine';
    
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
    
    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

// Rafraîchir la page toutes les 30 secondes pour mettre à jour tous les éléments
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endsection