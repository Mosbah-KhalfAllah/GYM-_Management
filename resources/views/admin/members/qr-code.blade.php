{{-- resources/views/admin/members/qr-code.blade.php --}}
@extends('layouts.app')

@section('title', 'QR Code du Membre')
@section('page-title', 'QR Code du Membre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="text-center mb-8">
            <div class="h-20 w-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $member->full_name }}</h1>
            <p class="text-gray-600">Code d'accès à la salle</p>
            <p class="text-sm text-gray-500 mt-2">ID: {{ $member->id }}</p>
        </div>
        
        <!-- QR Code -->
        <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-xl border-2 border-dashed border-gray-200 mb-6">
            <div class="text-center">
                <div class="inline-block p-4 bg-white rounded-lg shadow-md">
                    <img src="{{ $qrCodeUrl }}" alt="QR Code" class="w-64 h-64 mx-auto">
                </div>
                <p class="text-gray-500 text-sm mt-4">
                    Scanner ce QR code pour enregistrer la présence du membre
                </p>
            </div>
        </div>
        
        <!-- Member Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-medium">{{ $member->email }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Statut d'adhésion</p>
                @if($member->membership)
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $member->membership->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($member->membership->status) }}
                    </span>
                @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                        Aucune adhésion
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('admin.members.show', $member) }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au profil
            </a>
            <button onclick="window.print()" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                <i class="fas fa-print mr-2"></i>
                Imprimer
            </button>
            <a href="{{ $qrCodeUrl }}" download="qr-code-{{ $member->id }}.png" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 font-medium">
                <i class="fas fa-download mr-2"></i>
                Télécharger
            </a>
        </div>
    </div>
    
    <!-- Instructions -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-3">Instructions</h3>
        <ul class="space-y-2 text-blue-700">
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-3 text-blue-600"></i>
                <span>Ce QR code est unique à ce membre et permet d'enregistrer ses présences</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-3 text-blue-600"></i>
                <span>Le membre peut scanner son code à l'entrée de la salle</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-3 text-blue-600"></i>
                <span>Le personnel peut scanner ce code pour enregistrer manuellement la présence</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle mt-1 mr-3 text-blue-600"></i>
                <span>Le QR code sera automatiquement désactivé si l'adhésion expire</span>
            </li>
        </ul>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .bg-white {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        
        .flex, .grid {
            display: block !important;
        }
    }
</style>
@endsection