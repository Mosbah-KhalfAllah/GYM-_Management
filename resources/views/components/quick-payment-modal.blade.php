<!-- Modal de paiement rapide -->
<div id="quickPaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- En-tête -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Paiement rapide</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeQuickPaymentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Formulaire -->
            <form id="quickPaymentForm">
                @csrf
                <input type="hidden" id="quickPaymentUserId" name="user_id">
                
                <div class="space-y-4">
                    <!-- Membre sélectionné -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Membre</label>
                        <div id="selectedMemberInfo" class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div id="memberAvatar" class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                        <span id="memberInitials" class="text-xs font-medium text-white"></span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div id="memberName" class="text-sm font-medium text-gray-900"></div>
                                    <div id="memberEmail" class="text-sm text-gray-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Montant -->
                    <div>
                        <label for="quickAmount" class="block text-sm font-medium text-gray-700 mb-1">Montant (DT)</label>
                        <input type="number" id="quickAmount" name="amount" step="0.01" min="0" max="999999.99" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="50.00" required>
                    </div>
                    
                    <!-- Méthode de paiement -->
                    <div>
                        <label for="quickPaymentMethod" class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
                        <select id="quickPaymentMethod" name="payment_method" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="cash">Espèces</option>
                            <option value="card">Carte bancaire</option>
                            <option value="online">Paiement en ligne</option>
                        </select>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="quickDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" id="quickDescription" name="description" maxlength="500"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Abonnement mensuel, cours particulier...">
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeQuickPaymentModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openQuickPaymentModal(userId, userName, userEmail) {
    // Remplir les informations du membre
    document.getElementById('quickPaymentUserId').value = userId;
    document.getElementById('memberName').textContent = userName;
    document.getElementById('memberEmail').textContent = userEmail;
    document.getElementById('memberInitials').textContent = userName.split(' ').map(n => n[0]).join('').toUpperCase();
    
    // Réinitialiser le formulaire
    document.getElementById('quickPaymentForm').reset();
    document.getElementById('quickPaymentUserId').value = userId;
    
    // Afficher le modal
    document.getElementById('quickPaymentModal').classList.remove('hidden');
}

function closeQuickPaymentModal() {
    document.getElementById('quickPaymentModal').classList.add('hidden');
}

// Gestion de la soumission du formulaire
document.getElementById('quickPaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Désactiver le bouton et afficher le loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';
    
    fetch('{{ route("admin.payments.quick") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès
            showNotification('Paiement enregistré avec succès', 'success');
            closeQuickPaymentModal();
            
            // Recharger la page si nécessaire
            if (window.location.pathname.includes('/payments')) {
                location.reload();
            }
        } else {
            showNotification('Erreur lors de l\'enregistrement du paiement', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'enregistrement du paiement', 'error');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

// Fonction pour afficher les notifications
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animer l'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('quickPaymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickPaymentModal();
    }
});
</script>