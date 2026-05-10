@extends('layouts.app')

@section('title', 'Tickets en Service - ESPRIT File d\'Attente')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.24em] text-[#B91C1C]">Espace Employé</p>
                <h1 class="mt-4 text-3xl font-semibold text-slate-900">Tickets en service</h1>
                <p class="mt-3 text-slate-600">Gérez les tickets actuellement en cours de traitement.</p>
            </div>
            <a href="{{ route('employee.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au dashboard
            </a>
        </div>
    </div>

    @if($tickets->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($tickets as $ticket)
                <div class="rounded-[2rem] border border-slate-200/70 bg-gradient-to-br from-[#DBEAFE] to-[#EFF6FF] p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#2563EB] text-white font-bold text-lg">
                            {{ $ticket->ticket_number }}
                        </div>
                        <div class="text-right">
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

                    <div class="space-y-3">
                        <div>
                            <h3 class="font-semibold text-slate-900 text-lg">{{ $ticket->full_name }}</h3>
                            <p class="text-sm text-slate-600">{{ $ticket->service->name }}</p>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Début:</span>
                            <span class="font-medium text-slate-900">{{ $ticket->started_at?->format('H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">Durée:</span>
                            <span class="font-medium text-slate-900">
                                @if($ticket->started_at)
                                    {{ now()->diffInMinutes($ticket->started_at) }} min
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        @if($ticket->email)
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $ticket->email }}</span>
                            </div>
                        @endif

                        @if($ticket->phone)
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <i class="fas fa-phone"></i>
                                <span>{{ $ticket->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 mt-6">
                        <form method="POST" action="{{ route('employee.tickets.complete-service', $ticket) }}" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full btn-primary bg-green-600 hover:bg-green-700">
                                <i class="fas fa-check mr-2"></i>Terminer
                            </button>
                        </form>

                        <button type="button" onclick="cancelTicket({{ $ticket->id }}, '{{ $ticket->ticket_number }}')"
                                class="px-4 py-2 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-12 shadow-sm text-center">
            <i class="fas fa-clock text-6xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-900 mb-2">Aucun ticket en service</h3>
            <p class="text-slate-600">Tous les tickets sont terminés ou en attente.</p>
            <a href="{{ route('employee.dashboard') }}" class="btn-primary mt-6 inline-block">
                Retour au dashboard
            </a>
        </div>
    @endif
</div>

<!-- Modal d'annulation -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Annuler le ticket</h3>
            <form id="cancelForm" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Raison de l'annulation</label>
                    <textarea name="reason" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-2 focus:border-[#B91C1C] focus:ring-[#B91C1C]" placeholder="Raison de l'annulation..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 btn-secondary">Annuler</button>
                    <button type="submit" class="flex-1 btn-primary bg-red-600 hover:bg-red-700">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cancelTicket(ticketId, ticketNumber) {
    document.getElementById('cancelForm').action = `/employee/tickets/${ticketId}/cancel`;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}
</script>
@endsection