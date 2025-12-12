@extends('layouts.app')

@section('title', 'Paiements de ' . $member->name)
@section('page-title', 'Paiements de ' . $member->name)

@section('content')
<div class="space-y-6">
    <!-- Informations du membre -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Informations du membre</h2>
            <button onclick="openQuickPaymentModal({{ $member->id }}, '{{ $member->name }}', '{{ $member->email }}')" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Nouveau paiement
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profil du membre -->
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-xl font-medium text-white">{{ substr($member->name, 0, 2) }}</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $member->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $member->email }}</p>
                    <p class="text-sm text-gray-500">Membre depuis {{ $member->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            
            <!-- Statistiques des paiements -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Total des paiements</h4>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($payments->sum('amount'), 2) }}€</p>
                <p class="text-sm text-gray-500">{{ $payments->total() }} paiement(s)</p>
            </div>
            
            <!-- Dernier paiement -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Dernier paiement</h4>
                @if($payments->count() > 0)
                    <p class="text-lg font-semibold text-gray-900">{{ number_format($payments->first()->amount, 2) }}€</p>
                    <p class="text-sm text-gray-500">{{ $payments->first()->created_at->format('d/m/Y') }}</p>
                @else
                    <p class="text-sm text-gray-500">Aucun paiement</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Historique des paiements</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $payment->id }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($payment->amount, 2) }}€
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
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-credit-card text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Aucun paiement trouvé</p>
                                <p class="text-sm">Ce membre n'a encore effectué aucun paiement.</p>
                                <button onclick="openQuickPaymentModal({{ $member->id }}, '{{ $member->name }}', '{{ $member->email }}')" 
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Ajouter un paiement
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
    
    <!-- Actions -->
    <div class="flex justify-between">
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux membres
        </a>
        
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-list mr-2"></i>
            Tous les paiements
        </a>
    </div>
</div>

<!-- Inclure le modal de paiement rapide -->
@include('components.quick-payment-modal')
@endsection