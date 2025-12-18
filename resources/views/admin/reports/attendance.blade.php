@extends('layouts.app')

@section('title', 'Rapport Présences')
@section('page-title', 'Rapport Présences')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rapport Présences</h1>
        <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Total Présences</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $attendances->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Jours Actifs</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $dailyStats->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Moyenne/Jour</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $dailyStats->count() > 0 ? round($attendances->count() / $dailyStats->count(), 1) : 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des présences -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Présences du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </h3>
        </div>
        
        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sortie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durée</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $attendance->user->full_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $attendance->check_in->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'En cours' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->duration_minutes)
                                    {{ floor($attendance->duration_minutes / 60) }}h {{ $attendance->duration_minutes % 60 }}min
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-clock text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500">Aucune présence trouvée pour cette période</p>
            </div>
        @endif
    </div>
</div>
@endsection