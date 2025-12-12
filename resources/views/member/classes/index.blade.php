@extends('layouts.app')

@section('title', 'Classes - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Classes</h1>
        <p class="opacity-90">Découvrez et réservez vos cours</p>
    </div>

    <!-- Booked Classes Section -->
    @if($bookedClasses->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calendar-check mr-3 text-green-600"></i>
                Mes réservations actives ({{ $bookedClasses->count() }})
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($bookedClasses as $class)
                    <div class="border border-green-200 rounded-lg p-4 hover:shadow-lg transition-shadow bg-green-50">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $class->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $class->coach->first_name }} {{ $class->coach->last_name }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-medium">
                                Réservé
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><i class="fas fa-clock mr-2"></i>{{ $class->schedule_time->format('d/m/Y à H:i') }}</p>
                            <p><i class="fas fa-people-group mr-2"></i>{{ $class->registered_count }}/{{ $class->capacity }}</p>
                            <p><i class="fas fa-hourglass-half mr-2"></i>{{ $class->duration_minutes }} min</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('member.classes.show', $class) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm text-center font-medium">
                                <i class="fas fa-eye mr-1"></i>Détails
                            </a>
                            <form action="{{ route('member.classes.destroy', $class->bookings()->where('member_id', Auth::id())->first()) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr?')" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-trash mr-1"></i>Annuler
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Classes Section -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar-plus mr-3 text-blue-600"></i>
            Classes disponibles ({{ $availableClasses->total() }})
        </h2>
        
        @if($availableClasses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($availableClasses as $class)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $class->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $class->coach->first_name }} {{ $class->coach->last_name }}
                                </p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                Disponible
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p><i class="fas fa-clock mr-2"></i>{{ $class->schedule_time->format('d/m/Y à H:i') }}</p>
                            <p><i class="fas fa-people-group mr-2"></i>{{ $class->registered_count }}/{{ $class->capacity }}</p>
                            <p><i class="fas fa-hourglass-half mr-2"></i>{{ $class->duration_minutes }} min</p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $class->location ?? 'Salle principale' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('member.classes.show', $class) }}" class="flex-1 px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm text-center font-medium">
                                <i class="fas fa-eye mr-1"></i>Détails
                            </a>
                            <form action="{{ route('member.classes.book', $class) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i>Réserver
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $availableClasses->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Aucune classe disponible</p>
            </div>
        @endif
    </div>

    <!-- Class History Section -->
    @if($classHistory->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-history mr-3 text-gray-600"></i>
                Historique des classes ({{ $classHistory->total() }})
            </h2>
            <div class="space-y-3">
                @foreach($classHistory as $class)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $class->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    {{ $class->coach->first_name }} {{ $class->coach->last_name }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $class->schedule_time->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                                    Passée
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($classHistory->count() > 0)
                <div class="mt-6">
                    {{ $classHistory->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

