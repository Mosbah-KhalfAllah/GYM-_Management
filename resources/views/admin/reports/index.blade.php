@extends('layouts.app')

@section('title', 'Rapports et Statistiques')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rapports et Statistiques</h1>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Rapport Membres -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Rapport Membres</h3>
            </div>
            <p class="text-gray-600 mb-6">Générez des rapports détaillés sur vos membres</p>
            
            <form action="{{ route('admin.reports.members') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                        <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Voir
                    </button>
                    <button type="submit" name="format" value="pdf" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Rapport Paiements -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-money-bill text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Rapport Paiements</h3>
            </div>
            <p class="text-gray-600 mb-6">Analysez vos revenus et transactions</p>
            
            <form action="{{ route('admin.reports.payments') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                        <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Voir
                    </button>
                    <button type="submit" name="format" value="pdf" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Rapport Présences -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900">Rapport Présences</h3>
            </div>
            <p class="text-gray-600 mb-6">Suivez la fréquentation de votre salle</p>
            
            <form action="{{ route('admin.reports.attendance') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                        <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>Voir
                    </button>
                    <button type="submit" name="format" value="pdf" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Excel -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Export Excel</h3>
        <div class="grid md:grid-cols-3 gap-4">
            <form action="{{ route('admin.reports.members.excel') }}" method="GET" class="flex gap-2">
                <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i>Membres CSV
                </button>
            </form>
        </div>
    </div>
</div>
@endsection