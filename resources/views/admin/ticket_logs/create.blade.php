@extends('layouts.app')

@section('title', 'Ajouter un Log - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.ticket-logs.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Ajouter un log</h1>
            <p class="mt-2 text-slate-600">Enregistrez une nouvelle action sur un ticket.</p>
        </div>
    </div>

    <div class="card-panel">
        <form action="{{ route('admin.ticket-logs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label for="ticket_id" class="form-label">Ticket <span class="text-rose-500">*</span></label>
                    <select id="ticket_id" name="ticket_id" class="form-input" required>
                        <option value="">Sélectionnez un ticket</option>
                        @foreach($tickets as $ticket)
                            <option value="{{ $ticket->id }}" {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                {{ $ticket->ticket_number }} - {{ $ticket->full_name }} ({{ $ticket->service->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('ticket_id')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="action" class="form-label">Action <span class="text-rose-500">*</span></label>
                    <input type="text" id="action" name="action" value="{{ old('action') }}" class="form-input" placeholder="Ex: status_changed" required>
                    @error('action')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="old_value" class="form-label">Ancienne valeur</label>
                    <input type="text" id="old_value" name="old_value" value="{{ old('old_value') }}" class="form-input" placeholder="Ex: pending">
                    @error('old_value')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_value" class="form-label">Nouvelle valeur</label>
                    <input type="text" id="new_value" name="new_value" value="{{ old('new_value') }}" class="form-input" placeholder="Ex: serving">
                    @error('new_value')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="comment" class="form-label">Commentaire</label>
                    <textarea id="comment" name="comment" rows="4" class="form-input" placeholder="Ajoutez un commentaire facultatif...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('admin.ticket-logs.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer le log
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
