@extends('layouts.app')

@section('title', 'Nouveau Service')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-esprit">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Créer un service</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('services.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nom du service <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" required maxlength="100">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3" maxlength="255">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" id="active" 
                               {{ old('active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Service actif</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-esprit">
                            <i class="fas fa-save me-1"></i> Créer le service
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection