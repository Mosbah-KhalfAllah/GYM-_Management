@extends('layouts.app')

@section('title', 'Profil - Membre')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et vos préférences</p>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_workouts'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Séances complétées</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-green-600">{{ $stats['total_exercises'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Exercices effectués</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_classes'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Classes suivies</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['challenge_points'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Points de défis</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations personnelles -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-6">Informations personnelles</h2>
                
                <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-form-field label="Prénom" name="first_name" :value="$user->first_name" />
                        <x-form-field label="Nom" name="last_name" :value="$user->last_name" />
                    </div>

                    <x-form-field label="Email" name="email" type="email" :value="$user->email" />
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-form-field label="Téléphone" name="phone" :value="$user->phone" />
                        <x-form-field label="Genre" name="gender" type="select" :value="$user->gender">
                            <option value="">-- Sélectionner --</option>
                            <option value="male" @selected($user->gender === 'male')>Homme</option>
                            <option value="female" @selected($user->gender === 'female')>Femme</option>
                            <option value="other" @selected($user->gender === 'other')>Autre</option>
                        </x-form-field>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <x-form-field label="Date de naissance" name="birth_date" type="date" :value="$user->birth_date" />
                        <x-form-field label="Contact d'urgence" name="emergency_contact" :value="$user->emergency_contact" />
                    </div>

                    <x-form-field label="Adresse" name="address" :value="$user->address" />

                    <x-form-field label="Photo de profil" name="avatar" type="file" accept="image/*" />

                    <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-6">Sécurité</h2>
                
                <form action="{{ route('member.profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <x-form-field label="Mot de passe actuel" name="current_password" type="password" />
                    <x-form-field label="Nouveau mot de passe" name="password" type="password" />
                    <x-form-field label="Confirmer le mot de passe" name="password_confirmation" type="password" />

                    <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations d'adhésion et progression récente -->
        <div>
            <!-- Adhésion -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Mon adhésion</h2>
                
                @if($user->membership)
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-semibold">{{ $user->membership->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Statut</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium @if($user->membership->status === 'active') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $user->membership->status === 'active' ? 'Actif' : 'Expiré' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date d'expiration</p>
                            <p class="font-semibold">{{ $user->membership->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Prix mensuel</p>
                            <p class="font-semibold">{{ number_format($user->membership->price, 2) }} €</p>
                        </div>
                    </div>

                    <form action="{{ route('member.profile.update-membership') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="auto_renewal" @checked($user->membership->auto_renewal) class="w-4 h-4 rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Renouvellement automatique</span>
                        </label>

                        <button type="submit" class="mt-3 w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Mettre à jour
                        </button>
                    </form>
                @else
                    <p class="text-gray-600">Aucune adhésion active</p>
                @endif
            </div>

            <!-- Progression récente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Progression récente</h2>
                
                @if($recentProgress->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentProgress as $log)
                            <div class="flex items-center justify-between p-2 border-b border-gray-100">
                                <div>
                                    <p class="font-medium text-sm">{{ $log->exercise->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($log->reps) {{ $log->reps }} reps @endif
                                        @if($log->weight) - {{ $log->weight }} kg @endif
                                    </p>
                                </div>
                                <span class="text-xs text-green-600 font-medium">✓</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-sm">Aucune progression enregistrée</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
