@extends('layouts.app')

@section('title', 'Modifier le Ticket')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-esprit">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier {{ $ticket->ticket_number }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <!-- Informations en lecture seule -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Client</label>
                            <p class="fw-medium mb-0">{{ $ticket->full_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Service</label>
                            <p class="fw-medium mb-0">{{ $ticket->service->name ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="mb-3">
                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="waiting" {{ old('status',$ticket->status)=='waiting'?'selected':'' }}>En attente</option>
                            <option value="processing" {{ old('status',$ticket->status)=='processing'?'selected':'' }}>En cours</option>
                            <option value="completed" {{ old('status',$ticket->status)=='completed'?'selected':'' }}>Terminé</option>
                            <option value="canceled" {{ old('status',$ticket->status)=='canceled'?'selected':'' }}>Annulé</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Priorité -->
                    <div class="mb-3">
                        <label class="form-label">Priorité</label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                            <option value="normal" {{ old('priority',$ticket->priority)=='normal'?'selected':'' }}>Normale</option>
                            <option value="urgent" {{ old('priority',$ticket->priority)=='urgent'?'selected':'' }}>Urgente</option>
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label">Notes internes</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                  rows="3">{{ old('notes',$ticket->notes) }}</textarea>
                        <small class="text-muted">Maximum 500 caractères</small>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-esprit">
                            <i class="fas fa-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection