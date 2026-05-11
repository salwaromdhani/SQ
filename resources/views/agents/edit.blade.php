@extends('layouts.app')

@section('title', 'Modifier l\'Agent')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-esprit">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Modifier {{ $agent->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('agents.update', $agent) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $agent->name) }}" required maxlength="100">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $agent->email) }}" required maxlength="100">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $agent->phone) }}" maxlength="20">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="agent" {{ old('role',$agent->role)==='agent'?'selected':'' }}>Agent</option>
                            <option value="superviseur" {{ old('role',$agent->role)==='superviseur'?'selected':'' }}>Superviseur</option>
                            <option value="admin" {{ old('role',$agent->role)==='admin'?'selected':'' }}>Administrateur</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label"> les Services affecté</label>
                        <select name="service_id" class="form-select @error('service_id') is-invalid @enderror">
                            <option value="">-- Non affecté --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id',$agent->service_id)==$service->id?'selected':'' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" id="active" 
                               {{ old('active', $agent->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">Agent actif</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('agents.show', $agent) }}" class="btn btn-outline-secondary">
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