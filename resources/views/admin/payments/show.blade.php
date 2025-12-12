@extends('layouts.app')

@section('title', 'Détails du Paiement')
@section('page-title', 'Détails du Paiement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">Paiement #{{ $payment->id }}</h1>
                    <p class="text-blue-100">{{ $payment->payment_id }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Informations principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Informations du membre -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Informations du membre
                    </h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr($payment->user->name ?? 'N/A', 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'Membre supprimé' }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($payment->user)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500">Membre depuis</p>
                                <p class="text-sm font-medium">{{ $payment->user->created_at->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Détails du paiement -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-credit-card mr-2 text-green-500"></i>
                        Détails du paiement
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Montant</span>
                            <span class="text-lg font-bold text-gray-900">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Méthode</span>
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
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Date de création</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            @if($payment->description)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-gray-500"></i>
                        Description
                    </h3>
                    <p class="text-gray-700">{{ $payment->description }}</p>
                </div>
            @endif
            
            <!-- Actions -->
            <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                
                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                </form>
                
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

