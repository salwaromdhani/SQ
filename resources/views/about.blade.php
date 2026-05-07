@extends('layouts.app')

@section('title', 'À propos')

@section('content')
<h2>À propos du projet</h2>
<p class="lead">Cette application a été développée afin de faciliter la gestion des files d’attente en proposant une solution virtuelle qui réduit le temps d’attente et améliore l’expérience des utilisateurs.</p>

<p>Elle permet aux usagers de :</p>
<ul>
    <li>Rejoindre une file d'attente virtuelle</li>
    <li>Suivre l'état de leur ticket en temps réel</li>
    <li>Réduire les files physiques et optimiser le temps d'attente</li>
</ul>

<div class="alert alert-info mt-4">
    <h5 class="alert-heading">🎯 Fonctionnalités disponibles :</h5>
    <ul class="mb-0">
        <li>✅ Créer un ticket pour un service</li>
        <li>✅ Consulter la liste des tickets</li>
        <li>✅ Modifier le statut d'un ticket</li>
        <li>✅ Rechercher un ticket par nom ou numéro</li>
        <li>✅ Supprimer un ticket</li>
    </ul>
</div>

<div class="text-center mt-4">
    <a href="{{ route('client.tickets.create') }}" class="btn btn-success btn-lg">
        🎫 Commencer à utiliser l'application
    </a>
</div>
@endsection