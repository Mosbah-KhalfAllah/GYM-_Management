@extends('layouts.app')

@section('title', 'Membres - Coach')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Mes Membres</h1>
    </div>

    @if($members->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Téléphone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Adhésion</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Programme Actif</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($members as $member)
                        @php
                            $membership = $member->membership;
                            $activeProgram = $member->programs->where('pivot.status', 'active')->first();
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $member->first_name }} {{ $member->last_name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $member->email }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $member->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($membership)
                                    <div class="space-y-1">
                                        <p class="font-medium text-gray-900">{{ $membership->type }}</p>
                                        <p class="text-xs text-gray-500">{{ $membership->price }}€/mois</p>
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                            @if($membership->status === 'active')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($membership->status) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">Aucune adhésion</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($activeProgram)
                                    <div class="space-y-1">
                                        <p class="font-medium text-gray-900">{{ $activeProgram->title }}</p>
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $activeProgram->pivot->completion_percentage }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600">{{ $activeProgram->pivot->completion_percentage }}%</span>
                                        </div>
                                        <p class="text-xs text-gray-500">Jour {{ $activeProgram->pivot->current_day }}/{{ $activeProgram->duration_days }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-500 text-xs">Aucun programme actif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('coach.members.show', $member) }}" class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition" title="Voir le profil">
                                        <i class="fas fa-eye mr-1"></i>
                                        Voir
                                    </a>
                                    <a href="{{ route('coach.members.edit', $member) }}" class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition" title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('coach.members.assignProgram', $member) }}" class="inline-flex items-center px-3 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition" title="Assigner programme">
                                        <i class="fas fa-tasks"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Aucun membre trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <p class="text-gray-600 mb-4">Vous n'avez pas encore de membres assignés</p>
        </div>
    @endif
</div>
@endsection

