@extends('layouts.app')

@section('title', 'Modifier le commentaire')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-esprit">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Modifier le Log #{{ $ticketLog->id }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('ticket-logs.update', $ticketLog) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label text-muted">Type d'action (Lecture seule)</label>
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $ticketLog->action)) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Modifier le commentaire</label>
                        <textarea name="comment" class="form-control @error('comment') is-invalid @enderror" 
                                  rows="4" maxlength="500">{{ old('comment', $ticketLog->comment) }}</textarea>
                        @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ticket-logs.show', $ticketLog) }}" class="btn btn-outline-secondary">
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