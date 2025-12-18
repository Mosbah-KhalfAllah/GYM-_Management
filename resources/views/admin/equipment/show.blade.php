@extends('layouts.app')

@section('title', 'Détails équipement')
@section('page-title', 'Détails équipement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ $equipment->name }}</h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium 
                {{ $equipment->status === 'available' ? 'bg-green-100 text-green-800' : 
                   ($equipment->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($equipment->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Informations générales</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Catégorie</p>
                    <p class="font-medium">{{ $equipment->category }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Numéro de série</p>
                    <p class="font-medium">{{ $equipment->serial_number }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Emplacement</p>
                    <p class="font-medium">{{ $equipment->location }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Prix d'achat</p>
                    <p class="font-medium">{{ number_format($equipment->purchase_price, 2) }} TND</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Maintenance</h3>
                
                <div>
                    <p class="text-sm text-gray-600">Date d'achat</p>
                    <p class="font-medium">{{ $equipment->purchase_date->format('d/m/Y') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Dernière maintenance</p>
                    <p class="font-medium">{{ $equipment->last_maintenance_date ? $equipment->last_maintenance_date->format('d/m/Y') : 'Aucune' }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-600">Prochaine maintenance</p>
                    <p class="font-medium">{{ $equipment->next_maintenance_date ? $equipment->next_maintenance_date->format('d/m/Y') : 'Non programmée' }}</p>
                </div>
            </div>
        </div>
        
        @if($equipment->maintenanceLogs && $equipment->maintenanceLogs->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Historique de maintenance</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Coût</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($equipment->maintenanceLogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->maintenance_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $log->type === 'preventive' ? 'bg-blue-100 text-blue-800' : 
                                               ($log->type === 'corrective' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($log->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $log->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($log->cost, 2) }} TND
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('admin.equipment.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Retour
            </a>
            <a href="{{ route('admin.equipment.edit', $equipment) }}" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Modifier
            </a>
        </div>
    </div>
</div>
@endsection

