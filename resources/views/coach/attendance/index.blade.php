@extends('layouts.app')

@section('title', 'Présences - Coach')
@section('page-title', 'Gestion des Présences')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Présences des Membres</h1>
                <p class="text-gray-600 mt-1">Historique des entrées/sorties de vos membres supervisés</p>
            </div>
            <a href="{{ route('coach.attendance.record') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2"></i>Enregistrer présence
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Aujourd'hui</p>
                        <p class="text-2xl font-bold">{{ $todayCount ?? 0 }}</p>
                    </div>
                    <i class="fas fa-calendar-day text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Cette semaine</p>
                        <p class="text-2xl font-bold">{{ $weekCount ?? 0 }}</p>
                    </div>
                    <i class="fas fa-calendar-week text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total</p>
                        <p class="text-2xl font-bold">{{ $attendances->total() ?? $attendances->count() }}</p>
                    </div>
                    <i class="fas fa-chart-bar text-2xl opacity-80"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des présences -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if($attendances && $attendances->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sortie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ $attendance->user ? substr($attendance->user->first_name, 0, 1) . substr($attendance->user->last_name, 0, 1) : '?' }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $attendance->user ? $attendance->user->first_name . ' ' . $attendance->user->last_name : 'Membre supprimé' }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user?->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $attendance->check_in?->format('H:i') ?? '-' }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendance->check_in?->format('d/m/Y') ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->check_out)
                                        <div class="text-sm text-gray-900">{{ $attendance->check_out->format('H:i') }}</div>
                                        <div class="text-sm text-gray-500">{{ $attendance->check_out->format('d/m/Y') }}</div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>En cours
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->duration_minutes)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ intval($attendance->duration_minutes / 60) }}h {{ $attendance->duration_minutes % 60 }}min
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $attendance->entry_method === 'manual' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                        <i class="fas {{ $attendance->entry_method === 'manual' ? 'fa-hand-paper' : 'fa-qrcode' }} mr-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $attendance->entry_method ?? 'unknown')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($attendance->user)
                                            <a href="{{ route('coach.members.show', $attendance->user) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-600 px-3 py-1 rounded-lg transition-all duration-200 text-xs font-medium">
                                                Voir
                                            </a>
                                            @if(is_null($attendance->check_out))
                                                <form action="{{ route('coach.attendance.checkout', $attendance) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 px-3 py-1 rounded-lg transition-all duration-200 text-xs font-medium">
                                                        Sortie
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune présence enregistrée</h3>
                <p class="text-gray-600 mb-6">Les présences de vos membres supervisés apparaîtront ici.</p>
                <a href="{{ route('coach.attendance.record') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Enregistrer une présence
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

