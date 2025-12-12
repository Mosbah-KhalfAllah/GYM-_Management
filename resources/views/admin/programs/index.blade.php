@extends('layouts.app')

@section('title', 'Gestion des Programmes')
@section('page-title', 'Gestion des Programmes')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des programmes</h2>
            <a href="{{ route('admin.programs.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Créer un programme
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un programme..." 
                           class="form-input">
                </div>
                <div>
                    <select name="difficulty" class="form-select">
                        <option value="">Toutes les difficultés</option>
                        <option value="beginner" {{ request('difficulty') === 'beginner' ? 'selected' : '' }}>Débutant</option>
                        <option value="intermediate" {{ request('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="advanced" {{ request('difficulty') === 'advanced' ? 'selected' : '' }}>Avancé</option>
                    </select>
                </div>
                <div>
                    <select name="sort" class="form-select">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="duration_weeks" {{ request('sort') === 'duration_weeks' ? 'selected' : '' }}>Durée</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.programs.index') }}" class="btn-secondary">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Difficulté</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membres</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($programs as $program)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-running"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $program->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($program->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $program->coach->full_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge 
                                    {{ $program->difficulty_level === 'beginner' ? 'badge-success' : '' }}
                                    {{ $program->difficulty_level === 'intermediate' ? 'badge-warning' : '' }}
                                    {{ $program->difficulty_level === 'advanced' ? 'badge-danger' : '' }}">
                                    {{ ucfirst($program->difficulty_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $program->duration_weeks }} semaines
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $program->members->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.programs.show', $program) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.programs.edit', $program) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.programs.destroy', $program) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce programme ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun programme trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $programs->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection