@extends('layouts.app')

@section('title', 'Programmes - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Mes Programmes</h1>
        <p class="opacity-90">Gérez vos programmes d'entraînement</p>
    </div>

    <!-- Active Program -->
    @if($activeProgram)
        <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 rounded-lg p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-play-circle mr-3 text-green-600"></i>
                        Programme actif
                    </h2>
                    <p class="text-gray-600 mt-1">{{ $activeProgram->title }}</p>
                </div>
                <span class="px-4 py-2 bg-green-600 text-white rounded-full font-medium">
                    Actif
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Niveau</p>
                    <p class="text-2xl font-bold text-gray-800">{{ ucfirst($activeProgram->level) }}</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Jour actuel</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeProgram->pivot->current_day }}/{{ $activeProgram->duration_days }}</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Progression</p>
                    <p class="text-2xl font-bold text-gray-800">{{ round($activeProgram->pivot->completion_percentage) }}%</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-gray-600 text-sm">Entraîneur</p>
                    <p class="text-lg font-bold text-gray-800">{{ $activeProgram->coach->first_name }}</p>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-700 font-medium">Progression globale</span>
                    <span class="text-gray-600">{{ round($activeProgram->pivot->completion_percentage) }}%</span>
                </div>
                <div class="w-full bg-gray-300 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-3 rounded-full" 
                         style="width: {{ round($activeProgram->pivot->completion_percentage) }}%"></div>
                </div>
            </div>

            <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Début: {{ \Carbon\Carbon::parse($activeProgram->pivot->start_date)->format('d/m/Y') }} | 
                Fin estimée: {{ \Carbon\Carbon::parse($activeProgram->pivot->end_date)->format('d/m/Y') }}
            </p>
        </div>
    @else
        <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-6 text-center">
            <i class="fas fa-search text-4xl text-blue-600 mb-3"></i>
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Aucun programme actif</h2>
            <p class="text-gray-600 mb-4">Commencez un programme pour débuter votre parcours de fitness</p>
            <a href="#programmes-disponibles" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                <i class="fas fa-arrow-down mr-2"></i>Voir les programmes disponibles
            </a>
        </div>
    @endif

    <!-- Available Programs Section -->
    <div id="programmes-disponibles" class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-dumbbell mr-3 text-blue-600"></i>
            Programmes disponibles ({{ $availablePrograms->count() }})
        </h2>

        @if($availablePrograms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availablePrograms as $program)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="mb-3">
                            <h3 class="font-semibold text-gray-800 text-lg">{{ $program->title }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-user-tie mr-2"></i>
                                {{ $program->coach->first_name }} {{ $program->coach->last_name }}
                            </p>
                        </div>

                        <p class="text-sm text-gray-700 mb-3">{{ Str::limit($program->description, 60) }}</p>

                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><i class="fas fa-layer-group mr-2"></i><strong>Niveau:</strong> {{ ucfirst($program->level) }}</p>
                            <p><i class="fas fa-calendar-days mr-2"></i><strong>Durée:</strong> {{ $program->duration_days }} jours</p>
                            <p><i class="fas fa-list mr-2"></i><strong>Exercices:</strong> {{ $program->exercises()->count() }}</p>
                        </div>

                        <form action="{{ route('member.program.enroll', $program) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i class="fas fa-play mr-2"></i>Démarrer
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Aucun programme disponible</p>
            </div>
        @endif
    </div>

    <!-- Completed Programs Section -->
    @if($programHistory->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-trophy mr-3 text-yellow-600"></i>
                Programmes complétés ({{ $programHistory->count() }})
            </h2>

            <div class="space-y-3">
                @foreach($programHistory as $program)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $program->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $program->coach->first_name }} {{ $program->coach->last_name }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($program->pivot->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($program->pivot->end_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="mb-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-check mr-1"></i>Complété
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-green-600">{{ round($program->pivot->completion_percentage) }}%</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

