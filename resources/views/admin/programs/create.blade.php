@extends('layouts.app')

@section('title', 'Créer un programme')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Créer un programme de fitness</h1>
            <a href="{{ route('admin.programs.index') }}" class="text-gray-600 hover:text-gray-900">✕</a>
        </div>

        <form action="{{ route('admin.programs.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf

            <x-form-field label="Coach responsable" name="coach_id" type="select" placeholder="Sélectionner un coach" required>
                @foreach($coaches as $coach)
                    <option value="{{ $coach->id }}">{{ $coach->first_name }} {{ $coach->last_name }}</option>
                @endforeach
            </x-form-field>

            <x-form-field label="Titre du programme" name="title" type="text" placeholder="Ex: Prise de masse musculaire" required />

            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="4" class="block w-full border rounded-lg p-3 focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror" placeholder="Décrivez les objectifs et le contenu du programme">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <x-form-field label="Niveau" name="level" type="select" required>
                    <option value="beginner">Débutant</option>
                    <option value="intermediate">Intermédiaire</option>
                    <option value="advanced">Avancé</option>
                </x-form-field>

                <x-form-field label="Durée (jours)" name="duration_days" type="number" min="1" placeholder="30" required />

                <x-form-field label="Objectif" name="goal" type="select" required>
                    <option value="weight_loss">Perte de poids</option>
                    <option value="muscle_gain">Prise musculaire</option>
                    <option value="endurance">Endurance</option>
                    <option value="flexibility">Flexibilité</option>
                </x-form-field>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Créer le programme</button>
                <a href="{{ route('admin.programs.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
