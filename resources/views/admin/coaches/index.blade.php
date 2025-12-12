@extends('layouts.app')

@section('title', 'Gestion des Coachs')
@section('page-title', 'Gestion des Coachs')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <!-- Header avec bouton d'ajout -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des coachs</h2>
            <a href="{{ route('admin.coaches.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un coach
            </a>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un coach..." 
                           class="form-input">
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div>
                    <select name="sort" class="form-select">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                        <option value="first_name" {{ request('sort') === 'first_name' ? 'selected' : '' }}>Prénom</option>
                        <option value="last_name" {{ request('sort') === 'last_name' ? 'selected' : '' }}>Nom</option>
                        <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.coaches.index') }}" class="btn-secondary">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coach</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Programmes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($coaches as $coach)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($coach->first_name, 0, 1) . substr($coach->last_name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $coach->full_name }}</div>
                                        <div class="text-sm text-gray-500">Coach</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $coach->email }}</div>
                                <div class="text-sm text-gray-500">{{ $coach->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge {{ $coach->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $coach->is_active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $coach->createdPrograms->count() ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $coach->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.coaches.show', $coach) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coaches.edit', $coach) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce coach ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun coach trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $coaches->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection