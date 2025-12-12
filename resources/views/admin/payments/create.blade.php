@extends('layouts.app')

@section('title', 'Nouveau Paiement')
@section('page-title', 'Nouveau Paiement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Enregistrer un nouveau paiement</h2>
        
        <form action="{{ route('admin.payments.store') }}" method="POST" id="paymentForm">
            @csrf
            
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
                        :value="old('user_id', $selectedMemberId ?? '')"
                    >
                        <option value="">Sélectionnez un membre</option>
                        @foreach($members ?? [] as $member)
                            <option value="{{ $member->id }}" {{ old('user_id', $selectedMemberId ?? '') == $member->id ? 'selected' : '' }}>
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
                            :value="old('amount')"
                        />
                        
                        <x-form-field
                            name="currency"
                            label="Devise"
                            type="select"
                            :error="$errors->first('currency')"
                            :value="old('currency', 'EUR')"
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
                        :value="old('payment_method')"
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
                        :value="old('status', 'completed')"
                    >
                        <option value="pending">En attente</option>
                        <option value="completed">Complété</option>
                        <option value="failed">Échoué</option>
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
                    :value="old('description')"
                    help="Maximum 500 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.payments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const amountInput = form.querySelector('input[name="amount"]');
    const methodSelect = form.querySelector('select[name="payment_method"]');
    const statusSelect = form.querySelector('select[name="status"]');
    
    // Auto-compléter le statut selon la méthode
    methodSelect.addEventListener('change', function() {
        if (this.value === 'cash' || this.value === 'card') {
            statusSelect.value = 'completed';
        } else if (this.value === 'online') {
            statusSelect.value = 'pending';
        }
    });
    
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
});
</script>
@endsection

