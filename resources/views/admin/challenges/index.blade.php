@extends('layouts.app')

@section('title', 'Gestion des Challenges')
@section('page-title', 'Gestion des Challenges')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des challenges</h2>
            <a href="{{ route('admin.challenges.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Créer un challenge
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un challenge..." 
                           class="form-input">
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>À venir</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
                <div>
                    <select name="sort" class="form-select">
                        <option value="start_date" {{ request('sort') === 'start_date' ? 'selected' : '' }}>Date de début</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.challenges.index') }}" class="btn-secondary">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Challenge</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Participants</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Récompense</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($challenges as $challenge)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $challenge->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($challenge->description, 40) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $challenge->start_date?->format('d/m/Y') ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $challenge->end_date?->format('d/m/Y') ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $now = now();
                                    $status = 'upcoming';
                                    if ($challenge->start_date && $challenge->end_date) {
                                        if ($now->between($challenge->start_date, $challenge->end_date)) {
                                            $status = 'active';
                                        } elseif ($now->gt($challenge->end_date)) {
                                            $status = 'completed';
                                        }
                                    }
                                @endphp
                                <span class="badge 
                                    {{ $status === 'upcoming' ? 'badge-info' : '' }}
                                    {{ $status === 'active' ? 'badge-success' : '' }}
                                    {{ $status === 'completed' ? 'badge-warning' : '' }}">
                                    @switch($status)
                                        @case('upcoming') À venir @break
                                        @case('active') En cours @break
                                        @case('completed') Terminé @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $challenge->participants->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $challenge->reward ?? 'Aucune' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.challenges.show', $challenge) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.challenges.edit', $challenge) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.challenges.destroy', $challenge) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce challenge ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun challenge trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $challenges->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection