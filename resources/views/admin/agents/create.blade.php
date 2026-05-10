@extends('layouts.app')

@section('title', 'Créer un Agent - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.agents.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Nouvel agent</h1>
            <p class="mt-2 text-slate-600">Ajoutez un nouvel agent à votre équipe.</p>
        </div>
    </div>

    <div class="card-panel">
        <form action="{{ route('admin.agents.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="name" class="form-label">Nom complet <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" placeholder="Ex: Jean Dupont" required>
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="agent@esprit.tn" required>
                    @error('email')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+216 00 000 000">
                    @error('phone')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="form-label">Rôle <span class="text-rose-500">*</span></label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="" disabled selected>Choisir un rôle</option>
                        <option value="agent" {{ old('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                        <option value="superviseur" {{ old('role') === 'superviseur' ? 'selected' : '' }}>Superviseur</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="service_id" class="form-label">Service affecté</label>
                    <select id="service_id" name="service_id" class="form-select">
                        <option value="">Aucun service (non affecté)</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('admin.agents.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Créer l'agent
                </button>
            </div>
        </form>
    </div>
</div>
@endsection