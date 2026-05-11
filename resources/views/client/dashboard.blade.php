@extends('layouts.app')

@section('title', 'Mon Espace - ESPRIT File d\'Attente')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Espace Client</p>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Bienvenue, {{ Auth::user()->name }}</h1>
                <p class="mt-3 text-slate-600">Suivez vos tickets, consultez votre historique et gérez votre profil.</p>
            </div>
            <a href="{{ route('client.tickets.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Nouveau ticket
            </a>
        </div>
    </div>

    <!-- Statistiques personnelles -->
    <div class="grid gap-6 md:grid-cols-4">
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEE2E2] to-[#FEF2F2] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Total tickets</p>
            <p class="mt-3 text-4xl font-bold text-[#B91C1C]">{{ $stats['total_tickets'] }}</p>
        </div>
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#D1FAE5] to-[#ECFDF5] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#16A34A]">Terminés</p>
            <p class="mt-3 text-4xl font-bold text-[#16A34A]">{{ $stats['completed_tickets'] }}</p>
        </div>
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEF3C7] to-[#FFFBEB] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#D97706]">Temps moyen</p>
            <p class="mt-3 text-4xl font-bold text-[#D97706]">{{ $stats['avg_wait_time'] }} min</p>
        </div>
        @if($stats['current_position'])
            <div class="rounded-[1.75rem] bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF] p-6 text-center">
                <p class="text-sm uppercase tracking-[0.24em] text-[#2563EB]">Position actuelle</p>
                <p class="mt-3 text-4xl font-bold text-[#2563EB]">{{ $stats['current_position'] }}</p>
            </div>
        @else
            <div class="rounded-[1.75rem] bg-slate-100 p-6 text-center">
                <p class="text-sm uppercase tracking-[0.24em] text-slate-500">Position actuelle</p>
                <p class="mt-3 text-4xl font-bold text-slate-400">-</p>
            </div>
        @endif
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Tickets actifs -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Mes tickets actifs</h2>

            @if($activeTickets->count() > 0)
                <div class="space-y-4">
                    @foreach($activeTickets as $ticket)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C] font-bold">
                                    {{ $ticket->ticket_number }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $ticket->service->name }}</h3>
                                    <p class="text-sm text-slate-600">
                                        @if($ticket->status === 'pending')
                                            En attente
                                        @else
                                            En service
                                        @endif
                                    </p>
                                    @if($stats['current_position'] && $ticket->status === 'pending')
                                        <p class="text-sm font-medium text-[#B91C1C]">Position: {{ $stats['current_position'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">{{ $ticket->created_at->diffForHumans() }}</p>
                                <p class="text-sm font-medium text-slate-700">{{ $ticket->estimated_wait_time }} min estimé</p>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{ route('client.tickets.show', $ticket) }}" class="text-[#B91C1C] hover:text-[#991B1B] text-sm font-medium">
                                        Détails
                                    </a>
                                    <span class="text-slate-300">•</span>
                                    <a href="{{ route('tickets.qr-code.page', $ticket) }}" class="text-[#B91C1C] hover:text-[#991B1B] text-sm font-medium">
                                        QR Code
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-ticket-alt text-4xl text-slate-300 mb-4"></i>
                    <p class="text-slate-600 mb-4">Aucun ticket actif</p>
                    <a href="{{ route('client.tickets.create') }}" class="btn-primary">
                        Créer un ticket
                    </a>
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Actions rapides</h2>
            <div class="space-y-4">
                <a href="{{ route('client.tickets.create') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-plus text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Nouveau ticket</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>

                <a href="{{ route('client.history') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-history text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Historique</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>

                <a href="{{ route('client.profile') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-user text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Mon profil</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>

                <a href="{{ route('client.queue.live') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-eye text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">File en temps réel</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Historique récent -->
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-slate-900">Historique récent</h2>
            <a href="{{ route('client.history') }}" class="text-[#B91C1C] hover:text-[#991B1B] font-medium">
                Voir tout →
            </a>
        </div>

        @if($recentTickets->count() > 0)
            <div class="space-y-4">
                @foreach($recentTickets as $ticket)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl
                                @if($ticket->status === 'completed') bg-green-100 text-green-700
                                @elseif($ticket->status === 'canceled') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700 @endif font-bold text-sm">
                                {{ $ticket->ticket_number }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900">{{ $ticket->service->name }}</h3>
                                <p class="text-sm text-slate-600">{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                @if($ticket->status === 'completed') bg-green-100 text-green-800
                                @elseif($ticket->status === 'canceled') bg-red-100 text-red-800
                                @elseif($ticket->status === 'serving') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if($ticket->status === 'completed') Terminé
                                @elseif($ticket->status === 'canceled') Annulé
                                @elseif($ticket->status === 'serving') En service
                                @else En attente @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-history text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-600">Aucun historique disponible</p>
            </div>
        @endif
    </div>
</div>
@endsection