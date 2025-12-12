@extends('layouts.app')

@section('title', 'Créer un exercice - Coach')
@section('page-title', 'Créer un exercice')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Créer un nouvel exercice</h1>
                <p class="text-gray-600 mt-2">Ajoutez un exercice à vos programmes d'entraînement</p>
            </div>
            <a href="{{ route('coach.exercises.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <form action="{{ route('coach.exercises.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Programme -->
                <div class="md:col-span-2">
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-running mr-2 text-blue-500"></i>Programme d'entraînement
                    </label>
                    <select name="program_id" id="program_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Sélectionner un programme</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('program_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nom de l'exercice -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-dumbbell mr-2 text-green-500"></i>Nom de l'exercice
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ex: Développé couché">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-purple-500"></i>Description
                    </label>
                    <textarea name="description" id="description" rows="4" required 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Décrivez l'exercice, la technique, les muscles ciblés...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Séries -->
                <div>
                    <label for="sets" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-orange-500"></i>Nombre de séries
                    </label>
                    <input type="number" name="sets" id="sets" value="{{ old('sets', 3) }}" min="1" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('sets')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Répétitions -->
                <div>
                    <label for="reps" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-redo mr-2 text-red-500"></i>Répétitions par série
                    </label>
                    <input type="number" name="reps" id="reps" value="{{ old('reps', 10) }}" min="1" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('reps')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poids -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight-hanging mr-2 text-gray-600"></i>Poids (kg) - Optionnel
                    </label>
                    <input type="number" name="weight" id="weight" value="{{ old('weight') }}" min="0" step="0.5" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ex: 50">
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Temps de repos -->
                <div>
                    <label for="rest_seconds" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-yellow-500"></i>Temps de repos (secondes)
                    </label>
                    <input type="number" name="rest_seconds" id="rest_seconds" value="{{ old('rest_seconds', 60) }}" min="0" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('rest_seconds')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jour du programme -->
                <div>
                    <label for="day_number" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-day mr-2 text-indigo-500"></i>Jour du programme
                    </label>
                    <input type="number" name="day_number" id="day_number" value="{{ old('day_number', 1) }}" min="1" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('day_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('coach.exercises.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i>Créer l'exercice
                </button>
            </div>
        </form>
    </div>
</div>
@endsection