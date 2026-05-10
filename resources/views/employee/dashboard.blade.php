@extends('layouts.app')

@section('title', 'Dashboard Employé - ESPRIT File d\'Attente')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Espace Employé</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Tableau de bord employé</h1>
        <p class="mt-3 text-slate-600">Gérez les tickets en attente, appelez les clients et suivez les services en cours.</p>
    </div>

    <!-- Statistiques -->
    <div class="grid gap-6 md:grid-cols-4">
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEE2E2] to-[#FEF2F2] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">En attente</p>
            <p class="mt-3 text-4xl font-bold text-[#B91C1C]">{{ $stats['pending_count'] }}</p>
        </div>
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#2563EB]">En service</p>
            <p class="mt-3 text-4xl font-bold text-[#2563EB]">{{ $stats['serving_count'] }}</p>
        </div>
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#D1FAE5] to-[#ECFDF5] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#16A34A]">Terminés aujourd'hui</p>
            <p class="mt-3 text-4xl font-bold text-[#16A34A]">{{ $stats['completed_today'] }}</p>
        </div>
        <div class="rounded-[1.75rem] bg-gradient-to-br from-[#FEF3C7] to-[#FFFBEB] p-6 text-center">
            <p class="text-sm uppercase tracking-[0.24em] text-[#D97706]">Temps moyen</p>
            <p class="mt-3 text-4xl font-bold text-[#D97706]">{{ $stats['avg_wait_time'] }} min</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Tickets en attente -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-slate-900">Tickets en attente</h2>
                <form method="POST" action="{{ route('employee.call-next') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-bullhorn mr-2"></i>Appeler suivant
                    </button>
                </form>
            </div>

            @if($pendingTickets->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingTickets as $ticket)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C] font-bold">
                                    {{ $ticket->ticket_number }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $ticket->full_name }}</h3>
                                    <p class="text-sm text-slate-600">{{ $ticket->service->name }}</p>
                                    @if($ticket->priority === 'vip')
                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
                                            <i class="fas fa-star mr-1"></i>VIP
                                        </span>
                                    @elseif($ticket->priority === 'urgent')
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Urgent
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">{{ $ticket->created_at->diffForHumans() }}</p>
                                <p class="text-sm font-medium text-slate-700">{{ $ticket->estimated_wait_time }} min</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-4"></i>
                    <p class="text-slate-600">Aucun ticket en attente</p>
                </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900 mb-6">Actions rapides</h2>
            <div class="space-y-4">
                <a href="{{ route('employee.serving') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Tickets en service</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>

                <a href="{{ route('admin.tickets.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-list text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Tous les tickets</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>

                <a href="{{ route('admin.services.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-cogs text-[#B91C1C]"></i>
                        <span class="font-medium text-slate-900">Services</span>
                    </div>
                    <i class="fas fa-chevron-right text-slate-400"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection