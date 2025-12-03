@extends('layouts.app')

@section('title', 'Scanner - Coach')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h1 class="text-3xl font-bold mb-2">Scanner de présence</h1>
        <p class="text-gray-600">Enregistrez l'entrée/sortie des membres</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h2 class="font-semibold text-blue-900 mb-2">📷 Comment utiliser la caméra:</h2>
        <ol class="list-decimal list-inside text-sm text-blue-800 space-y-1">
            <li>Acceptez l'accès à la caméra quand demandé</li>
            <li>Pointez la caméra vers le code du membre</li>
            <li>Attendez que le code soit détecté automatiquement</li>
            <li>L'entrée/sortie sera enregistrée automatiquement</li>
        </ol>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-600 mb-4">Vous pouvez aussi coller les données du code JSON ci-dessous si la caméra ne fonctionne pas.</p>

        <div id="scannerArea" class="mb-4">
            <div class="bg-gray-900 rounded border-2 border-gray-700 overflow-hidden" style="max-width: 500px; margin: 0 auto;">
                <video id="video" autoplay muted playsinline style="width: 100%; display: block;"></video>
            </div>
            <canvas id="canvas" class="hidden"></canvas>
            <div class="mt-3 text-center">
                <p id="scannerStatus" class="text-sm font-medium text-gray-700">🔄 Initialisation du scanner...</p>
            </div>
        </div>

        <div class="mb-4">
            <form id="pasteForm">
                <label class="block text-sm font-medium text-gray-700 mb-2">💾 Ou collez les données du membre:</label>
                <textarea id="qrDataInput" name="qr_data" rows="4" class="mt-1 block w-full border rounded p-2 text-xs" placeholder='Collez ici le contenu JSON du code'></textarea>
                <div class="mt-2">
                    <button id="pasteSubmit" type="button" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Soumettre</button>
                </div>
            </form>
        </div>

        <div id="result" class="mt-4"></div>
    </div>

    <script>
        (function(){
            const status = document.getElementById('scannerStatus');
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const qrDataInput = document.getElementById('qrDataInput');
            const pasteSubmit = document.getElementById('pasteSubmit');
            const result = document.getElementById('result');

            const postScan = async (qrText) => {
                status.textContent = '⏳ Envoi des données...';
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                try {
                    const res = await fetch('{{ route('coach.attendance.scan') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ qr_data: qrText })
                    });
                    const json = await res.json();
                    const bgColor = json.success ? 'bg-green-100 border-green-300' : 'bg-red-100 border-red-300';
                    const textColor = json.success ? 'text-green-800' : 'text-red-800';
                    result.innerHTML = '<div class="p-3 border rounded ' + bgColor + ' ' + textColor + '">' +
                        '<strong>' + (json.message || 'Réponse du serveur') + '</strong>' +
                        '<pre class="text-xs mt-2">' + JSON.stringify(json, null, 2) + '</pre>' +
                        '</div>';
                    status.textContent = json.success ? '✅ ' + (json.message || 'OK') : '❌ ' + (json.message || 'Erreur');
                } catch (err) {
                    result.innerHTML = '<div class="p-3 bg-red-100 border border-red-300 rounded text-red-800">Erreur: ' + err.message + '</div>';
                    status.textContent = '❌ Erreur réseau: ' + err.message;
                }
            };

            pasteSubmit.addEventListener('click', () => {
                const val = qrDataInput.value.trim();
                if (!val) {
                    status.textContent = '❌ Veuillez coller les données du QR.';
                    return;
                }
                postScan(val);
            });

            // Try using the native BarcodeDetector if available
            if (window.BarcodeDetector) {
                const formats = ['qr_code'];
                const detector = new BarcodeDetector({formats});

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(stream => {
                        video.srcObject = stream;
                        video.play();
                        status.textContent = '✅ Caméra activée, en attente de QR...';

                        const scanLoop = async () => {
                            try {
                                const detections = await detector.detect(video);
                                if (detections && detections.length) {
                                    const qrText = detections[0].rawValue;
                                    status.textContent = '📍 QR détecté — envoi...';
                                    postScan(qrText);
                                }
                            } catch (e) {
                                // ignore detection errors
                            }
                            requestAnimationFrame(scanLoop);
                        };

                        requestAnimationFrame(scanLoop);
                    })
                    .catch(err => {
                        status.textContent = '❌ Impossible d\'accéder à la caméra: ' + err.message;
                    });
            } else {
                status.textContent = '⚠️ Scanner caméra non supporté. Utilisez le champ de saisie ci-dessous.';
            }
        })();
    </script>
</div>
@endsection
