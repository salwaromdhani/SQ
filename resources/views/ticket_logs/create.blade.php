@extends('layouts.app')

@section('title', 'Ajouter une entrée')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-esprit">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Nouvelle entrée dans l'historique</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('ticket-logs.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Ticket concerné <span class="text-danger">*</span></label>
                        <select name="ticket_id" class="form-select @error('ticket_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un ticket --</option>
                            @foreach($tickets as $ticket)
                                <option value="{{ $ticket->id }}" {{ old('ticket_id')==$ticket->id?'selected':'' }}>
                                    {{ $ticket->ticket_number }} - {{ $ticket->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type d'action <span class="text-danger">*</span></label>
                        <select name="action" class="form-select @error('action') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une action --</option>
                            <option value="status_changed">Changement de statut</option>
                            <option value="priority_changed">Changement de priorité</option>
                            <option value="note_added">Note ajoutée</option>
                            <option value="other">Autre</option>
                        </select>
                        @error('action') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Commentaire / Observation</label>
                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" 
                                  rows="4" maxlength="500" placeholder="Détails sur l'action effectuée...">{{ old('comment') }}</textarea>
                        @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ticket-logs.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-esprit">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection