<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - GYM PRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="container mx-auto px-6 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl">
                    <i class="fas fa-dumbbell text-purple-600 text-2xl"></i>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">Contactez-nous</h1>
            <p class="text-xl text-white/90">Nous sommes là pour répondre à toutes vos questions</p>
        </div>

        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="glass-card rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="Votre prénom" value="{{ old('first_name') }}">
                            @error('first_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                   placeholder="Votre nom" value="{{ old('last_name') }}">
                            @error('last_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                               placeholder="votre@email.com" value="{{ old('email') }}">
                        @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                               placeholder="06 12 34 56 78" value="{{ old('phone') }}">
                        @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                        <select name="subject" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="">Choisir un sujet</option>
                            <option value="membership" {{ old('subject') == 'membership' ? 'selected' : '' }}>Adhésion / Abonnement</option>
                            <option value="classes" {{ old('subject') == 'classes' ? 'selected' : '' }}>Cours collectifs</option>
                            <option value="personal_training" {{ old('subject') == 'personal_training' ? 'selected' : '' }}>Coaching personnel</option>
                            <option value="equipment" {{ old('subject') == 'equipment' ? 'selected' : '' }}>Équipements</option>
                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('subject')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" required rows="5" maxlength="500"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                  placeholder="Décrivez votre demande...">{{ old('message') }}</textarea>
                        @error('message')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        <div class="text-sm text-gray-500 mt-1">Maximum 500 caractères</div>
                    </div>
                    
                    <div class="flex gap-4">
                        <a href="{{ route('home') }}" 
                           class="flex-1 px-6 py-3 border border-gray-300 rounded-xl text-gray-700 text-center hover:bg-gray-50 transition-colors">
                            Retour
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 gradient-bg text-white rounded-xl hover:shadow-lg transition-all duration-300 font-medium">
                            <i class="fas fa-paper-plane mr-2"></i>Envoyer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="space-y-8">
                <!-- Info Cards -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Nos coordonnées</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Adresse</h4>
                                <p class="text-gray-600">123 Rue du Sport, 75001 Paris</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Téléphone</h4>
                                <p class="text-gray-600">01 23 45 67 89</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">contact@gympro.fr</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-orange-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Horaires</h4>
                                <p class="text-gray-600">Lun-Ven: 6h-22h<br>Sam-Dim: 8h-20h</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="glass-card rounded-3xl p-8 shadow-2xl">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Actions rapides</h3>
                    
                    <div class="space-y-4">
                        <a href="{{ route('login') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-blue-500 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-3"></i>
                            <div>
                                <div class="font-semibold">Connexion</div>
                                <div class="text-sm opacity-90">Accéder à votre espace</div>
                            </div>
                        </a>
                        
                        <a href="{{ route('home') }}" 
                           class="flex items-center p-4 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-xl hover:shadow-lg transition-all duration-300">
                            <i class="fas fa-home mr-3"></i>
                            <div>
                                <div class="font-semibold">Accueil</div>
                                <div class="text-sm opacity-90">Retour à la page d'accueil</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>