@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Paramètres de l'application</h1>

        <div class="space-y-6">
            <!-- Général -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold mb-4 pb-4 border-b">📋 Paramètres généraux</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la salle</label>
                        <input type="text" value="Gym Management" class="block w-full border rounded-lg p-3 bg-gray-50" disabled>
                        <p class="text-xs text-gray-500 mt-1">Ne peut être modifié que via la configuration système</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                        <select class="block w-full border rounded-lg p-3">
                            <option>Europe/Paris</option>
                            <option>Europe/London</option>
                            <option>Europe/Berlin</option>
                            <option>UTC</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold mb-4 pb-4 border-b">🔒 Sécurité</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Authentification à deux facteurs</h3>
                            <p class="text-sm text-gray-500">Améliorer la sécurité des comptes admin</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <hr>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Délai d'inactivité (minutes)</label>
                        <input type="number" value="30" min="5" class="block w-full border rounded-lg p-3">
                        <p class="text-xs text-gray-500 mt-1">Déconnecter après X minutes d'inactivité</p>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold mb-4 pb-4 border-b">🔔 Notifications</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Alertes de présence</h3>
                            <p class="text-sm text-gray-500">Recevoir les alertes pour les présences tardives</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">Alertes de paiement</h3>
                            <p class="text-sm text-gray-500">Recevoir les alertes pour les adhésions expirées</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold mb-4 pb-4 border-b">🔧 Maintenance</h2>
                <div class="space-y-4">
                    <button type="button" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        🗑️ Vider le cache
                    </button>
                    <button type="button" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        📊 Réinitialiser les statistiques
                    </button>
                    <button type="button" class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                        ⚠️ Exporter les logs
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Enregistrer les modifications</button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Annuler</a>
            </div>
        </div>
    </div>
</div>
@endsection
