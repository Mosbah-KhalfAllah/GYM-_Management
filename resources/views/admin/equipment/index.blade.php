@extends('layouts.app')

@section('title', 'Gestion des Équipements')
@section('page-title', 'Gestion des Équipements')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des équipements</h2>
            <a href="{{ route('admin.equipment.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un équipement
            </a>
        </div>

        <!-- Filtres -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher un équipement..." 
                           class="form-input">
                </div>
                <div>
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="in_use" {{ request('status') === 'in_use' ? 'selected' : '' }}>En cours d'utilisation</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>En maintenance</option>
                        <option value="out_of_order" {{ request('status') === 'out_of_order' ? 'selected' : '' }}>Hors service</option>
                    </select>
                </div>
                <div>
                    <select name="sort" class="form-select">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="purchase_date" {{ request('sort') === 'purchase_date' ? 'selected' : '' }}>Date d'achat</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn-secondary">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Équipement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'achat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigné à</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maintenance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($equipment as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-dumbbell"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $item->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->brand ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge 
                                    {{ $item->status === 'available' ? 'badge-success' : '' }}
                                    {{ $item->status === 'in_use' ? 'badge-info' : '' }}
                                    {{ $item->status === 'maintenance' ? 'badge-warning' : '' }}
                                    {{ $item->status === 'out_of_order' ? 'badge-danger' : '' }}">
                                    @switch($item->status)
                                        @case('available') Disponible @break
                                        @case('in_use') En cours @break
                                        @case('maintenance') Maintenance @break
                                        @case('out_of_order') Hors service @break
                                        @default {{ $item->status }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->purchase_date?->format('d/m/Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->assignedUser->full_name ?? 'Non assigné' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->maintenanceLogs->count() }} logs
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.equipment.show', $item) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.equipment.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.equipment.destroy', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer cet équipement ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucun équipement trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $equipment->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection