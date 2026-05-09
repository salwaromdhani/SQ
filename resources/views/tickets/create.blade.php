@extends('layouts.app')

@section('title', 'Créer un ticket - SmartQueue')

@section('content')
<div class="space-y-10">
    <section class="rounded-[2rem] bg-white p-8 shadow-[0_30px_80px_-45px_rgba(15,23,42,0.12)]">
        <div class="grid gap-6 lg:grid-cols-[0.9fr_0.7fr] lg:items-center">
            <div>
                <span class="inline-flex rounded-full bg-indigo-100 px-4 py-2 text-sm font-semibold text-indigo-700">Nouveau ticket</span>
                <h1 class="mt-4 section-title text-slate-900">Obtenez votre numéro de file d'attente.</h1>
                <p class="section-subtitle">Remplissez vos informations, choisissez le service souhaité et recevez votre ticket virtuel immédiatement.</p>
            </div>
            <div class="rounded-[1.75rem] border border-slate-200/80 bg-slate-50 p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">Temps estimé</p>
                <p class="mt-4 text-4xl font-semibold text-slate-900">10-15 min</p>
                <div class="mt-6 rounded-3xl bg-white p-4 text-sm text-slate-600 shadow-sm">
                    <p class="font-semibold text-slate-900">Notifications</p>
                    <p class="mt-2 text-slate-600">Vous recevrez une notification SMS/Email 5 minutes avant votre tour.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="card-panel">
        <form action="{{ route('client.tickets.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="form-field">
                    <label for="full_name" class="form-label">Nom complet <span class="text-rose-500">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" class="form-input" placeholder="Votre nom complet" required />
                </div>
                <div class="form-field">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="adresse@mail.com" />
                </div>
                <div class="form-field">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="+216 00 000 000" />
                </div>
                <div class="form-field">
                    <label for="country" class="form-label">Pays <span class="text-rose-500">*</span></label>
                    <select id="country" name="country" class="form-select" required>
                        <option value="" disabled selected>Choisir un pays</option>
                        <option value="Tunisie">Tunisie</option>
                        <option value="France">France</option>
                        <option value="Maroc">Maroc</option>
                        <option value="Algérie">Algérie</option>
                        <option value="Canada">Canada</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div class="lg:col-span-2 form-field">
                    <label for="service_id" class="form-label">Service <span class="text-rose-500">*</span></label>
                    <select id="service_id" name="service_id" class="form-select" required>
                        <option value="" disabled selected>Choisir un service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
                <a href="{{ route('home') }}" class="btn-secondary w-full justify-center sm:w-auto">Retour</a>
                <button type="submit" class="btn-primary w-full justify-center sm:w-auto">Obtenir mon ticket</button>
            </div>
        </form>
    </section>
</div>

@endsection
