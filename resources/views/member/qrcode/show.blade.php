@extends('layouts.app')

@section('title', 'QR Code - Membre')

@section('content')
<div class="max-w-md mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <h1 class="text-2xl font-bold mb-4">Votre QR Code</h1>

        @if(isset($qrCodeBase64) && $qrCodeBase64)
            <div class="inline-block p-4 bg-gray-50 rounded-lg mb-4">
                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="QR Code" class="w-64 h-64 mx-auto" />
            </div>

            <p class="text-gray-600 text-sm mb-4">Scannez ce code pour enregistrer votre présence.</p>

            <div class="flex gap-3 justify-center mb-3">
                <a href="{{ route('member.qrcode.download') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Télécharger
                </a>
                <form action="{{ route('member.qrcode.regenerate') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-sync mr-2"></i>Régénérer
                    </button>
                </form>
            </div>

            <div class="text-left text-sm text-gray-700 bg-gray-50 rounded p-3">
                <p><strong>Données encodées:</strong></p>
                @php
                    $decoded = json_decode($qrData, true) ?: [];
                @endphp
                @if(!empty($decoded))
                    <ul class="text-xs space-y-1">
                        <li><strong>user_id:</strong> {{ $decoded['user_id'] ?? '—' }}</li>
                        <li><strong>type:</strong> {{ $decoded['type'] ?? '—' }}</li>
                        <li><strong>name:</strong> {{ $decoded['name'] ?? '—' }}</li>
                        <li><strong>email:</strong> {{ $decoded['email'] ?? '—' }}</li>
                        @if(!empty($decoded['timestamp']))
                            <li><strong>timestamp:</strong> {{ \Carbon\Carbon::createFromTimestamp($decoded['timestamp'])->format('d/m/Y H:i:s') }} ({{ $decoded['timestamp'] }})</li>
                        @endif
                        @if(!empty($decoded['expires_at']))
                            <li><strong>expires_at:</strong> {{ \Carbon\Carbon::createFromTimestamp($decoded['expires_at'])->format('d/m/Y H:i:s') }} ({{ $decoded['expires_at'] }})</li>
                            <li><strong>TTL:</strong>
                                @php
                                    $ttl = $decoded['expires_at'] - ($decoded['timestamp'] ?? now()->timestamp);
                                @endphp
                                {{ $ttl > 0 ? gmdate('H:i:s', $ttl) : 'Expiré' }}
                            </li>
                        @endif
                    </ul>
                @else
                    <pre class="text-xs break-all bg-transparent p-0 m-0">{{ $qrData }}</pre>
                @endif
            </div>
        @else
            <p class="text-gray-600">Aucun QR Code disponible pour le moment.</p>
        @endif

        @if(isset($qrStats))
            <div class="mt-4 text-left text-sm text-gray-600">
                <p><strong>Statistiques:</strong></p>
                <p>Aujourd'hui: {{ $qrStats['today_scans'] ?? 0 }}</p>
                <p>Total: {{ $qrStats['total_scans'] ?? 0 }}</p>
                @if($qrStats['last_scan'])
                    <p>Dernier scan: {{ $qrStats['last_scan']->check_in->format('d/m/Y H:i') }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
