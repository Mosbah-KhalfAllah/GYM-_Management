@extends('layouts.app')

@section('title', 'Détails classe - Coach')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">{{ $class->name ?? $class->title }}</h1>
            <p class="text-gray-600 mt-1">{{ $class->description ?? '-' }}</p>
        </div>
        <a href="{{ route('coach.classes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Informations Générales -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Informations Générales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Type de classe</p>
                        <p class="font-semibold">{{ $class->type ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Niveau</p>
                        <p class="font-semibold">{{ ucfirst($class->level ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Durée</p>
                        <p class="font-semibold">{{ $class->duration ?? '-' }} minutes</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Capacité max</p>
                        <p class="font-semibold">{{ $class->max_participants ?? '-' }} personnes</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Jour de la semaine</p>
                        <p class="font-semibold">{{ $class->schedule_day ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Heure de début</p>
                        <p class="font-semibold">{{ $class->start_time ? $class->start_time->format('H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Description Complète</h2>
                <p class="text-gray-700 leading-relaxed">
                    {{ $class->description ?? 'Aucune description disponible.' }}
                </p>
            </div>

            <!-- Participants -->
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">Participants Inscrits</h2>
                    <a href="{{ route('coach.classes.attendees', $class->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Voir tous →
                    </a>
                </div>
                
                @php
                    $attendees = $class->bookings()->with('member')->latest()->take(5)->get();
                @endphp

                @if($attendees->count())
                    <div class="space-y-2">
                        @foreach($attendees as $booking)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $booking->member?->first_name ?? '-' }} {{ $booking->member?->last_name ?? '-' }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->member?->email }}</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    Inscrit
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-center py-4">Aucun participant inscrit</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">Statistiques</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-blue-100 text-sm">Participants Inscrits</p>
                        <p class="text-3xl font-bold">{{ $class->bookings()->count() }}</p>
                    </div>
                    <div class="pt-3 border-t border-blue-400">
                        <p class="text-blue-100 text-sm">Places Restantes</p>
                        <p class="text-2xl font-bold">{{ max(0, ($class->capacity ?? 0) - $class->bookings()->count()) }}</p>
                    </div>
                    <div class="pt-3 border-t border-blue-400">
                        <p class="text-blue-100 text-sm">Taux de Remplissage</p>
                        @php
                            $fillRate = ($class->capacity ?? 1) > 0 
                                ? round(($class->bookings()->count() / ($class->capacity ?? 1)) * 100) 
                                : 0;
                        @endphp
                        <p class="text-2xl font-bold">{{ $fillRate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('coach.classes.edit', $class->id) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Éditer
                    </a>
                    <a href="{{ route('coach.classes.attendees', $class->id) }}" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center justify-center">
                        <i class="fas fa-users mr-2"></i>
                        Voir Participants
                    </a>
                    <form action="{{ route('coach.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Schedule Info -->
            <div class="bg-gray-50 rounded-xl p-6 border">
                <h3 class="font-bold mb-3">Horaire</h3>
                <div class="space-y-2 text-sm">
                    <p>
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        <strong>Jour:</strong> {{ $class->schedule_day ?? '-' }}
                    </p>
                    <p>
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        <strong>Heure:</strong> {{ $class->start_time ? $class->start_time->format('H:i') : '-' }}
                    </p>
                    <p>
                        <i class="fas fa-hourglass-end text-blue-600 mr-2"></i>
                        <strong>Durée:</strong> {{ $class->duration ?? '-' }} min
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
