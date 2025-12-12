{{-- resources/views/admin/members/attendance.blade.php --}}
@extends('layouts.app')

@section('title', 'Présences du Membre')
@section('page-title', 'Présences du Membre')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="text-center mb-8">
            <div class="h-20 w-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $member->full_name }}</h1>
            <p class="text-gray-600">Gestion des présences</p>
            <p class="text-sm text-gray-500 mt-2">ID: {{ $member->id }}</p>
        </div>
        
        <!-- Member Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Email</p>
                <p class="font-medium">{{ $member->email }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600">Statut d'adhésion</p>
                @if($member->membership)
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $member->membership->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($member->membership->status) }}
                    </span>
                @else
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                        Aucune adhésion
                    </span>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <button onclick="recordAttendance('check_in')" class="px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition">
                <i class="fas fa-sign-in-alt mr-2"></i>Enregistrer Entrée
            </button>
            <button onclick="recordAttendance('check_out')" class="px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Enregistrer Sortie
            </button>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            <a href="{{ route('admin.members.show', $member) }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour au profil
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium text-center">
                <i class="fas fa-list mr-2"></i>
                Tous les présences
            </a>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Historique des présences</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white border-b">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Entrée</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Sortie</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Durée</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $attendances = \App\Models\Attendance::where('user_id', $member->id)
                                ->latest('check_in')
                                ->limit(20)
                                ->get();
                        @endphp
                        @forelse($attendances as $attendance)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-2 font-medium text-gray-900">{{ $attendance->check_in->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center gap-1">
                                    <span class="text-green-600">✓</span>
                                    {{ $attendance->check_in->format('H:i') }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($attendance->check_out)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="text-red-600">✓</span>
                                        {{ $attendance->check_out->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-orange-600 font-medium">En cours</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($attendance->duration_minutes)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                                        {{ intval($attendance->duration_minutes / 60) }}h {{ $attendance->duration_minutes % 60 }}min
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-history text-2xl mb-2"></i>
                                <p>Aucune présence enregistrée</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
let memberId = {{ $member->id }};

async function recordAttendance(action) {
    try {
        const response = await fetch('{{ route("admin.attendance.record") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: memberId,
                action: action
            })
        });

        const result = await response.json();

        if (result.success) {
            const message = action === 'check_in' ? 
                '✅ Entrée enregistrée avec succès' : 
                '🚪 Sortie enregistrée avec succès';
            alert(message);
            location.reload();
        } else {
            alert('❌ Erreur: ' + result.error);
        }
    } catch (error) {
        alert('Erreur de communication: ' + error.message);
        console.error('Error:', error);
    }
}
</script>
@endsection
