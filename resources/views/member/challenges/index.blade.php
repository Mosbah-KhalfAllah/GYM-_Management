@extends('layouts.app')

@section('title', 'Challenges - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Challenges</h1>
        <p class="opacity-90">Relevez des défis et gagnez des points!</p>
    </div>

    <!-- User Points -->
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Vos points totaux</h2>
                <p class="text-gray-600 mt-1">Cumulés dans tous les challenges</p>
            </div>
            <div class="text-right">
                <p class="text-5xl font-bold text-yellow-600">{{ $userPoints }}</p>
                <p class="text-gray-600 mt-2"><i class="fas fa-star mr-1 text-yellow-500"></i>Points</p>
            </div>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-ranking-star mr-3 text-purple-600"></i>
            Classement Global
        </h2>
        <div class="space-y-3">
            @forelse($leaderboard as $index => $entry)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r {{ $index == 0 ? 'from-yellow-50 to-yellow-100' : ($index == 1 ? 'from-gray-50 to-gray-100' : ($index == 2 ? 'from-orange-50 to-orange-100' : 'hover:bg-gray-50')) }} rounded-lg transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-full {{ $index == 0 ? 'bg-yellow-500' : ($index == 1 ? 'bg-gray-400' : ($index == 2 ? 'bg-orange-600' : 'bg-blue-500')) }} text-white flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $entry->member->full_name() }}</p>
                            <p class="text-sm text-gray-600">Membre depuis {{ $entry->member->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">{{ $entry->total_points }} <span class="text-sm text-gray-600">pts</span></p>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Aucun participant au classement</p>
            @endforelse
        </div>
    </div>

    <!-- Active Challenges -->
    @if($activeChallenges->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-fire mr-3 text-red-600"></i>
                Challenges en cours ({{ $activeChallenges->count() }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeChallenges as $challenge)
                    @php
                        $participation = $challenge->participants->first();
                        $progress = ($participation->current_progress / $challenge->target_value) * 100;
                    @endphp
                    <div class="border-2 border-red-300 rounded-lg p-4 bg-red-50 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg">{{ $challenge->title }}</h3>
                                <p class="text-xs text-gray-600 mt-1 uppercase font-medium">{{ $challenge->type }}</p>
                            </div>
                            <span class="px-3 py-1 bg-red-200 text-red-800 rounded-full text-xs font-medium">
                                Actif
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($challenge->description, 80) }}</p>

                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Progression</span>
                                <span class="text-sm font-bold text-gray-800">{{ round($progress) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" 
                                     style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-2">{{ $participation->current_progress }}/{{ $challenge->target_value }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="bg-white rounded p-2 text-center">
                                <p class="text-xs text-gray-600">Points</p>
                                <p class="text-lg font-bold text-gray-800">{{ $challenge->points_reward }}</p>
                            </div>
                            <div class="bg-white rounded p-2 text-center">
                                <p class="text-xs text-gray-600">Jours restants</p>
                                <p class="text-lg font-bold text-gray-800">{{ now()->diffInDays($challenge->end_date) }}</p>
                            </div>
                        </div>

                        <a href="{{ route('member.challenges.show', $challenge) }}" class="w-full block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center font-medium">
                            <i class="fas fa-arrow-right mr-2"></i>Voir le détail
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Challenges -->
    @if($availableChallenges->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-star mr-3 text-blue-600"></i>
                Challenges disponibles ({{ $availableChallenges->count() }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableChallenges as $challenge)
                    <div class="border border-blue-200 rounded-lg p-4 bg-blue-50 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800 text-lg">{{ $challenge->title }}</h3>
                                <p class="text-xs text-gray-600 mt-1 uppercase font-medium">{{ $challenge->type }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-medium">
                                Disponible
                            </span>
                        </div>

                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($challenge->description, 80) }}</p>

                        <div class="grid grid-cols-2 gap-2 mb-4">
                            <div class="bg-white rounded p-2 text-center">
                                <p class="text-xs text-gray-600">Objectif</p>
                                <p class="text-lg font-bold text-gray-800">{{ $challenge->target_value }}</p>
                            </div>
                            <div class="bg-white rounded p-2 text-center">
                                <p class="text-xs text-gray-600">Points</p>
                                <p class="text-lg font-bold text-gray-800">{{ $challenge->points_reward }}</p>
                            </div>
                        </div>

                        <form action="{{ route('member.challenges.join', $challenge) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i class="fas fa-play mr-2"></i>Rejoindre
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Completed Challenges -->
    @if($completedChallenges->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-trophy mr-3 text-yellow-600"></i>
                Challenges complétés ({{ $completedChallenges->total() }})
            </h2>
            <div class="space-y-3">
                @foreach($completedChallenges as $challenge)
                    @php
                        $participation = $challenge->participants->first();
                    @endphp
                    <div class="border border-green-200 rounded-lg p-4 bg-green-50 hover:bg-green-100 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $challenge->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-check mr-2 text-green-600"></i>
                                    {{ \Carbon\Carbon::parse($challenge->end_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-full text-xs font-medium mb-2">
                                    <i class="fas fa-crown mr-1"></i>Complété
                                </span>
                                <p class="text-lg font-bold text-yellow-600">
                                    <i class="fas fa-star mr-1"></i>{{ $challenge->points_reward }} pts
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $completedChallenges->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

