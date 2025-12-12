@extends('layouts.app')

@section('title', 'Détails exercice - Coach')
@section('page-title', 'Détails de l\'exercice')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $exercise->name }}</h1>
                <p class="text-gray-600 mt-2">Programme : {{ $exercise->program->title }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('coach.exercises.edit', $exercise) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Modifier
                </a>
                <a href="{{ route('coach.exercises.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Informations principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-layer-group text-blue-500 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Séries</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $exercise->sets }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-redo text-green-500 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Répétitions</p>
                        <p class="text-2xl font-bold text-green-600">{{ $exercise->reps }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-weight-hanging text-purple-500 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Poids</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $exercise->weight ? $exercise->weight . ' kg' : 'Libre' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-clock text-orange-500 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Repos</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $exercise->rest_seconds }}s</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">
                <i class="fas fa-align-left mr-2 text-gray-600"></i>Description
            </h3>
            <p class="text-gray-700 leading-relaxed">{{ $exercise->description }}</p>
        </div>

        <!-- Informations supplémentaires -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-indigo-50 rounded-lg p-4">
                <h4 class="font-semibold text-indigo-900 mb-2">
                    <i class="fas fa-calendar-day mr-2"></i>Jour du programme
                </h4>
                <p class="text-indigo-700">Jour {{ $exercise->day_number }}</p>
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-running mr-2"></i>Programme associé
                </h4>
                <p class="text-blue-700">{{ $exercise->program->title }}</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Créé le {{ $exercise->created_at->format('d/m/Y à H:i') }}
                @if($exercise->updated_at != $exercise->created_at)
                    • Modifié le {{ $exercise->updated_at->format('d/m/Y à H:i') }}
                @endif
            </div>
            
            <form action="{{ route('coach.exercises.destroy', $exercise) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet exercice ?\n\nCette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-trash mr-2"></i>Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection