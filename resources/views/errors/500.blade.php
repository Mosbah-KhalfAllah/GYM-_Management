<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur Serveur - 500</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous">
</head>
<body class="bg-gradient-to-br from-orange-900 via-red-900 to-black min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-orange-500/30 p-8 text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-server text-orange-400 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">500</h1>
                <h2 class="text-xl text-orange-300 font-semibold">Erreur Serveur</h2>
            </div>
            
            <p class="text-gray-300 mb-6 leading-relaxed">
                Une erreur interne s'est produite. Nos équipes ont été notifiées.
            </p>
            
            <div class="bg-orange-500/20 border border-orange-500/50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center text-orange-300">
                    <i class="fas fa-tools mr-2"></i>
                    <span class="text-sm">Incident signalé automatiquement</span>
                </div>
            </div>
            
            <div class="space-y-3">
                @auth
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'admin' => 'admin.dashboard',
                            'coach' => 'coach.dashboard',
                            default => 'member.dashboard'
                        };
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" 
                       class="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Retour au Tableau de Bord
                    </a>
                @else
                    <a href="{{ route('home') }}" 
                       class="w-full bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Retour à l'Accueil
                    </a>
                @endauth
                
                <button onclick="location.reload()" 
                        class="w-full bg-gray-600/50 hover:bg-gray-600/70 text-gray-300 font-semibold py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i>
                    Réessayer
                </button>
            </div>
        </div>
    </div>
</body>
</html>