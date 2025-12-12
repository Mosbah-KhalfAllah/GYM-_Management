@extends('layouts.app')

@section('title', 'Modifier le Paiement')
@section('page-title', 'Modifier le Paiement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Modifier le paiement #{{ $payment->id }}</h2>
            <span class="text-sm text-gray-500">{{ $payment->payment_id }}</span>
        </div>
        
        <form action="{{ route('admin.payments.update', $payment) }}" method="POST" id="paymentEditForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations du membre -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations du membre</h3>
                    
                    <x-form-field
                        name="user_id"
                        label="Membre"
                        type="select"
                        :required="true"
                        :error="$errors->first('user_id')"
                        :value="old('user_id', $payment->user_id)"
                    >
                        @foreach($members ?? [] as $member)
                            <option value="{{ $member->id }}" {{ old('user_id', $payment->user_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->email }})
                            </option>
                        @endforeach
                    </x-form-field>
                </div>
                
                <!-- Détails du paiement -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Détails du paiement</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-field
                            name="amount"
                            label="Montant (€)"
                            type="number"
                            placeholder="50.00"
                            :required="true"
                            step="0.01"
                            min="0.01"
                            max="999999.99"
                            :error="$errors->first('amount')"
                            :value="old('amount', $payment->amount)"
                        />
                        
                        <x-form-field
                            name="currency"
                            label="Devise"
                            type="select"
                            :error="$errors->first('currency')"
                            :value="old('currency', $payment->currency)"
                        >
                            <option value="EUR">EUR (€)</option>
                            <option value="USD">USD ($)</option>
                        </x-form-field>
                    </div>
                    
                    <x-form-field
                        name="payment_method"
                        label="Méthode de paiement"
                        type="select"
                        :required="true"
                        :error="$errors->first('payment_method')"
                        :value="old('payment_method', $payment->payment_method)"
                    >
                        <option value="cash">Espèces</option>
                        <option value="card">Carte bancaire</option>
                        <option value="online">Paiement en ligne</option>
                    </x-form-field>
                    
                    <x-form-field
                        name="status"
                        label="Statut"
                        type="select"
                        :required="true"
                        :error="$errors->first('status')"
                        :value="old('status', $payment->status)"
                    >
                        <option value="pending">En attente</option>
                        <option value="completed">Complété</option>
                        <option value="failed">Échoué</option>
                        <option value="refunded">Remboursé</option>
                    </x-form-field>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-6">
                <x-form-field
                    name="description"
                    label="Description"
                    type="textarea"
                    placeholder="Abonnement mensuel, cours particulier, etc..."
                    maxlength="500"
                    :error="$errors->first('description')"
                    :value="old('description', $payment->description)"
                    help="Maximum 500 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.payments.show', $payment) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentEditForm');
    const amountInput = form.querySelector('input[name="amount"]');
    const statusSelect = form.querySelector('select[name="status"]');
    
    // Validation du montant
    amountInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value && value < 0.01) {
            this.setCustomValidity('Le montant doit être supérieur à 0');
        } else if (value && value > 999999.99) {
            this.setCustomValidity('Le montant ne peut pas dépasser 999,999.99');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Confirmation pour les changements de statut critiques
    form.addEventListener('submit', function(e) {
        const originalStatus = '{{ $payment->status }}';
        const newStatus = statusSelect.value;
        
        if (originalStatus === 'completed' && newStatus !== 'completed') {
            if (!confirm('Vous êtes sur le point de changer le statut d\'un paiement complété. Êtes-vous sûr ?')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection

