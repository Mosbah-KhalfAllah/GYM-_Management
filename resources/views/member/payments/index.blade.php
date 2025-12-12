@extends('layouts.app')

@section('title', 'Mes Paiements')
@section('page-title', 'Mes Paiements')

@section('content')
<div class="space-y-6">
    <!-- Statut d'adhésion -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Mon Adhésion</h2>
        
        @if($membership)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700">Type d'adhésion</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $membership->type }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700">Statut</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                        @if($membership->status === 'active') bg-green-100 text-green-800
                        @elseif($membership->status === 'expired') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($membership->status) }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700">Expire le</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ $membership->end_date->format('d/m/Y') }}</p>
                </div>
            </div>
            
            @if($membership->status === 'expired' || $membership->end_date->isPast())
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-red-800 font-medium">Adhésion expirée</h4>
                            <p class="text-red-600 text-sm">Votre adhésion a expiré. Effectuez un paiement pour la renouveler.</p>
                        </div>
                        <button onclick="openPaymentModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Payer maintenant
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Historique des paiements -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Historique des paiements</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
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
                                        Espèces
                                    @elseif($payment->payment_method === 'card')
                                        Carte
                                    @else
                                        En ligne
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($payment->status === 'completed')
                                        Complété
                                    @elseif($payment->status === 'pending')
                                        En attente
                                    @else
                                        Échoué
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <p class="text-lg font-medium">Aucun paiement trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de paiement -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Paiement en ligne</h3>
        
        <form action="{{ route('member.payments.online') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type d'adhésion</label>
                    <select name="membership_type" id="membership_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                        <option value="">Choisissez votre adhésion</option>
                        <option value="mensuel" data-price="50">Mensuel - 50 DT</option>
                        <option value="trimestriel" data-price="140">Trimestriel - 140 DT (Economie 10 DT)</option>
                        <option value="semestriel" data-price="270">Semestriel - 270 DT (Economie 30 DT)</option>
                        <option value="annuel" data-price="500">Annuel - 500 DT (Economie 100 DT)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant (DT)</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="50.00" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly required>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closePaymentModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Payer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

// Mettre à jour le montant selon le type d'adhésion
document.getElementById('membership_type').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const amountInput = document.getElementById('amount');
    
    if (price) {
        amountInput.value = price + '.00';
    } else {
        amountInput.value = '0.00';
    }
});
</script>
@endsection