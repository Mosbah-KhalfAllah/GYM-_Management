{{-- resources/views/coach/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de bord Coach')
@section('page-title', 'Tableau de bord Coach')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Programs -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Programmes</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_programs'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-check-circle mr-1"></i> {{ $stats['active_programs'] }} actifs
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-running text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Assigned Members -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Membres Assignés</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['assigned_members'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-user-check mr-1"></i> Sous ma supervision
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Cours à Venir</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['upcoming_classes'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-calendar-alt mr-1"></i> Cette semaine
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Cours</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_classes'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-history mr-1"></i> Tous les temps
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-dumbbell text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Classes -->
        <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Cours à Venir</h3>
                <a href="{{ route('coach.classes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tout
                </a>
            </div>
            <div class="space-y-4">
                @foreach($upcomingClasses as $class)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $class->schedule_time->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $class->registered_count >= $class->capacity ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $class->registered_count }}/{{ $class->capacity }}
                                </span>
                                <p class="text-sm text-gray-600 mt-2">{{ $class->duration_minutes }} min</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $class->description }}</span>
                                <a href="{{ route('coach.classes.show', $class) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Assigned Members -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Membres Assignés</h3>
                <a href="{{ route('coach.members.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tout
                </a>
            </div>
            <div class="space-y-4">
                @foreach($assignedMembers as $member)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $member->full_name }}</p>
                                <p class="text-sm text-gray-600">{{ $member->membership->type ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($member->activeProgram)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-running mr-1"></i> Programme actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-pause mr-1"></i> Pas de programme
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Attendances -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Présences Récentes</h3>
            <a href="{{ route('coach.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir tout
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure d'entrée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentAttendances as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($attendance->user->first_name, 0, 1) }}{{ substr($attendance->user->last_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $attendance->user->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendance->check_in->format('H:i') }}</div>
                                <div class="text-sm text-gray-500">{{ $attendance->check_in->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->user->activeProgram)
                                    <span class="text-sm font-medium text-gray-900">{{ $attendance->user->activeProgram->title }}</span>
                                @else
                                    <span class="text-sm text-gray-500">Aucun programme</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->check_out)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Sorti
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-clock mr-1"></i> En salle
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('coach.programs.create') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 border border-gray-200 hover:border-blue-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white mb-4">
                    <i class="fas fa-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Créer un Programme</h3>
                <p class="text-gray-600">Créez un nouveau programme d'entraînement personnalisé</p>
            </div>
        </a>
        <a href="{{ route('coach.classes.create') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 border border-gray-200 hover:border-blue-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white mb-4">
                    <i class="fas fa-calendar-plus text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Planifier un Cours</h3>
                <p class="text-gray-600">Planifiez un nouveau cours collectif</p>
            </div>
        </a>
        <a href="{{ route('coach.attendance.scanner') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1 border border-gray-200 hover:border-blue-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white mb-4">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Scanner Présence</h3>
                <p class="text-gray-600">Scanner les QR codes pour enregistrer les présences</p>
            </div>
        </a>
    </div>
</div>
@endsection