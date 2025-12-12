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
                    
                    <!-- Recherche par téléphone -->
                    <div>
                        <label for="phone_search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher par téléphone</label>
                        <div class="flex gap-2">
                            <input type="text" id="phone_search" placeholder="Numéro de téléphone" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="button" id="search_btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Membre sélectionné -->
                    <div id="member_info" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Membre trouvé</label>
                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span id="member_initials" class="text-sm font-medium text-white"></span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div id="member_name" class="text-sm font-medium text-gray-900"></div>
                                    <div id="member_email" class="text-sm text-gray-500"></div>
                                    <div id="member_phone" class="text-sm text-gray-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="user_id" id="selected_user_id" required>
                    @error('user_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Détails du paiement -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Détails du paiement</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-field
                            name="amount"
                            label="Montant (DT)"
                            type="number"
                            placeholder="50.00"
                            :required="true"
                            step="0.01"
                            min="0"
                            max="999999.99"
                            :error="$errors->first('amount')"
                            :value="old('amount')"
                        />
                        
                        <x-form-field
                            name="currency"
                            label="Devise"
                            type="select"
                            :error="$errors->first('currency')"
                            :value="old('currency', 'TND')"
                        >
                            <option value="TND">TND (DT)</option>
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
    const phoneSearch = document.getElementById('phone_search');
    const searchBtn = document.getElementById('search_btn');
    const memberInfo = document.getElementById('member_info');
    const selectedUserId = document.getElementById('selected_user_id');
    
    // Recherche par téléphone
    function searchMember() {
        const phone = phoneSearch.value.trim();
        if (!phone) {
            alert('Veuillez saisir un numéro de téléphone');
            return;
        }
        
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch('/admin/members/search-by-phone', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.member) {
                // Afficher les informations du membre
                document.getElementById('member_name').textContent = data.member.name;
                document.getElementById('member_email').textContent = data.member.email;
                document.getElementById('member_phone').textContent = data.member.phone;
                document.getElementById('member_initials').textContent = data.member.name.split(' ').map(n => n[0]).join('').toUpperCase();
                selectedUserId.value = data.member.id;
                memberInfo.classList.remove('hidden');
            } else {
                alert('Aucun membre trouvé avec ce numéro de téléphone');
                memberInfo.classList.add('hidden');
                selectedUserId.value = '';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la recherche');
        })
        .finally(() => {
            searchBtn.disabled = false;
            searchBtn.innerHTML = '<i class="fas fa-search"></i>';
        });
    }
    
    searchBtn.addEventListener('click', searchMember);
    phoneSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchMember();
        }
    });
    
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
        if (value && value < 0) {
            this.setCustomValidity('Le montant doit être supérieur ou égal à 0');
        } else if (value && value > 999999.99) {
            this.setCustomValidity('Le montant ne peut pas dépasser 999,999.99');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection

