@extends('layouts.app')

@section('title', 'Éditer membre - Coach')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Éditer le profil</h1>
            <p class="text-gray-600 mt-1">{{ $member->first_name }} {{ $member->last_name }}</p>
        </div>
        <a href="{{ route('coach.members.show', $member->id) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('coach.members.update', $member->id) }}" method="POST" class="bg-white rounded-xl shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Prénom -->
            <x-form-field
                label="Prénom"
                name="first_name"
                type="text"
                value="{{ $member->first_name }}"
                placeholder="Ex: Jean"
                required />

            <!-- Nom -->
            <x-form-field
                label="Nom"
                name="last_name"
                type="text"
                value="{{ $member->last_name }}"
                placeholder="Ex: Dupont"
                required />

            <!-- Email -->
            <x-form-field
                label="Email"
                name="email"
                type="email"
                value="{{ $member->email }}"
                placeholder="exemple@gym.com"
                required />

            <!-- Téléphone -->
            <x-form-field
                label="Téléphone"
                name="phone"
                type="tel"
                value="{{ $member->phone }}"
                placeholder="06 12 34 56 78" />

            <!-- Date de naissance -->
            <x-form-field
                label="Date de naissance"
                name="birth_date"
                type="date"
                value="{{ $member->birth_date?->format('Y-m-d') }}" />

            <!-- Genre -->
            <x-form-field
                label="Genre"
                name="gender"
                type="select"
                value="{{ $member->gender }}">
                <option value="">-- Sélectionner --</option>
                <option value="male">Homme</option>
                <option value="female">Femme</option>
                <option value="other">Autre</option>
            </x-form-field>

            <!-- Adresse (2 colonnes) -->
            <div class="md:col-span-2">
                <x-form-field
                    label="Adresse"
                    name="address"
                    type="text"
                    value="{{ $member->address }}"
                    placeholder="123 Rue de la Paix" />
            </div>

            <!-- Contact d'urgence (2 colonnes) -->
            <div class="md:col-span-2">
                <x-form-field
                    label="Contact d'urgence"
                    name="emergency_contact"
                    type="text"
                    value="{{ $member->emergency_contact }}"
                    placeholder="Nom et téléphone" />
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-check mr-2"></i>
                Enregistrer
            </button>
            <a href="{{ route('coach.members.show', $member->id) }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition flex items-center">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </a>
        </div>

        @if ($errors->any())
            <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="font-bold text-red-700 mb-2">Erreurs de validation:</p>
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>
@endsection
