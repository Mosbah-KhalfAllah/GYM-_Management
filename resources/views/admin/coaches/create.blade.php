@extends('layouts.app')

@section('title', 'Ajouter un coach')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Ajouter un coach</h1>
            <a href="{{ route('admin.coaches.index') }}" class="text-gray-600 hover:text-gray-900">✕</a>
        </div>

        <form action="{{ route('admin.coaches.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Prénom" name="first_name" type="text" placeholder="Ex: Jean" pattern="^[a-zA-ZÀ-ÿ\s'\-]+$" title="Le prénom ne doit contenir que des lettres" required />
                <x-form-field label="Nom" name="last_name" type="text" placeholder="Ex: Dupont" pattern="^[a-zA-ZÀ-ÿ\s'\-]+$" title="Le nom ne doit contenir que des lettres" required />
            </div>

            <x-form-field label="Email" name="email" type="email" placeholder="coach@gym.com" required />
            <x-form-field label="Téléphone" name="phone" type="tel" pattern="^[\d\s\+\-\(\)]+$" placeholder="06 12 34 56 78" />
            <x-form-field label="Spécialisation" name="specialization" type="text" maxlength="100" placeholder="Ex: CrossFit, Yoga, Musculation" />
            <x-form-field label="Années d'expérience" name="experience_years" type="number" min="0" max="60" />
            <x-form-field label="Mot de passe" name="password" type="password" minlength="8" placeholder="Min. 8 caractères" required />
            <x-form-field label="Confirmer le mot de passe" name="password_confirmation" type="password" minlength="8" placeholder="Répétez le mot de passe" required />

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Créer le coach</button>
                <a href="{{ route('admin.coaches.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

