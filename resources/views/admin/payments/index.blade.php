@extends('layouts.app')

@section('title', 'Paiements')
@section('page-title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Revenus totaux</p>
                    <p class="text-2xl font-bold">{{ number_format($totalRevenue ?? 0, 2) }}DT</p>
                </div>
                <i class="fas fa-euro-sign text-3xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Revenus ce mois</p>
                    <p class="text-2xl font-bold">{{ number_format($monthlyRevenue ?? 0, 2) }}DT</p>
                </div>
                <i class="fas fa-calendar-alt text-3xl text-blue-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Paiements en attente</p>
                    <p class="text-2xl font-bold">{{ $pendingPayments ?? 0 }}</p>
                </div>
                <i class="fas fa-clock text-3xl text-orange-200"></i>
            </div>
        </div>
    </div>

    <!-- En-tête et filtres -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des paiements</h2>
            <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nouveau paiement
            </a>
        </div>
        
        <!-- Filtres -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Complété</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                </select>
            </div>
            <div>
                <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Toutes les méthodes</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                    <option value="card" {{ request('method') == 'card' ? 'selected' : '' }}>Carte</option>
                    <option value="online" {{ request('method') == 'online' ? 'selected' : '' }}>En ligne</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
        
        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                ID
                                @if(request('sort') === 'id')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Montant
                                @if(request('sort') === 'amount')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700">
                                Date
                                @if(request('sort') === 'created_at')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $payment->id }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ substr($payment->user->name ?? 'N/A', 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'Membre supprimé' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->user->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($payment->amount, 2) }}DT
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                    @elseif($payment->payment_method === 'card') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    @if($payment->payment_method === 'cash')
                                        <i class="fas fa-money-bill-wave mr-1"></i>Espèces
                                    @elseif($payment->payment_method === 'card')
                                        <i class="fas fa-credit-card mr-1"></i>Carte
                                    @else
                                        <i class="fas fa-globe mr-1"></i>En ligne
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($payment->status === 'completed')
                                        <i class="fas fa-check-circle mr-1"></i>Complété
                                    @elseif($payment->status === 'pending')
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    @elseif($payment->status === 'failed')
                                        <i class="fas fa-times-circle mr-1"></i>Échoué
                                    @else
                                        <i class="fas fa-undo mr-1"></i>Remboursé
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.edit', $payment) }}" class="text-indigo-600 hover:text-indigo-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.accept', $payment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Accepter">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Aucun paiement trouvé</p>
                                <p class="text-sm">Commencez par ajouter un nouveau paiement.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

