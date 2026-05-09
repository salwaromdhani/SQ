@extends('layouts.app')

@section('title', $service->name)

@section('content')
<div class="space-y-8 lg:flex lg:items-start lg:gap-8">
    <div class="flex-1 space-y-6">
        <div class="card-panel">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-indigo-600">Service</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $service->name }}</h1>
                </div>
                <span class="inline-flex rounded-full px-3 py-2 text-sm font-semibold uppercase tracking-[0.15em] {{ $service->active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $service->active ? 'Actif' : 'Inactif' }}
                </span>
            </div>
        </div>

        <div class="card-panel">
            <h2 class="text-xl font-semibold text-slate-900">Informations</h2>
            <p class="mt-4 text-slate-700">{{ $service->description ?? 'Aucune description fournie.' }}</p>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Créé le</p>
                    <p class="mt-2 text-slate-900">{{ $service->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-sm text-slate-500">Dernière mise à jour</p>
                    <p class="mt-2 text-slate-900">{{ $service->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        @if($service->tickets->count() > 0)
        <div class="card-panel">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-900">Tickets associés</h2>
                <span class="text-sm text-slate-500">{{ $service->tickets->count() }} total</span>
            </div>
            <ul class="mt-5 space-y-3">
                @foreach($service->tickets->take(5) as $ticket)
                <li class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $ticket->ticket_number }} — {{ $ticket->full_name }}</p>
                            <p class="text-sm text-slate-500">{{ $ticket->email }}</p>
                        </div>
                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">{{ ucfirst($ticket->status) }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
            @if($service->tickets->count() > 5)
            <p class="mt-4 text-sm text-slate-500">+ {{ $service->tickets->count() - 5 }} autres tickets</p>
            @endif
        </div>
        @endif
    </div>

    <div class="w-full max-w-md space-y-4">
        <div class="card-panel">
            <h2 class="text-lg font-semibold text-slate-900">Actions</h2>
            <div class="mt-4 space-y-3">
                <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary w-full justify-center">Modifier</a>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Supprimer ce service ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-secondary w-full justify-center">Supprimer</button>
                </form>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary w-full justify-center">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
@endsection