@extends('layouts.app')

@section('title', 'Classes - Coach')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Mes Classes</h1>
            <p class="text-gray-600 mt-1">Gérez vos classes et participants</p>
        </div>
        <a href="{{ route('coach.classes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nouvelle Classe
        </a>
    </div>

    @if($classes && $classes->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <!-- Class Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
                        <h2 class="text-xl font-bold">{{ $class->name ?? $class->title }}</h2>
                        <p class="text-blue-100 text-sm mt-1">{{ $class->description ?? '-' }}</p>
                    </div>

                    <!-- Class Details -->
                    <div class="p-4 space-y-3 border-b">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 text-sm">Type</p>
                                <p class="font-semibold">{{ $class->type ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Niveau</p>
                                <p class="font-semibold">{{ ucfirst($class->level ?? '-') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Durée</p>
                                <p class="font-semibold">{{ $class->duration ?? '-' }} min</p>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">Capacité</p>
                                <p class="font-semibold">{{ $class->max_participants ?? '-' }} personnes</p>
                            </div>
                        </div>

                        <!-- Schedule -->
                        @if($class->schedule_day || $class->start_time)
                            <div class="bg-gray-50 rounded p-2">
                                <p class="text-gray-600 text-sm">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    {{ $class->schedule_day ?? '-' }}
                                </p>
                                <p class="text-gray-600 text-sm">
                                    <i class="fas fa-clock mr-2"></i>
                                    {{ $class->start_time ? $class->start_time->format('H:i') : '-' }}
                                </p>
                            </div>
                        @endif

                        <!-- Participants Count -->
                        <div class="bg-blue-50 rounded p-3 flex items-center justify-between">
                            <span class="text-gray-700 font-medium">Participants</span>
                            <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                {{ $class->bookings()->count() }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 space-y-2">
                        <a href="{{ route('coach.classes.show', $class->id) }}" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center justify-center text-sm font-medium">
                            <i class="fas fa-eye mr-2"></i>
                            Voir Détails
                        </a>
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('coach.classes.edit', $class->id) }}" class="px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>
                                Éditer
                            </a>
                            <a href="{{ route('coach.classes.attendees', $class->id) }}" class="px-3 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-users mr-1"></i>
                                Participants
                            </a>
                        </div>
                        <form action="{{ route('coach.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr?');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex items-center justify-center text-sm font-medium">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($classes instanceof \Illuminate\Pagination\Paginator || $classes instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-8">
                {{ $classes->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 text-lg mb-4">Vous n'avez pas encore de classes</p>
            <a href="{{ route('coach.classes.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>
                Créer une classe
            </a>
        </div>
    @endif
</div>
@endsection

