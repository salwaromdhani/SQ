@extends('layouts.app')

@section('title', 'Modifier le ticket')

@section('content')
<div class="max-w-3xl space-y-8">
    <div class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600">Ticket</p>
        <h1 class="text-3xl font-semibold text-slate-900">Modifier {{ $ticket->ticket_number }}</h1>
        <p class="text-slate-600">Mettez à jour le statut et la priorité du ticket.</p>
    </div>

    <div class="card-panel">
        <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="form-field">
                    <label class="form-label">Client</label>
                    <p class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700">{{ $ticket->full_name }}</p>
                </div>
                <div class="form-field">
                    <label class="form-label">Service</label>
                    <p class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-700">{{ $ticket->service->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="form-field">
                <label class="form-label">Statut</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ old('status', $ticket->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="serving" {{ old('status', $ticket->status) == 'serving' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ old('status', $ticket->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                    <option value="canceled" {{ old('status', $ticket->status) == 'canceled' ? 'selected' : '' }}>Annulé</option>
                </select>
                @error('status') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="form-field">
                <label class="form-label">Priorité</label>
                <select name="priority" class="form-select">
                    <option value="normal" {{ old('priority', $ticket->priority) == 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
                @error('priority') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-secondary w-full sm:w-auto">Retour</a>
                <button type="submit" class="btn-primary w-full sm:w-auto">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection