<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM PRO - Système de gestion de salle de sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-secondary { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .gradient-accent { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .text-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .pulse-slow { animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .fade-in { animation: fadeIn 1s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .blob { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; animation: blob 7s ease-in-out infinite; }
        @keyframes blob { 0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; } 25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; } 50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; } 75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; } }
    </style>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-sm shadow-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center">
                        <i class="fas fa-dumbbell text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">GYM <span class="text-gradient">PRO</span></span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('inquiry.create') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        Contact
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 gradient-primary text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300">
                        Connexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="fade-in">
                    <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-tight mb-8">
                        Gérez votre
                        <span class="text-gradient block">Salle de Sport</span>
                        en toute simplicité
                    </h1>
                    <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                        Solution complète pour la gestion des membres, paiements, cours et équipements. 
                        Interface moderne et intuitive pour optimiser votre business.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 gradient-primary text-white font-semibold rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-rocket mr-3"></i>
                            Commencer maintenant
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-gray-400 transition-all duration-300">
                            <i class="fas fa-play mr-3"></i>
                            Découvrir
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute inset-0 gradient-accent opacity-20 blob"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8">
                        <div class="text-center mb-8">
                            <div class="w-20 h-20 gradient-secondary rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-white text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Dashboard Admin</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-blue-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">Membres actifs</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">247</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-money-bill text-green-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">Revenus mensuel</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">12,450 DT</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar text-purple-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">Cours aujourd'hui</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">8</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-gray-900 mb-6">
                    Fonctionnalités <span class="text-gradient">Complètes</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tous les outils nécessaires pour gérer efficacement votre salle de sport
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 gradient-primary rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestion des Membres</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Inscriptions, profils complets, suivi des adhésions et historique des présences.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 gradient-secondary rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-credit-card text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Système de Paiements</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Gestion complète des transactions, méthodes multiples et rapports financiers.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 gradient-accent rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Cours Collectifs</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Planification, réservations et gestion des coachs avec système intégré.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-dumbbell text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Équipements</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Inventaire complet, suivi de maintenance et gestion des emplacements.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-trophy text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Défis & Challenges</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Création de défis fitness, suivi des participations et système de récompenses.
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 card-hover">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-400 to-red-500 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Analytics Avancés</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Tableaux de bord détaillés, statistiques et rapports pour optimiser votre business.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="fade-in">
                    <div class="text-4xl font-black text-gradient mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Salles Équipées</div>
                </div>
                <div class="fade-in">
                    <div class="text-4xl font-black text-gradient mb-2">50K+</div>
                    <div class="text-gray-600 font-medium">Membres Actifs</div>
                </div>
                <div class="fade-in">
                    <div class="text-4xl font-black text-gradient mb-2">99.9%</div>
                    <div class="text-gray-600 font-medium">Uptime Garanti</div>
                </div>
                <div class="fade-in">
                    <div class="text-4xl font-black text-gradient mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Support Client</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-primary">
        <div class="max-w-4xl mx-auto text-center px-6 lg:px-8">
            <h2 class="text-4xl font-black text-white mb-6">
                Prêt à Transformer Votre Salle ?
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                Rejoignez les centaines de propriétaires qui font confiance à GYM PRO pour développer leur business.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-xl hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-rocket mr-3"></i>
                    Démarrer Gratuitement
                </a>
                <a href="{{ route('inquiry.create') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300">
                    <i class="fas fa-phone mr-3"></i>
                    Nous Contacter
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 gradient-primary rounded-2xl flex items-center justify-center">
                            <i class="fas fa-dumbbell text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold">GYM <span class="text-blue-400">PRO</span></span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md">
                        Solution complète de gestion de salle de sport. Moderne, efficace et professionnel.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Produit</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sécurité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('inquiry.create') }}" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>© {{ date('Y') }} GYM PRO. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card-hover').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>