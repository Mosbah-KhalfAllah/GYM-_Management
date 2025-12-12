{{-- resources/views/admin/members/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Membres')
@section('page-title', 'Gestion des Membres')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Liste des membres</h2>
        <a href="{{ route('admin.members.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Ajouter un membre
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Rechercher un membre..." 
                       class="form-input">
            </div>
            <div>
                <select name="status" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div>
                <select name="gender" class="form-select">
                    <option value="">Tous les genres</option>
                    <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Homme</option>
                    <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Femme</option>
                    <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div>
                <select name="sort" class="form-select">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
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
                <a href="{{ route('admin.members.index') }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Membres</p>
                    <p class="text-2xl font-bold mt-1">{{ $members->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Actifs</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $members->where('membership.status', 'active')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Expirés</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $members->where('membership.status', 'expired')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Nouveaux (30j)</p>
                    <p class="text-2xl font-bold mt-1">
                        {{ $members->where('created_at', '>=', now()->subDays(30))->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Adhésion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $member->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->gender === 'male' ? 'Homme' : ($member->gender === 'female' ? 'Femme' : 'Autre') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $member->email }}</div>
                                <div class="text-sm text-gray-500">{{ $member->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $member->membership->type ?? 'Aucune' }}</div>
                                @if($member->membership)
                                    <div class="text-sm text-gray-500">{{ $member->membership->end_date?->format('d/m/Y') ?? 'N/A' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->membership)
                                    <span class="badge 
                                        {{ $member->membership->status === 'active' ? 'badge-success' : '' }}
                                        {{ $member->membership->status === 'expired' ? 'badge-danger' : '' }}
                                        {{ $member->membership->status === 'cancelled' ? 'badge-warning' : '' }}
                                        {{ $member->membership->status === 'pending' ? 'badge-info' : '' }}">
                                        {{ ucfirst($member->membership->status) }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">Sans adhésion</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.members.show', $member) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.members.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="openQuickPaymentModal({{ $member->id }}, '{{ $member->full_name }}', '{{ $member->email }}')" 
                                            class="text-green-600 hover:text-green-900" title="Paiement rapide">
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                    <a href="{{ route('admin.members.payments', $member) }}" class="text-purple-600 hover:text-purple-900" title="Historique des paiements">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer ce membre ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">Aucun membre trouvé</p>
                                    <p class="mt-2">Commencez par ajouter votre premier membre.</p>
                                    <a href="{{ route('admin.members.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>
                                        Ajouter un membre
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Inclure le modal de paiement rapide -->
@include('components.quick-payment-modal')
@endsection
