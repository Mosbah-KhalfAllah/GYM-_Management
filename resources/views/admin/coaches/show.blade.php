@extends('layouts.app')

@section('title', 'Profil du Coach')
@section('page-title', 'Profil du Coach')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                {{ substr($coach->first_name, 0, 1) }}{{ substr($coach->last_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $coach->full_name() }}</h1>
                <p class="text-gray-600">Coach #{{ $coach->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.coaches.index') }}" class="px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
            <a href="{{ route('admin.coaches.edit', $coach) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
            <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver ce coach ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-user-slash"></i>
                    Désactiver
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $coach->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="font-medium">{{ $coach->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date de naissance</p>
                        <p class="font-medium">{{ $coach->birth_date ? $coach->birth_date->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Genre</p>
                        <p class="font-medium">
                            @switch($coach->gender)
                                @case('male')
                                    Homme
                                    @break
                                @case('female')
                                    Femme
                                    @break
                                @case('other')
                                    Autre
                                    @break
                                @default
                                    Non renseigné
                            @endswitch
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Adresse</p>
                        <p class="font-medium">{{ $coach->address ?? 'Non renseignée' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Contact d'urgence</p>
                        <p class="font-medium">{{ $coach->emergency_contact ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Programs Created -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Programmes créés</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $coach->createdPrograms()->count() }}
                    </span>
                </div>
                @if($coach->createdPrograms()->count() > 0)
                    <div class="space-y-3">
                        @foreach($coach->createdPrograms as $program)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $program->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $program->description }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                            <span><i class="fas fa-graduation-cap mr-1"></i>{{ ucfirst($program->level) }}</span>
                                            <span><i class="fas fa-calendar mr-1"></i>{{ $program->duration_days }} jours</span>
                                            <span><i class="fas fa-bullseye mr-1"></i>{{ ucfirst($program->goal) }}</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $program->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $program->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun programme créé</p>
                @endif
            </div>

            <!-- Classes Coached -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Cours dispensés</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $coach->classesCoached()->count() }}
                    </span>
                </div>
                @if($coach->classesCoached()->count() > 0)
                    <div class="space-y-3">
                        @foreach($coach->classesCoached as $class)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $class->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $class->description }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                            <span><i class="fas fa-users mr-1"></i>Max: {{ $class->max_participants }} participants</span>
                                            <span><i class="fas fa-clock mr-1"></i>{{ $class->duration_minutes }} min</span>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $class->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $class->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucun cours dispensé</p>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Compte</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $coach->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $coach->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Coach depuis</span>
                        <span class="font-medium">{{ $coach->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dernière modification</span>
                        <span class="font-medium">{{ $coach->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Programmes créés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $coach->createdPrograms()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cours dispensés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $coach->classesCoached()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Membres total</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $coach->createdPrograms()->withCount('members')->get()->sum('members_count') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

