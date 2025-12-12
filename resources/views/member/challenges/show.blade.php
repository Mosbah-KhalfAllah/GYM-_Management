@extends('layouts.app')

@section('title', 'Détails challenge - Membre')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <!-- En-tête -->
    <div class="mb-8">
        <a href="{{ route('member.challenges.index') }}" class="text-indigo-600 hover:text-indigo-700 mb-4 inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux défis
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $challenge->name }}</h1>
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
        <!-- Contenu principal -->
        <div class="lg:col-span-2">
            <!-- Description du défi -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">À propos de ce défi</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-600">Type</p>
                        <p class="font-semibold">{{ $challenge->challenge_type }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Difficulté</p>
                        <p class="font-semibold">
                            <span class="px-3 py-1 rounded-full text-sm font-medium @if($challenge->difficulty === 'easy') bg-green-100 text-green-800 @elseif($challenge->difficulty === 'medium') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                {{ $challenge->difficulty === 'easy' ? 'Facile' : ($challenge->difficulty === 'medium' ? 'Moyen' : 'Difficile') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durée</p>
                        <p class="font-semibold">{{ $challenge->start_date->format('d/m/Y') }} - {{ $challenge->end_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Points à gagner</p>
                        <p class="font-semibold text-lg text-purple-600">{{ $challenge->max_points }} pts</p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700 whitespace-pre-line">{{ $challenge->description }}</p>
                </div>
            </div>

            <!-- Participation de l'utilisateur -->
            @if($participation)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 text-indigo-900">Votre progression</h2>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600">Points gagnés</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $participation->points_earned }} pts</p>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <p class="text-sm text-gray-600">Progression</p>
                            <p class="text-2xl font-bold text-indigo-600">{{ $participation->current_progress ?? 0 }}%</p>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $participation->current_progress ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="mt-4">
                        @if($participation->completed)
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                Défi terminé
                            </span>
                        @else
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-hourglass-half mr-2"></i>
                                En cours
                            </span>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <p class="text-yellow-800">Vous n'êtes pas inscrit à ce défi</p>
                </div>
            @endif

            <!-- Critères de succès -->
            @if($challenge->success_criteria)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Critères de succès</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $challenge->success_criteria }}</p>
                </div>
            @endif

            <!-- Classement du défi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Classement</h2>
                
                @if($challengeLeaderboard->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Rang</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900">Participant</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Progression</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Points</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($challengeLeaderboard as $index => $entry)
                                    <tr class="@if($entry->member_id === auth()->id()) bg-indigo-50 @endif">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            @if($index == 0)
                                                <span class="flex w-6 h-6 rounded-full bg-yellow-400 text-white items-center justify-center text-xs">🥇</span>
                                            @elseif($index == 1)
                                                <span class="flex w-6 h-6 rounded-full bg-gray-400 text-white items-center justify-center text-xs">🥈</span>
                                            @elseif($index == 2)
                                                <span class="flex w-6 h-6 rounded-full bg-orange-400 text-white items-center justify-center text-xs">🥉</span>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <span class="font-medium">{{ $entry->member->full_name ?? 'Anonyme' }}</span>
                                            @if($entry->member_id === auth()->id())
                                                <span class="ml-2 inline-block px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-800">Vous</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900">
                                            <div class="flex items-center justify-end gap-2">
                                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $entry->current_progress ?? 0 }}%"></div>
                                                </div>
                                                <span>{{ $entry->current_progress ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-900 font-semibold">
                                            <span class="text-purple-600">{{ $entry->points_earned ?? 0 }} pts</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Aucun participant pour l'instant</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <!-- Informations du défi -->
            <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                <h2 class="text-lg font-bold mb-4">Informations</h2>
                
                <div class="space-y-3 mb-6">
                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Statut</p>
                        @if($challenge->is_active && now() < $challenge->end_date)
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-1">
                                Actif
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 mt-1">
                                Inactif
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Participants</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $challenge->participants()->count() }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-600 uppercase font-semibold">Temps restant</p>
                        <p class="font-semibold mt-1">
                            @if(now() < $challenge->end_date)
                                {{ $challenge->end_date->diffInDays(now()) }} jours
                            @else
                                Terminé
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Bouton d'action -->
                @if(!$participation && $challenge->is_active && now() < $challenge->end_date)
                    <form action="{{ route('member.challenges.join', $challenge->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Rejoindre ce défi
                        </button>
                    </form>
                @elseif($participation && !$participation->completed)
                    <div class="p-4 bg-indigo-50 rounded-lg text-indigo-900 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Vous avez rejoint ce défi et êtes en progression
                    </div>
                @elseif($participation && $participation->completed)
                    <div class="p-4 bg-green-50 rounded-lg text-green-900 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Vous avez complété ce défi
                    </div>
                @endif

                <!-- Récompenses -->
                @if($challenge->rewards)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-bold mb-3">Récompenses</h3>
                        <p class="text-sm text-gray-700">{{ $challenge->rewards }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

