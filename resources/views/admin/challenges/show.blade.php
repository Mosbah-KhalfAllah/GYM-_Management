@extends('layouts.app')

@section('title', 'Détails du challenge')
@section('page-title', 'Détails du challenge')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $challenge->title }}</h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $challenge->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $challenge->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Informations</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Description</p>
                    <p class="font-medium">{{ $challenge->description }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Type</p>
                    <p class="font-medium">{{ ucfirst($challenge->type) }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Objectif</p>
                    <p class="font-medium">{{ $challenge->target_value }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Points de récompense</p>
                    <p class="font-medium">{{ $challenge->points_reward }} points</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Période</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Date de début</p>
                    <p class="font-medium">{{ $challenge->start_date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Date de fin</p>
                    <p class="font-medium">{{ $challenge->end_date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Participants</p>
                    <p class="font-medium">{{ $challenge->participants->count() }} membres</p>
                </div>
            </div>
        </div>
        
        @if($challenge->participants->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Participants</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progrès</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($challenge->participants as $participant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($participant->member->first_name, 0, 1) }}{{ substr($participant->member->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $participant->member->full_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $participant->current_progress }} / {{ $challenge->target_value }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $participant->points_earned }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $participant->completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $participant->completed ? 'Terminé' : 'En cours' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('admin.challenges.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Retour
            </a>
            <a href="{{ route('admin.challenges.edit', $challenge) }}" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection

