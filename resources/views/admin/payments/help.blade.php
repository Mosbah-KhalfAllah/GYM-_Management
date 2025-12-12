@extends('layouts.app')

@section('title', 'Guide des Paiements')
@section('page-title', 'Guide des Paiements')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Introduction -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Comment effectuer un paiement ?</h2>
        <p class="text-gray-600 mb-6">Ce guide vous explique les différentes façons d'enregistrer un paiement dans le système de gestion de la salle de sport.</p>
    </div>

    <!-- Méthodes de paiement -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Paiement rapide -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-bolt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Paiement Rapide</h3>
            </div>
            <p class="text-gray-600 mb-4">La méthode la plus rapide pour enregistrer un paiement depuis la liste des membres.</p>
            
            <div class="space-y-3">
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                    <p class="text-sm text-gray-700">Allez dans <strong>Membres</strong> → <strong>Liste des membres</strong></p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                    <p class="text-sm text-gray-700">Cliquez sur l'icône <i class="fas fa-credit-card text-green-600"></i> dans la colonne Actions</p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                    <p class="text-sm text-gray-700">Remplissez le montant, la méthode et la description</p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                    <p class="text-sm text-gray-700">Cliquez sur <strong>Enregistrer</strong></p>
                </div>
            </div>
        </div>

        <!-- Paiement complet -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-file-invoice-dollar text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Paiement Complet</h3>
            </div>
            <p class="text-gray-600 mb-4">Pour enregistrer un paiement avec plus de détails et d'options.</p>
            
            <div class="space-y-3">
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                    <p class="text-sm text-gray-700">Allez dans <strong>Paiements</strong> → <strong>Nouveau paiement</strong></p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                    <p class="text-sm text-gray-700">Sélectionnez le membre dans la liste déroulante</p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                    <p class="text-sm text-gray-700">Remplissez tous les détails (montant, devise, méthode, statut)</p>
                </div>
                <div class="flex items-start">
                    <span class="flex-shrink-0 w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                    <p class="text-sm text-gray-700">Ajoutez une description détaillée si nécessaire</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Méthodes de paiement disponibles -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Méthodes de Paiement Disponibles</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                    <h4 class="font-semibold text-gray-800">Espèces</h4>
                </div>
                <p class="text-sm text-gray-600">Paiement en liquide directement à la réception</p>
                <span class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Statut: Complété automatiquement</span>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                    <h4 class="font-semibold text-gray-800">Carte Bancaire</h4>
                </div>
                <p class="text-sm text-gray-600">Paiement par carte bancaire sur terminal</p>
                <span class="inline-block mt-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Statut: Complété automatiquement</span>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-globe text-purple-600 mr-2"></i>
                    <h4 class="font-semibold text-gray-800">Paiement en Ligne</h4>
                </div>
                <p class="text-sm text-gray-600">Virement, PayPal ou autres méthodes en ligne</p>
                <span class="inline-block mt-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Statut: En attente par défaut</span>
            </div>
        </div>
    </div>

    <!-- Statuts des paiements -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Statuts des Paiements</h3>
        
        <div class="space-y-3">
            <div class="flex items-center p-3 bg-green-50 rounded-lg">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-green-800">Complété</h4>
                    <p class="text-sm text-green-600">Le paiement a été reçu et validé</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                <i class="fas fa-clock text-yellow-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">En Attente</h4>
                    <p class="text-sm text-yellow-600">Le paiement est en cours de traitement</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-red-50 rounded-lg">
                <i class="fas fa-times-circle text-red-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-red-800">Échoué</h4>
                    <p class="text-sm text-red-600">Le paiement n'a pas pu être traité</p>
                </div>
            </div>
            
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-undo text-gray-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Remboursé</h4>
                    <p class="text-sm text-gray-600">Le paiement a été remboursé au client</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conseils et bonnes pratiques -->
    <div class="bg-blue-50 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-blue-800 mb-4">
            <i class="fas fa-lightbulb mr-2"></i>
            Conseils et Bonnes Pratiques
        </h3>
        
        <div class="space-y-3">
            <div class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <p class="text-blue-700">Toujours vérifier l'identité du membre avant d'enregistrer un paiement</p>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <p class="text-blue-700">Ajouter une description claire (ex: "Abonnement mensuel Mars 2024")</p>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <p class="text-blue-700">Utiliser le paiement rapide pour les transactions courantes</p>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <p class="text-blue-700">Consulter l'historique des paiements d'un membre avant d'enregistrer un nouveau paiement</p>
            </div>
            <div class="flex items-start">
                <i class="fas fa-check text-blue-600 mt-1 mr-3"></i>
                <p class="text-blue-700">Marquer les paiements en ligne comme "En attente" jusqu'à confirmation</p>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions Rapides</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.payments.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-plus text-blue-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Nouveau Paiement</h4>
                    <p class="text-sm text-gray-600">Enregistrer un paiement complet</p>
                </div>
            </a>
            
            <a href="{{ route('admin.payments.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-list text-green-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Tous les Paiements</h4>
                    <p class="text-sm text-gray-600">Voir l'historique complet</p>
                </div>
            </a>
            
            <a href="{{ route('admin.members.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-users text-purple-600 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-gray-800">Liste des Membres</h4>
                    <p class="text-sm text-gray-600">Paiement rapide depuis la liste</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection