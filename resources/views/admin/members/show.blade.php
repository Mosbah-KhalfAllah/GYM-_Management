{{-- resources/views/admin/members/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails du Membre')
@section('page-title', 'Détails du Membre')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $member->full_name }}</h1>
                <p class="text-gray-600">Membre #{{ $member->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-door-open"></i>
                Présences
            </a>
            <a href="{{ route('admin.members.edit', $member) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Modifier
            </a>
            <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir désactiver ce membre ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-user-slash"></i>
                    Désactiver
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $member->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="font-medium">{{ $member->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Date de naissance</p>
                        <p class="font-medium">{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : 'Non renseignée' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Genre</p>
                        <p class="font-medium">
                            @switch($member->gender)
                                @case('male')
                                    Homme
                                    @break
                                @case('female')
                                    Femme
                                    @break
                                @case('other')
                                    Autre
                                    @break
                                @default
                                    Non renseigné
                            @endswitch
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Adresse</p>
                        <p class="font-medium">{{ $member->address ?? 'Non renseignée' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Contact d'urgence</p>
                        <p class="font-medium">{{ $member->emergency_contact ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Membership Information -->
            @if($member->membership)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Adhésion</h2>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $member->membership->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($member->membership->status === 'expired' ? 'bg-red-100 text-red-800' : 
                               ($member->membership->status === 'cancelled' ? 'bg-yellow-100 text-yellow-800' : 
                               'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($member->membership->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Type d'adhésion</p>
                            <p class="font-medium">{{ $member->membership->type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Prix</p>
                            <p class="font-medium">${{ number_format($member->membership->price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date de début</p>
                            <p class="font-medium">{{ $member->membership->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date de fin</p>
                            <p class="font-medium">{{ $member->membership->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Renouvellement automatique</p>
                            <p class="font-medium">
                                @if($member->membership->auto_renewal)
                                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Activé</span>
                                @else
                                    <span class="text-red-600"><i class="fas fa-times-circle"></i> Désactivé</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Attendance -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Présences récentes</h2>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Voir tout</a>
                </div>
                @if($member->attendances && $member->attendances->count() > 0)
                    <div class="space-y-3">
                        @foreach($member->attendances->take(5) as $attendance)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium">{{ $attendance->check_in->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $attendance->check_in->format('H:i') }} - 
                                        @if($attendance->check_out)
                                            {{ $attendance->check_out->format('H:i') }}
                                            ({{ $attendance->duration_minutes }} min)
                                        @else
                                            En cours
                                        @endif
                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $attendance->entry_method === 'qr_code' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $attendance->entry_method === 'qr_code' ? 'Scanner' : 'Manuel' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Aucune présence enregistrée</p>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statut</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Compte</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $member->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Adhésion</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $member->membership && $member->membership->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $member->membership ? ucfirst($member->membership->status) : 'Aucune' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Membre depuis</span>
                        <span class="font-medium">{{ $member->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dernière connexion</span>
                        <span class="font-medium">{{ $member->last_login_at ? $member->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Présences totales</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $member->attendances ? $member->attendances->count() : 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Programmes actifs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $member->programs ? $member->programs->where('status', 'active')->count() : 0 }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cours réservés</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $member->bookings ? $member->bookings->count() : 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.attendance.record', ['member_id' => $member->id]) }}" class="block w-full px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-center font-medium">
                        <i class="fas fa-door-open mr-2"></i>
                        Enregistrer présence
                    </a>
                    <a href="{{ route('admin.payments.create') }}?member_id={{ $member->id }}" class="block w-full px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-center font-medium">
                        <i class="fas fa-credit-card mr-2"></i>
                        Enregistrer paiement
                    </a>
                    <a href="{{ route('admin.programs.assignMemberForm', $member->id) }}" class="block w-full px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors text-center font-medium">
                        <i class="fas fa-dumbbell mr-2"></i>
                        Assigner programme
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
