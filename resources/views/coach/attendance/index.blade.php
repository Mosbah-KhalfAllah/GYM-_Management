@extends('layouts.app')

@section('title', 'Présences - Coach')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Présences</h1>
            <p class="text-gray-600 mt-1">Historique des entrées/sorties des membres supervisés</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500">Aujourd'hui</p>
                <p class="text-xl font-bold">{{ $todayCount ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500">Cette semaine</p>
                <p class="text-xl font-bold">{{ $weekCount ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-xl font-bold">{{ $attendances->total() ?? $attendances->count() }}</p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('coach.attendance.scanner') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Ouvrir le scanner</a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        @if($attendances && $attendances->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Membre</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Entrée</th>
                            <th class="px-4 py-2 text-left">Sortie</th>
                            <th class="px-4 py-2 text-left">Durée</th>
                            <th class="px-4 py-2 text-left">Méthode</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @foreach($attendances as $attendance)
                            <tr>
                                <td class="px-4 py-3">
                                    {{ $attendance->user?->first_name ? $attendance->user->first_name . ' ' . $attendance->user->last_name : '—' }}
                                </td>
                                <td class="px-4 py-3">{{ $attendance->user?->email ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_in?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_out?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $attendance->duration_minutes ? $attendance->duration_minutes . ' min' : '-' }}</td>
                                <td class="px-4 py-3">{{ ucfirst(str_replace('_',' ', $attendance->entry_method ?? 'unknown')) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($attendance->user)
                                        <a href="{{ route('coach.members.show', $attendance->user) }}" class="text-blue-600 mr-3">Voir</a>

                                        @if(is_null($attendance->check_out))
                                            <form action="{{ route('coach.attendance.checkout', $attendance) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 mr-3">Marquer sortie</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('coach.attendance.force-checkin', ['member' => $attendance->user->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-green-600">Forcer entrée</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        @else
            <p class="text-gray-600">Aucune présence enregistrée pour les membres assignés.</p>
        @endif
    </div>
</div>
@endsection
