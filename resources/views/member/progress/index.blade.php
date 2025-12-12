@extends('layouts.app')

@section('title', 'Progression - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Ma Progression</h1>
        <p class="opacity-90">Suivez vos statistiques et votre évolution</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium">Présences ce mois</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['monthly_attendance'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium">Présences cette année</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['yearly_attendance'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium">Heures d'entraînement</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ round($stats['total_workout_hours']) }}<span class="text-lg">h</span></p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm font-medium">Exercices complétés</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['exercises_completed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-pink-500">
            <p class="text-gray-600 text-sm font-medium">Programmes terminés</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['programs_completed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-medium">Challenges gagnés</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['challenges_won'] }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Présences (30 derniers jours)</h3>
            <div class="space-y-3">
                @foreach($attendanceChart as $data)
                    @if($data['value'] > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 min-w-12">{{ $data['label'] }}</span>
                            <div class="flex-1 mx-4 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" 
                                     style="width: {{ max($data['value'] * 25, 5) }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 min-w-8 text-right">{{ $data['value'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Exercise Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Exercices complétés (30 derniers jours)</h3>
            <div class="space-y-3">
                @foreach($exerciseChart as $data)
                    @if($data['value'] > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 min-w-12">{{ $data['label'] }}</span>
                            <div class="flex-1 mx-4 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" 
                                     style="width: {{ max($data['value'] * 15, 5) }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 min-w-8 text-right">{{ $data['value'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Weight Tracking Chart -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Suivi du poids</h3>
        <div class="space-y-3">
            @foreach($weightChart as $data)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600 min-w-12">{{ $data['label'] }}</span>
                    <div class="flex-1 mx-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-800">{{ $data['value'] }} kg</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Goals Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-target mr-3 text-red-600"></i>
            Mes Objectifs
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($goals as $goal)
                @php
                    $percentage = min(($goal['current'] / $goal['target']) * 100, 100);
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ $goal['title'] }}</h3>
                    <p class="text-2xl font-bold text-gray-800 mb-2">{{ round($percentage) }}%</p>
                    <div class="w-full bg-gray-300 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $goal['current'] }}/{{ $goal['target'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Program Progress Section -->
    @if($programProgress->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line mr-3 text-indigo-600"></i>
                Progression des programmes
            </h2>
            <div class="space-y-4">
                @foreach($programProgress as $program)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $program['title'] }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $program['start_date'] }} - {{ $program['end_date'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $program['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($program['status'] === 'paused' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($program['status']) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Jour {{ $program['current_day'] }}/{{ $program['duration'] }}</span>
                                <span class="text-sm font-bold text-gray-800">{{ round($program['progress']) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2.5">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-2.5 rounded-full" 
                                     style="width: {{ $program['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

