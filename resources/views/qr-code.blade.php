@extends('layouts.app')

@section('title', 'QR Code - Ticket {{ $ticket->ticket_number }}')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">QR Code</p>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Ticket {{ $ticket->ticket_number }}</h1>
                <p class="mt-3 text-slate-600">Scannez ce QR code pour accéder rapidement à votre ticket.</p>
            </div>
            <a href="{{ route('client.tickets.show', $ticket) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voir le ticket
            </a>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- QR Code -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm text-center">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Votre QR Code</h2>

            <div class="bg-white p-6 rounded-2xl border-2 border-dashed border-slate-300 inline-block">
                <img src="{{ route('tickets.qr-code', $ticket) }}"
                     alt="QR Code Ticket {{ $ticket->ticket_number }}"
                     class="w-64 h-64 mx-auto" />
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ route('tickets.qr-code.download', $ticket) }}"
                   class="btn-primary inline-block">
                    <i class="fas fa-download mr-2"></i>Télécharger
                </a>

                <p class="text-sm text-slate-600 mt-4">
                    Utilisez ce QR code pour un accès rapide à votre ticket depuis votre téléphone.
                </p>
            </div>
        </div>

        <!-- Informations du ticket -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Informations du ticket</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-slate-200">
                    <span class="text-slate-600">Numéro du ticket</span>
                    <span class="font-semibold text-slate-900">{{ $ticket->ticket_number }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-slate-200">
                    <span class="text-slate-600">Service</span>
                    <span class="font-semibold text-slate-900">{{ $ticket->service->name }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-slate-200">
                    <span class="text-slate-600">Statut</span>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                        @if($ticket->status === 'completed') bg-green-100 text-green-800
                        @elseif($ticket->status === 'canceled') bg-red-100 text-red-800
                        @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($ticket->status === 'completed') <i class="fas fa-check mr-1"></i>Terminé
                        @elseif($ticket->status === 'canceled') <i class="fas fa-times mr-1"></i>Annulé
                        @elseif($ticket->status === 'serving') <i class="fas fa-clock mr-1"></i>En service
                        @else <i class="fas fa-clock mr-1"></i>En attente @endif
                    </span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-slate-200">
                    <span class="text-slate-600">Créé le</span>
                    <span class="font-semibold text-slate-900">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                </div>

                @if($ticket->estimated_wait_time)
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Temps d'attente estimé</span>
                        <span class="font-semibold text-slate-900">{{ $ticket->estimated_wait_time }} minutes</span>
                    </div>
                @endif

                @if($ticket->started_at)
                    <div class="flex justify-between items-center py-3 border-b border-slate-200">
                        <span class="text-slate-600">Service commencé</span>
                        <span class="font-semibold text-slate-900">{{ $ticket->started_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif

                @if($ticket->completed_at)
                    <div class="flex justify-between items-center py-3">
                        <span class="text-slate-600">Terminé le</span>
                        <span class="font-semibold text-slate-900">{{ $ticket->completed_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <div class="flex gap-3">
                    <a href="{{ route('client.tickets.show', $ticket) }}" class="btn-primary flex-1 text-center">
                        <i class="fas fa-eye mr-2"></i>Voir le ticket
                    </a>
                    <a href="{{ route('tickets.qr-code.download', $ticket) }}" class="btn-secondary flex-1 text-center">
                        <i class="fas fa-download mr-2"></i>Télécharger QR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions d'utilisation -->
    <div class="rounded-[2rem] border border-slate-200/70 bg-gradient-to-br from-[#FEE2E2] to-[#FEF2F2] p-8 shadow-sm">
        <h2 class="text-xl font-semibold text-[#B91C1C] mb-4">
            <i class="fas fa-info-circle mr-2"></i>Comment utiliser votre QR code ?
        </h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="flex items-start gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#B91C1C] text-white font-bold text-sm">1</div>
                <div>
                    <h3 class="font-semibold text-slate-900">Ouvrez votre application de scan</h3>
                    <p class="text-sm text-slate-700">Utilisez l'appareil photo de votre téléphone ou une application de scan QR.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#B91C1C] text-white font-bold text-sm">2</div>
                <div>
                    <h3 class="font-semibold text-slate-900">Scannez le QR code</h3>
                    <p class="text-sm text-slate-700">Pointez votre caméra vers le QR code affiché ci-dessus.</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#B91C1C] text-white font-bold text-sm">3</div>
                <div>
                    <h3 class="font-semibold text-slate-900">Accédez à votre ticket</h3>
                    <p class="text-sm text-slate-700">Vous serez redirigé vers la page de suivi de votre ticket.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection