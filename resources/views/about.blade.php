@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<div class="space-y-8 max-w-3xl">
    <div class="rounded-[2rem] border border-slate-200/70 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-semibold text-slate-900">À propos du projet</h1>
        <p class="mt-4 text-slate-600">Cette application a été développée afin de faciliter la gestion des files d’attente en proposant une solution virtuelle qui réduit le temps d’attente et améliore l’expérience des utilisateurs.</p>

        <div class="mt-6 space-y-3 text-slate-700">
            <p>Elle permet aux usagers de :</p>
            <ul class="list-disc space-y-2 pl-6">
                <li>Rejoindre une file d'attente virtuelle</li>
                <li>Suivre l'état de leur ticket en temps réel</li>
                <li>Réduire les files physiques et optimiser le temps d'attente</li>
            </ul>
        </div>

        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <h2 class="text-xl font-semibold text-slate-900">🎯 Fonctionnalités disponibles :</h2>
            <ul class="mt-4 space-y-2 text-slate-700">
                <li>✅ Créer un ticket pour un service</li>
                <li>✅ Consulter la liste des tickets</li>
                <li>✅ Modifier le statut d'un ticket</li>
                <li>✅ Rechercher un ticket par nom ou numéro</li>
                <li>✅ Supprimer un ticket</li>
            </ul>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                🎫 Commencer à utiliser l'application
            </a>
        </div>
    </div>
</div>
@endsection