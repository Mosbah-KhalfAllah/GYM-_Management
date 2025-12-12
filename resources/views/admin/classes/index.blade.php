@extends('layouts.app')

@section('title', 'Gestion des Cours Collectifs')
@section('page-title', 'Gestion des Cours Collectifs')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des cours collectifs</h2>
            <a href="{{ route('admin.classes.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Créer un cours
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un cours..." 
                           class="form-input">
                </div>
                <div>
                    <select name="coach_id" class="form-select">
                        <option value="">Tous les coachs</option>
                        @foreach($coaches ?? [] as $coach)
                            <option value="{{ $coach->id }}" {{ request('coach_id') == $coach->id ? 'selected' : '' }}>
                                {{ $coach->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-input">
                </div>
                <div>
                    <select name="sort" class="form-select">
                        <option value="start_time" {{ request('sort') === 'start_time' ? 'selected' : '' }}>Heure de début</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.classes.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Horaire</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacité</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réservations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($classes as $class)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $class->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($class->description, 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $class->coach->full_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($class->start_time && $class->end_time)
                                    <div class="text-sm text-gray-900">{{ $class->start_time->format('H:i') }} - {{ $class->end_time->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">{{ $class->start_time->format('d/m/Y') }}</div>
                                @else
                                    <div class="text-sm text-gray-500">Horaire non défini</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $class->max_participants }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ ($class->bookings->count() ?? 0) >= ($class->max_participants ?? 0) ? 'badge-danger' : 'badge-success' }}">
                                    {{ $class->bookings->count() ?? 0 }}/{{ $class->max_participants ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.classes.show', $class) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce cours ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun cours trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $classes->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection