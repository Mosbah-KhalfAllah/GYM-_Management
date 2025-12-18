@extends('layouts.app')

@section('title', 'Présences')
@section('page-title', isset($member) ? 'Présences de ' . $member->full_name : 'Présences')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            @if(isset($member))
                <h1 class="text-2xl font-bold text-gray-900">Présences de {{ $member->full_name }}</h1>
                <p class="text-gray-600">Historique des entrées et sorties</p>
            @else
                <h1 class="text-2xl font-bold text-gray-900">Suivi des présences</h1>
                <p class="text-gray-600">Gestion des entrées et sorties</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            @if(isset($member))
                <a href="{{ route('admin.attendance.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Toutes les présences
                </a>
            @endif
            <a href="{{ route('admin.attendance.record') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Enregistrer présence
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aujourd'hui</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todayCount }}</p>
                </div>
                <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cette semaine</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $weekCount }}</p>
                </div>
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $attendances->total() }}</p>
                </div>
                <div class="h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Selection -->
    @if(!isset($member))
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Sélectionner un membre</h2>
        <div class="flex gap-4">
            <input type="text" id="phone-search" placeholder="Numéro de téléphone" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button onclick="searchMember()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-search mr-2"></i>Rechercher
            </button>
        </div>
        <div id="member-result" class="mt-4 hidden">
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div id="member-info"></div>
                    <a id="view-attendance" href="#" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        Voir présences
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendances List -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Liste des présences</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        @if(!isset($member))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sortie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            @if(!isset($member))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($attendance->user->first_name, 0, 1) }}{{ substr($attendance->user->last_name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : 'En cours' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attendance->duration_minutes)
                                    {{ floor($attendance->duration_minutes / 60) }}h {{ $attendance->duration_minutes % 60 }}min
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $attendance->entry_method === 'qr_code' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $attendance->entry_method === 'qr_code' ? 'Scanner' : 'Manuel' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ isset($member) ? '5' : '6' }}" class="px-6 py-4 text-center text-gray-500">
                                Aucune présence enregistrée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function searchMember() {
    const phone = document.getElementById('phone-search').value;
    if (!phone) return;
    
    fetch('{{ route("admin.members.search-by-phone") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ phone: phone })
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('member-result');
        const memberInfo = document.getElementById('member-info');
        const viewLink = document.getElementById('view-attendance');
        
        if (data.success) {
            memberInfo.innerHTML = `
                <div>
                    <p class="font-medium">${data.member.name}</p>
                    <p class="text-sm text-gray-600">${data.member.email} - ${data.member.phone}</p>
                </div>
            `;
            viewLink.href = `{{ route('admin.attendance.index') }}?member_id=${data.member.id}`;
            resultDiv.classList.remove('hidden');
        } else {
            memberInfo.innerHTML = `<p class="text-red-600">${data.message}</p>`;
            viewLink.classList.add('hidden');
            resultDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

document.getElementById('phone-search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchMember();
    }
});
</script>
@endsection

