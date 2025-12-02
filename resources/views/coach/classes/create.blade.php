@extends('layouts.app')

@section('title', 'Créer une classe - Coach')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Créer une nouvelle classe</h1>
            <p class="text-gray-600 mt-1">Remplissez les détails de votre classe</p>
        </div>
        <a href="{{ route('coach.classes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <form action="{{ route('coach.classes.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nom de la classe -->
            <x-form-field
                label="Nom de la classe"
                name="name"
                type="text"
                value="{{ old('name') }}"
                placeholder="Ex: Yoga Avancé"
                required />

            <!-- Type -->
            <x-form-field
                label="Type de classe"
                name="type"
                type="text"
                value="{{ old('type') }}"
                placeholder="Ex: Yoga, Pilates, Cardio"
                required />

            <!-- Niveau -->
            <x-form-field
                label="Niveau"
                name="level"
                type="select"
                value="{{ old('level') }}">
                <option value="">-- Sélectionner --</option>
                <option value="beginner">Débutant</option>
                <option value="intermediate">Intermédiaire</option>
                <option value="advanced">Avancé</option>
                <option value="expert">Expert</option>
            </x-form-field>

            <!-- Durée -->
            <x-form-field
                label="Durée (minutes)"
                name="duration"
                type="number"
                value="{{ old('duration', 60) }}"
                placeholder="60"
                required />

            <!-- Capacité max -->
            <x-form-field
                label="Capacité maximale"
                name="max_participants"
                type="number"
                value="{{ old('max_participants', 20) }}"
                placeholder="20"
                required />

            <!-- Jour de la semaine -->
            <x-form-field
                label="Jour de la semaine"
                name="schedule_day"
                type="select"
                value="{{ old('schedule_day') }}">
                <option value="">-- Sélectionner --</option>
                <option value="Lundi">Lundi</option>
                <option value="Mardi">Mardi</option>
                <option value="Mercredi">Mercredi</option>
                <option value="Jeudi">Jeudi</option>
                <option value="Vendredi">Vendredi</option>
                <option value="Samedi">Samedi</option>
                <option value="Dimanche">Dimanche</option>
            </x-form-field>

            <!-- Heure de début -->
            <x-form-field
                label="Heure de début"
                name="start_time"
                type="time"
                value="{{ old('start_time') }}" />
        </div>

        <!-- Description (2 colonnes) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" 
                      class="w-full rounded-lg border-2 border-gray-200 px-4 py-2 focus:border-blue-500 focus:outline-none transition @error('description') border-red-500 @enderror"
                      rows="4"
                      placeholder="Décrivez votre classe...">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Créer la classe
            </button>
            <a href="{{ route('coach.classes.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition flex items-center">
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
