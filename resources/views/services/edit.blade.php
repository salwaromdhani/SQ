@extends('layouts.app')

@section('title', 'Modifier le service')

@section('content')
<div class="space-y-8 max-w-3xl">
    <div class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600">Service</p>
        <h1 class="text-3xl font-semibold text-slate-900">Modifier {{ $service->name }}</h1>
        <p class="text-slate-600">Mettez à jour les informations et l'état de ce service.</p>
    </div>

    <div class="card-panel">
        <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="form-field">
                <label class="form-label">Nom du service <span class="text-rose-500">*</span></label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $service->name) }}" required maxlength="100">
                @error('name') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="form-field">
                <label class="form-label">Description</label>
                <textarea name="description" rows="4" class="form-input min-h-[8rem]">{{ old('description', $service->description) }}</textarea>
                @error('description') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <input id="active" name="active" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('active', $service->active) ? 'checked' : '' }}>
                <label for="active" class="text-sm font-medium text-slate-700">Service actif</label>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                <a href="{{ route('admin.services.show', $service) }}" class="btn-secondary w-full sm:w-auto">Retour</a>
                <button type="submit" class="btn-primary w-full sm:w-auto">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection