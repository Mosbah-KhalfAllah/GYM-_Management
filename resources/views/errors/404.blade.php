<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Non Trouvée - 404</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous">
</head>
<body class="bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-purple-500/30 p-8 text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-purple-400 text-3xl"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">404</h1>
                <h2 class="text-xl text-purple-300 font-semibold">Page Non Trouvée</h2>
            </div>
            
            <p class="text-gray-300 mb-6 leading-relaxed">
                La page que vous recherchez n'existe pas ou vous n'avez pas l'autorisation d'y accéder.
            </p>
                        
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
                       class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Retour au Tableau de Bord
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se Connecter
                    </a>
                @endauth
                
                <button onclick="history.back()" 
                        class="w-full bg-gray-600/50 hover:bg-gray-600/70 text-gray-300 font-semibold py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Page Précédente
                </button>
            </div>
        </div>
    </div>
</body>
</html>