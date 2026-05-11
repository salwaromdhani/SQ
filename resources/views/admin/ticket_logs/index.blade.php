@extends('layouts.app')

@section('title', 'Historique des Tickets - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Historique des tickets</h1>
            <p class="mt-2 text-slate-600">Consultez l'historique complet des actions sur les tickets.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Dashboard
            </a>
            <a href="{{ route('admin.ticket-logs.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un log
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-panel">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div class="text-sm text-slate-600">
                {{ $logs->total() }} action{{ $logs->total() > 1 ? 's' : '' }} enregistrée{{ $logs->total() > 1 ? 's' : '' }}
            </div>
            <form method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="ticket_id" value="{{ request('ticket_id') }}" placeholder="ID du ticket..."
                       class="rounded-3xl border border-slate-300 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit" class="btn-secondary text-sm px-4 py-2">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($logs as $log)
                <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#FEE2E2] text-[#B91C1C]">
                        <i class="fas fa-history text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="font-semibold text-slate-900">{{ $log->action }}</h3>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-600">
                                Ticket #{{ $log->ticket->ticket_number }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $log->ticket->full_name }} - {{ $log->ticket->service->name }}</p>
                        @if($log->details)
                            <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg">{{ $log->details }}</p>
                        @endif
                        <p class="text-xs text-slate-500 mt-2">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.ticket-logs.show', $log) }}" class="btn-secondary text-sm px-3 py-2">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.ticket-logs.edit', $log) }}" class="btn-primary text-sm px-3 py-2">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.ticket-logs.destroy', $log) }}" method="POST" class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce log ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger text-sm px-3 py-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center">
                    <i class="fas fa-history text-4xl text-slate-400"></i>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Aucun historique</h3>
                    <p class="mt-2 text-slate-600">Il n'y a encore aucune action enregistrée.</p>
                </div>
            @endforelse
        </div>

        @if($logs->hasPages())
            <div class="mt-8">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection