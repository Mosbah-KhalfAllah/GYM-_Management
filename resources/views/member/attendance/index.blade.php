@extends('layouts.app')

@section('title', 'Présences - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Mes Présences</h1>
        <p class="opacity-90">Consultez votre historique et vos statistiques de présence</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium">Aujourd'hui</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium">Cette semaine</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['this_week'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium">Ce mois</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['this_month'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm font-medium">Total</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-pink-500">
            <p class="text-gray-600 text-sm font-medium">Durée moyenne</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ round($stats['average_duration'] ?? 0) }}m</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Trend Chart -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Présences (7 derniers jours)</h3>
            <div class="space-y-4">
                @foreach($trendData as $data)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 min-w-10">{{ $data['date'] }}</span>
                        <div class="flex-1 mx-4 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" 
                                 style="width: {{ max($data['count'] * 20, 5) }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 min-w-8 text-right">{{ $data['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Hourly Preferences -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Heures préférées</h3>
            <div class="space-y-3">
                @foreach($hourlyData as $data)
                    @if($data['count'] > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 min-w-12">{{ $data['hour'] }}</span>
                            <div class="flex-1 mx-4 bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" 
                                     style="width: {{ max($data['count'] * 10, 5) }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 min-w-8 text-right">{{ $data['count'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtres</h3>
        <form method="GET" action="{{ route('member.attendance.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Période</label>
                <select name="filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="day" {{ $filter === 'day' ? 'selected' : '' }}>Par jour</option>
                    <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>Par semaine</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Par mois</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>Par année</option>
                </select>
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                <input type="month" name="date" value="{{ $date }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historique des présences</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sortie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {{ $attendance->check_in->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $attendance->check_in->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($attendance->check_out)
                                    {{ $attendance->check_out->format('H:i') }}
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En cours
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($attendance->duration_minutes)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $attendance->duration_minutes }} min
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        --
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($attendance->entry_method === 'qr_code')
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-qrcode mr-2 text-blue-600"></i>Scanner
                                    </span>
                                @else
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-hand-paper mr-2 text-gray-600"></i>Manuel
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                                <p class="font-medium">Aucune présence enregistrée</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attendances->count() > 0)
            <div class="mt-6">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

