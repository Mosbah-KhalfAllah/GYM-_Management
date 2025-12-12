@extends('layouts.app')

@section('title', 'Enregistrer une présence - Coach')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-3xl font-bold mb-2">Enregistrer une présence</h1>
        <p class="text-gray-600">Enregistrer manuellement l'entrée/sortie des membres</p>
    </div>

    <!-- Recherche de membre -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">🔍 Sélectionner un membre</h2>
        
        <div class="relative mb-4">
            <input 
                type="text" 
                id="memberSearch" 
                placeholder="Chercher un membre par nom ou email..." 
                class="w-full border rounded-lg p-3 focus:outline-none focus:border-blue-500"
            >
            <div id="searchResults" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg mt-1 max-h-64 overflow-y-auto z-10">
            </div>
        </div>

        <div id="selectedMember" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-lg mb-4">
            <p class="text-sm text-gray-600">Membre sélectionné:</p>
            <p class="text-lg font-bold text-blue-900" id="memberName"></p>
        </div>
    </div>

    <!-- Actions de présence -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <button 
            onclick="recordAttendance('check_in')" 
            class="p-6 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            id="checkInBtn"
            disabled
        >
            <div class="text-4xl mb-2">📍</div>
            <div class="text-lg font-bold">Enregistrer Entrée</div>
            <div class="text-sm opacity-90">Check-in (Arrivée)</div>
        </button>

        <button 
            onclick="recordAttendance('check_out')" 
            class="p-6 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg shadow-lg transition transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
            id="checkOutBtn"
            disabled
        >
            <div class="text-4xl mb-2">🚪</div>
            <div class="text-lg font-bold">Enregistrer Sortie</div>
            <div class="text-sm opacity-90">Check-out (Départ)</div>
        </button>
    </div>

    <!-- Messages de statut -->
    <div id="successMessage" class="hidden p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
        <div class="flex items-center gap-3">
            <span class="text-3xl">✅</span>
            <div>
                <p class="font-bold text-green-900" id="successText"></p>
                <p class="text-sm text-green-700" id="successTime"></p>
            </div>
        </div>
    </div>

    <div id="errorMessage" class="hidden p-4 bg-red-50 border border-red-200 rounded-lg mb-6">
        <div class="flex items-center gap-3">
            <span class="text-3xl">❌</span>
            <div>
                <p class="font-bold text-red-900" id="errorText"></p>
            </div>
        </div>
    </div>

    <!-- Informations du jour -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold mb-4">📊 Présences d'aujourd'hui (Mes membres)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Membre</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Entrée</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Sortie</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Durée</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $coachId = auth()->id();
                        $memberIds = \App\Models\User::whereHas('programs', function ($query) use ($coachId) {
                            $query->where('coach_id', $coachId);
                        })->pluck('id');
                        $todayAttendances = \App\Models\Attendance::whereDate('check_in', today())
                            ->whereIn('user_id', $memberIds)
                            ->with('user')
                            ->latest('check_in')
                            ->get();
                    @endphp
                    @forelse($todayAttendances as $attendance)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <div class="font-medium text-gray-900">{{ $attendance->user->first_name }} {{ $attendance->user->last_name }}</div>
                            <div class="text-xs text-gray-500">{{ $attendance->user->email }}</div>
                        </td>
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
                                <span class="text-orange-600 font-medium">En présence</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if($attendance->check_out)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ intval($attendance->duration_minutes / 60) }}h {{ $attendance->duration_minutes % 60 }}min
                                </span>
                            @else
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $diff = $attendance->check_in->diffInMinutes($now);
                                @endphp
                                <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ intval($diff / 60) }}h {{ $diff % 60 }}min
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <div class="text-4xl mb-2">📭</div>
                            <p>Aucune présence enregistrée aujourd'hui</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let selectedMemberId = null;

    async function loadMembers() {
        const searchInput = document.getElementById('memberSearch');
        
        searchInput.addEventListener('input', async function(e) {
            const query = e.target.value.toLowerCase().trim();
            if (query.length < 2) {
                document.getElementById('searchResults').classList.add('hidden');
                return;
            }
            
            try {
                const response = await fetch('{{ route("coach.api.members.search") }}?q=' + encodeURIComponent(query));
                const members = await response.json();
                displaySearchResults(members);
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
                displaySearchResults([]);
            }
        });
    }

    function displaySearchResults(members) {
        const resultsDiv = document.getElementById('searchResults');
        
        if (!members || members.length === 0) {
            resultsDiv.innerHTML = '<div class="p-4 text-gray-500 text-center">Aucun membre trouvé</div>';
            resultsDiv.classList.remove('hidden');
            return;
        }

        resultsDiv.innerHTML = members.map(member => `
            <div class="p-3 border-b cursor-pointer hover:bg-blue-50" onclick="selectMember(${member.id}, '${member.first_name} ${member.last_name}')">
                <div class="font-medium text-gray-900">${member.first_name} ${member.last_name}</div>
                <div class="text-xs text-gray-500">${member.email}</div>
            </div>
        `).join('');
        
        resultsDiv.classList.remove('hidden');
    }

    function selectMember(memberId, memberName) {
        selectedMemberId = memberId;
        document.getElementById('memberName').textContent = memberName;
        document.getElementById('selectedMember').classList.remove('hidden');
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('memberSearch').value = memberName;
        document.getElementById('checkInBtn').disabled = false;
        document.getElementById('checkOutBtn').disabled = false;
    }

    async function recordAttendance(action) {
        if (!selectedMemberId) {
            showError('Veuillez sélectionner un membre');
            return;
        }

        try {
            const response = await fetch('{{ route("coach.attendance.record") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: selectedMemberId,
                    action: action
                })
            });

            const result = await response.json();

            if (result.success) {
                const actionText = action === 'check_in' ? '✅ Entrée enregistrée' : '🚪 Sortie enregistrée';
                const timeText = action === 'check_in' ? 
                    `Arrivée à ${result.check_in}` : 
                    `Départ à ${result.check_out}`;
                
                showSuccess(actionText, timeText);
                
                setTimeout(() => {
                    selectedMemberId = null;
                    document.getElementById('selectedMember').classList.add('hidden');
                    document.getElementById('memberSearch').value = '';
                    document.getElementById('checkInBtn').disabled = true;
                    document.getElementById('checkOutBtn').disabled = true;
                    location.reload();
                }, 2000);
            } else {
                showError(result.error || 'Erreur lors de l\'enregistrement');
            }
        } catch (error) {
            showError('Erreur de communication: ' + error.message);
            console.error('Error:', error);
        }
    }

    function showSuccess(message, time) {
        document.getElementById('successText').textContent = message;
        document.getElementById('successTime').textContent = time;
        document.getElementById('successMessage').classList.remove('hidden');
        document.getElementById('errorMessage').classList.add('hidden');

        setTimeout(() => {
            document.getElementById('successMessage').classList.add('hidden');
        }, 3000);
    }

    function showError(message) {
        document.getElementById('errorText').textContent = message;
        document.getElementById('errorMessage').classList.remove('hidden');
        document.getElementById('successMessage').classList.add('hidden');

        setTimeout(() => {
            document.getElementById('errorMessage').classList.add('hidden');
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadMembers();
    });
</script>
@endsection