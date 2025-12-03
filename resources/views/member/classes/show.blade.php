@extends('layouts.app')

@section('title', 'Détails classe - Membre')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- En-tête -->
    <div class="mb-8">
        <a href="{{ route('member.classes.index') }}" class="text-indigo-600 hover:text-indigo-700 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux classes
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $class->title }}</h1>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <!-- Détails de la classe -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Informations de la classe</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Entraîneur</p>
                        <p class="font-semibold text-lg">{{ $class->coach->full_name ?? 'Non assigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Type</p>
                        <p class="font-semibold text-lg">{{ $class->class_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date et heure</p>
                        <p class="font-semibold text-lg">{{ $class->schedule_time->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durée</p>
                        <p class="font-semibold text-lg">{{ $class->duration_minutes }} minutes</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lieu</p>
                        <p class="font-semibold text-lg">{{ $class->location ?? 'Studio' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Niveau</p>
                        <p class="font-semibold text-lg">
                            <span class="px-3 py-1 rounded-full text-sm font-medium @if($class->difficulty_level === 'beginner') bg-blue-100 text-blue-800 @elseif($class->difficulty_level === 'intermediate') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                {{ $class->difficulty_level === 'beginner' ? 'Débutant' : ($class->difficulty_level === 'intermediate' ? 'Intermédiaire' : 'Avancé') }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Description -->
                @if($class->description)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700">{{ $class->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Disponibilité -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Disponibilité</h2>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 border rounded-lg">
                        <p class="text-sm text-gray-600">Capacité</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $class->capacity }}</p>
                    </div>
                    <div class="text-center p-4 border rounded-lg">
                        <p class="text-sm text-gray-600">Inscrits</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $class->registered_count }}</p>
                    </div>
                    <div class="text-center p-4 border rounded-lg">
                        <p class="text-sm text-gray-600">Places libres</p>
                        <p class="text-2xl font-bold {{ $spotsLeft > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $spotsLeft }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($class->registered_count / $class->capacity) * 100 }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ round(($class->registered_count / $class->capacity) * 100) }}% plein</p>
                </div>
            </div>

            <!-- Prérequis/Équipement -->
            @if($class->equipment_needed)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Équipement requis</h2>
                    <p class="text-gray-700">{{ $class->equipment_needed }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar - Actions -->
        <div>
            <!-- Statut et boutons -->
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <div class="mb-6">
                    @if($isBooked)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <i class="fas fa-check-circle text-green-600 text-2xl mb-2"></i>
                            <p class="font-bold text-green-800">Vous êtes inscrit</p>
                            <p class="text-sm text-green-700">à cette classe</p>
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
                            <p class="font-bold text-gray-800">Pas encore inscrit</p>
                            <p class="text-sm text-gray-600">à cette classe</p>
                        </div>
                    @endif
                </div>

                <!-- Boutons d'action -->
                @if(!$isBooked && $spotsLeft > 0)
                    <form action="{{ route('member.classes.book', $class->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            <i class="fas fa-calendar-check mr-2"></i>
                            M'inscrire à cette classe
                        </button>
                    </form>
                @elseif($isBooked)
                    <form action="{{ route('member.classes.cancel', $class->bookings()->where('member_id', auth()->id())->first()->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Annuler mon inscription
                        </button>
                    </form>
                @else
                    <button disabled class="w-full px-4 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                        <i class="fas fa-times-circle mr-2"></i>
                        Classe pleine
                    </button>
                @endif

                <!-- Informations supplémentaires -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-clock w-5 text-indigo-600"></i>
                            <span class="ml-3">{{ $class->duration_minutes }} minutes</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-map-marker-alt w-5 text-indigo-600"></i>
                            <span class="ml-3">{{ $class->location ?? 'Studio' }}</span>
                        </div>
                        @if($class->coach)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-user-tie w-5 text-indigo-600"></i>
                                <span class="ml-3">{{ $class->coach->full_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statut de la classe -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-600 mb-2">Statut</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium @if(now() < $class->schedule_time) bg-blue-100 text-blue-800 @else bg-gray-100 text-gray-800 @endif">
                        @if(now() < $class->schedule_time)
                            À venir
                        @else
                            Terminée
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
