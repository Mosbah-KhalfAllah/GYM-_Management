@extends('layouts.app')

@section('title', 'Profil membre - Coach')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">{{ $member->first_name }} {{ $member->last_name }}</h1>
            <p class="text-gray-600 mt-1">{{ $member->email }}</p>
        </div>
        <a href="{{ route('coach.members.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations Personnelles -->
        <div class="md:col-span-2 space-y-6">
            <!-- Infos Personnelles Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Informations Personnelles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Prénom</p>
                        <p class="font-medium">{{ $member->first_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Nom</p>
                        <p class="font-medium">{{ $member->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-medium text-blue-600">{{ $member->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Téléphone</p>
                        <p class="font-medium">{{ $member->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Date de naissance</p>
                        <p class="font-medium">{{ $member->birth_date ? $member->birth_date->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Genre</p>
                        <p class="font-medium">
                            @if($member->gender === 'male')
                                Homme
                            @elseif($member->gender === 'female')
                                Femme
                            @else
                                Autre
                            @endif
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Adresse</p>
                        <p class="font-medium">{{ $member->address ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-gray-600 text-sm">Contact d'urgence</p>
                        <p class="font-medium">{{ $member->emergency_contact ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Programmes Assignés -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Programmes Assignés</h2>
                @if($member->programs->count())
                    <div class="space-y-4">
                        @foreach($member->programs as $program)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h3 class="font-bold text-lg">{{ $program->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $program->description }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($program->pivot->status === 'active')
                                            bg-green-100 text-green-800
                                        @elseif($program->pivot->status === 'paused')
                                            bg-yellow-100 text-yellow-800
                                        @else
                                            bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($program->pivot->status) }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-3 pt-3 border-t">
                                    <div>
                                        <p class="text-gray-600 text-xs">Niveau</p>
                                        <p class="font-medium">{{ ucfirst($program->level) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-xs">Jour en cours</p>
                                        <p class="font-medium">{{ $program->pivot->current_day }}/{{ $program->duration_days }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-xs">Durée</p>
                                        <p class="font-medium">{{ $program->duration_days }} jours</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600 text-xs">Objectif</p>
                                        <p class="font-medium">{{ ucfirst($program->goal) }}</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-xs text-gray-600">Progression</p>
                                        <p class="text-xs font-medium">{{ $program->pivot->completion_percentage }}%</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $program->pivot->completion_percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">Aucun programme assigné</p>
                @endif
            </div>

            <!-- Présences Récentes -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Présences Récentes (10 dernières)</h2>
                @if($member->attendances->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">Date/Heure</th>
                                    <th class="px-4 py-2 text-left">Entrée</th>
                                    <th class="px-4 py-2 text-left">Sortie</th>
                                    <th class="px-4 py-2 text-left">Durée</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($member->attendances as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $attendance->check_in->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2">{{ $attendance->check_in->format('H:i') }}</td>
                                        <td class="px-4 py-2">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                                        <td class="px-4 py-2">{{ $attendance->duration_minutes ? $attendance->duration_minutes . ' min' : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Aucune présence enregistrée</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Adhésion Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold mb-4">Adhésion</h2>
                @if($member->membership)
                    @php
                        $membership = $member->membership;
                    @endphp
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-600 text-sm">Type</p>
                            <p class="font-bold text-lg">{{ $membership->type }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Prix</p>
                            <p class="font-bold text-lg">{{ $membership->price }}€</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Statut</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($membership->status === 'active')
                                    bg-green-100 text-green-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($membership->status) }}
                            </span>
                        </div>
                        <div class="border-t pt-3">
                            <p class="text-gray-600 text-sm">Début</p>
                            <p class="font-medium">{{ $membership->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Fin</p>
                            <p class="font-medium">{{ $membership->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Renouvellement auto</p>
                            <p class="font-medium">{{ $membership->auto_renewal ? 'Oui' : 'Non' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Aucune adhésion active</p>
                @endif
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('coach.members.show', $member->id) }}" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        Voir Profil
                    </a>
                    <a href="{{ route('coach.members.edit', $member->id) }}" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        Éditer
                    </a>
                    <a href="{{ route('coach.members.assignProgram', $member->id) }}" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center justify-center">
                        <i class="fas fa-tasks mr-2"></i>
                        Assigner Programme
                    </a>
                    <button class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition flex items-center justify-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        Voir Progression
                    </button>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold mb-4">Statistiques</h2>
                <div class="space-y-3">
                    <div class="bg-blue-50 rounded p-3">
                        <p class="text-gray-600 text-sm">Programmes actifs</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $member->programs->where('pivot.status', 'active')->count() }}</p>
                    </div>
                    <div class="bg-green-50 rounded p-3">
                        <p class="text-gray-600 text-sm">Présences ce mois</p>
                        <p class="text-2xl font-bold text-green-600">{{ $member->attendances->where('check_in', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div class="bg-purple-50 rounded p-3">
                        <p class="text-gray-600 text-sm">Adhésion</p>
                        <p class="text-lg font-bold text-purple-600">{{ $member->membership?->type ?? 'Aucune' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
