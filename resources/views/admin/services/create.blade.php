@extends('layouts.app')

@section('title', 'Créer un Service - Admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.services.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Nouveau service</h1>
            <p class="mt-2 text-slate-600">Ajoutez un nouveau service à votre système de file d'attente.</p>
        </div>
    </div>

    <div class="card-panel">
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label for="name" class="form-label">Nom du service <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-input" placeholder="Ex: Service clientèle" required>
                    @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="4" class="form-input" placeholder="Description du service...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" id="active" name="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="active" class="ml-2 block text-sm text-slate-900">
                        Service actif
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('admin.services.index') }}" class="btn-secondary">Annuler</a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Créer le service
                </button>
            </div>
        </form>
    </div>
</div>
@endsection