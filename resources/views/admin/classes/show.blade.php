@extends('layouts.app')

@section('title', 'Détails de la classe')
@section('page-title', 'Détails de la classe')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $class->name }}</h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                {{ ucfirst($class->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Informations générales</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Description</p>
                    <p class="font-medium">{{ $class->description }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Coach</p>
                    <p class="font-medium">{{ $class->coach->full_name }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Lieu</p>
                    <p class="font-medium">{{ $class->location }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Prix</p>
                    <p class="font-medium">{{ number_format($class->price, 2) }} TND</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Horaires et capacité</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Date et heure</p>
                    <p class="font-medium">{{ $class->schedule_time->format('d/m/Y à H:i') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Durée</p>
                    <p class="font-medium">{{ $class->duration_minutes }} minutes</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Participants</p>
                    <p class="font-medium">{{ $class->registered_count }} / {{ $class->capacity }}</p>
                </div>
            </div>
        </div>
        
        @if($class->bookings->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Participants inscrits</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($class->bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($booking->member->first_name, 0, 1) }}{{ substr($booking->member->last_name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->member->full_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $booking->member->phone }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($booking->status) }}
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
            <a href="{{ route('admin.classes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Retour
            </a>
            <a href="{{ route('admin.classes.edit', $class) }}" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection

