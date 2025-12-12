@extends('layouts.app')

@section('title', 'Présence - Membre')

@section('content')
    <div class="max-w-md mx-auto p-6">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <h1 class="text-2xl font-bold mb-4">Enregistrer votre présence</h1>        <div class="text-center mb-6">
            <p class="text-gray-700">Cliquez sur le bouton ci-dessous pour enregistrer votre entrée ou sortie.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-blue-800">{{ session('info') }}</div>
        @endif

        <div class="flex justify-center mb-4">
            <form action="{{ route('member.attendance.toggle') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg shadow hover:from-indigo-700 hover:to-indigo-800 transition"> 
                    <i class="fas fa-door-open mr-2"></i>
                    Enregistrer ma présence
                </button>
            </form>
        </div>

        @if(isset($qrStats))
            <div class="mt-4 text-left text-sm text-gray-600">
                <p><strong>Statistiques (manuelles):</strong></p>
                <p>Aujourd'hui: {{ $qrStats['today_scans'] ?? 0 }}</p>
                <p>Total: {{ $qrStats['total_scans'] ?? 0 }}</p>
                @if($qrStats['last_scan'])
                    <p>Dernier enregistrement: {{ $qrStats['last_scan']->check_in->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

