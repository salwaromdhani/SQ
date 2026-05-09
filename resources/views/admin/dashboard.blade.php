@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <p class="text-sm uppercase tracking-[0.24em] text-indigo-600">Administration</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Tableau de bord administrateur</h1>
        <p class="mt-3 text-slate-600">Toutes les fonctions de gestion sont accessibles depuis ici. Suivez les tickets, gérez les services, les agents et consultez l'historique.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Fonctionnalités de l'administrateur</h2>
            <ul class="mt-6 space-y-4 text-slate-700">
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">1</span>
                    <span>Se connecter à l'espace admin sécurisé.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">2</span>
                    <span>Gérer les services disponibles et leur activation.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">3</span>
                    <span>Gérer les agents et leurs affectations aux services.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">4</span>
                    <span>Gérer les tickets en attente, en cours ou terminés.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">5</span>
                    <span>Appeler un ticket pour le passer en service.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">6</span>
                    <span>Clôturer un ticket une fois le service terminé.</span>
                </li>
                <li class="flex gap-3">
                    <span class="mt-1 inline-flex h-8 w-8 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">7</span>
                    <span>Consulter l'historique des actions et des modifications de tickets.</span>
                </li>
            </ul>
        </div>

        <div class="rounded-[2rem] border border-slate-200/70 bg-slate-950/95 p-8 shadow-sm text-white">
            <h2 class="text-xl font-semibold text-white">Statistiques rapides</h2>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-slate-900/80 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Services</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['services'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/80 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Agents</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['agents'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/80 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Tickets en attente</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['pending'] }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/80 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Tickets en cours</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['serving'] }}</p>
                </div>
                <div class="sm:col-span-2 rounded-[1.75rem] bg-slate-900/80 p-5">
                    <p class="text-sm uppercase tracking-[0.24em] text-slate-400">Historique total</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $stats['history'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <a href="{{ route('admin.services.index') }}" class="card-panel hover:border-indigo-500">
            <div class="text-xl font-semibold text-slate-900">Services</div>
            <p class="mt-3 text-slate-600">Créer, modifier et activer les services disponibles.</p>
        </a>
        <a href="{{ route('admin.agents.index') }}" class="card-panel hover:border-indigo-500">
            <div class="text-xl font-semibold text-slate-900">Agents</div>
            <p class="mt-3 text-slate-600">Gérer les agents, leurs rôles et leurs affectations.</p>
        </a>
        <a href="{{ route('admin.tickets.index') }}" class="card-panel hover:border-indigo-500">
            <div class="text-xl font-semibold text-slate-900">Tickets</div>
            <p class="mt-3 text-slate-600">Voir, appeler ou clôturer les tickets en file.</p>
        </a>
        <a href="{{ route('admin.ticket-logs.index') }}" class="card-panel hover:border-indigo-500">
            <div class="text-xl font-semibold text-slate-900">Historique</div>
            <p class="mt-3 text-slate-600">Consulter l’historique complet des actions sur les tickets.</p>
        </a>
    </div>
</div>
@endsection
