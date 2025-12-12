@extends('layouts.app')

@section('title', 'Planning - Coach')
@section('page-title', 'Mon Planning')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mon Planning</h1>
                <p class="text-gray-600 mt-1">Gérez vos cours et programmes</p>
            </div>
            <a href="{{ route('coach.classes.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2"></i>Nouveau Cours
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Cours Aujourd'hui</p>
                        <p class="text-2xl font-bold">{{ $stats['today_classes'] }}</p>
                    </div>
                    <i class="fas fa-calendar-day text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Cours à Venir</p>
                        <p class="text-2xl font-bold">{{ $stats['upcoming_classes'] }}</p>
                    </div>
                    <i class="fas fa-clock text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Programmes Actifs</p>
                        <p class="text-2xl font-bold">{{ $stats['active_programs'] }}</p>
                    </div>
                    <i class="fas fa-running text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Membres</p>
                        <p class="text-2xl font-bold">{{ $stats['total_members'] }}</p>
                    </div>
                    <i class="fas fa-users text-2xl opacity-80"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendrier des 7 prochains jours -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-calendar-week mr-2 text-blue-500"></i>Planning des 7 prochains jours
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
            @foreach($calendarDays as $day)
            <div class="border border-gray-200 rounded-lg p-4 {{ $day['date']->isToday() ? 'bg-blue-50 border-blue-300' : 'hover:bg-gray-50' }} transition-colors">
                <div class="text-center mb-3">
                    <p class="text-sm font-medium text-gray-600">{{ ucfirst($day['day_name']) }}</p>
                    <p class="text-2xl font-bold {{ $day['date']->isToday() ? 'text-blue-600' : 'text-gray-900' }}">{{ $day['day_number'] }}</p>
                    <p class="text-xs text-gray-500">{{ $day['date']->format('M Y') }}</p>
                </div>
                
                @if($day['class_count'] > 0)
                    <div class="space-y-2">
                        @foreach($day['classes'] as $class)
                        <div class="bg-white border border-gray-200 rounded p-2 text-xs">
                            <p class="font-medium text-gray-900">{{ $class->name }}</p>
                            <p class="text-gray-600">{{ $class->schedule_time->format('H:i') }}</p>
                            <p class="text-gray-500">{{ $class->registered_count ?? 0 }}/{{ $class->capacity }} participants</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400">
                        <i class="fas fa-calendar-times text-2xl mb-2"></i>
                        <p class="text-xs">Aucun cours</p>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- Cours d'aujourd'hui -->
    @if($todayClasses->count() > 0)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-calendar-day mr-2 text-green-500"></i>Cours d'aujourd'hui
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($todayClasses as $class)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900">{{ $class->name }}</h3>
                    <span class="text-sm font-medium text-green-600">{{ $class->schedule_time->format('H:i') }}</span>
                </div>
                
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($class->description, 80) }}</p>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <i class="fas fa-users mr-1"></i>{{ $class->registered_count ?? 0 }}/{{ $class->capacity }}
                    </span>
                    <span class="text-gray-500">
                        <i class="fas fa-clock mr-1"></i>{{ $class->duration_minutes }}min
                    </span>
                </div>
                
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('coach.classes.show', $class) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir les détails →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Cours à venir -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-clock mr-2 text-purple-500"></i>Prochains cours
            </h2>
            <a href="{{ route('coach.classes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir tous les cours →
            </a>
        </div>
        
        @if($upcomingClasses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($upcomingClasses->take(10) as $class)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($class->description, 50) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $class->schedule_time->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $class->schedule_time->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ ($class->registered_count ?? 0) >= $class->capacity ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $class->registered_count ?? 0 }}/{{ $class->capacity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $class->duration_minutes }} min
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('coach.classes.show', $class) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded-lg transition-all duration-200 text-xs font-medium">
                                    Voir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun cours planifié</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre premier cours.</p>
                <a href="{{ route('coach.classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Créer un cours
                </a>
            </div>
        @endif
    </div>

    <!-- Programmes actifs -->
    @if($activePrograms->count() > 0)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">
                <i class="fas fa-running mr-2 text-orange-500"></i>Mes programmes actifs
            </h2>
            <a href="{{ route('coach.programs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir tous les programmes →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activePrograms as $program)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-900">{{ $program->title }}</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Actif
                    </span>
                </div>
                
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($program->description, 80) }}</p>
                
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <i class="fas fa-users mr-1"></i>{{ $program->members_count }} membres
                    </span>
                    <span class="text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>{{ $program->duration_weeks }} semaines
                    </span>
                </div>
                
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('coach.programs.show', $program) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir le programme →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection