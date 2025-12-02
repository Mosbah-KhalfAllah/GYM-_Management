<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM Management - Système de gestion de salle de sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dumbbell text-white text-xl"></i>
                        </div>
                        <span class="ml-3 text-2xl font-bold text-gray-900">GYM PRO</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('login') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-transparent sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Gérez votre salle</span>
                            <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-purple-600">de sport efficacement</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Système complet de gestion de salle de sport avec QR codes, programmes personnalisés, cours collectifs et suivi des performances.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 md:py-4 md:text-lg md:px-10 transition-all duration-200">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Démarrer maintenant
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10 transition-all duration-200">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    En savoir plus
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <div class="h-56 w-full bg-gradient-to-r from-blue-500 to-purple-600 sm:h-72 md:h-96 lg:w-full lg:h-full rounded-l-3xl">
                <div class="h-full w-full flex items-center justify-center p-8">
                    <div class="text-white text-center">
                        <i class="fas fa-dumbbell text-8xl mb-4 opacity-80"></i>
                        <h3 class="text-2xl font-bold">GYM Management Pro</h3>
                        <p class="mt-2 opacity-90">Solution professionnelle</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Fonctionnalités</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Tout ce dont vous avez besoin
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Un système complet pour gérer tous les aspects de votre salle de sport
                </p>
            </div>

            <div class="mt-10">
                <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <!-- Feature 1 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Gestion des membres</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Gérez les inscriptions, adhésions et présences de vos membres avec des QR codes uniques.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gradient-to-r from-green-500 to-green-600 text-white">
                            <i class="fas fa-running"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Programmes personnalisés</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Créez des programmes d'entraînement sur mesure avec suivi de progression.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Cours collectifs</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Planifiez et gérez les réservations de cours collectifs avec système de QR codes.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="relative">
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <div class="ml-16">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Équipements & Maintenance</h3>
                            <p class="mt-2 text-base text-gray-500">
                                Suivez l'état des équipements et planifiez les maintenances préventives.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Utilisé par les meilleures salles
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Découvrez ce que disent nos clients
                </p>
            </div>
            <div class="mt-10">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Testimonial 1 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    JD
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Jean Dupont</h4>
                                <p class="text-gray-600">Propriétaire, Fitness Center</p>
                            </div>
                        </div>
                        <p class="text-gray-700">
                            "Ce système a révolutionné la gestion de ma salle. Les QR codes pour les présences sont géniaux !"
                        </p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold">
                                    MS
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Marie Simon</h4>
                                <p class="text-gray-600">Coach, Power Gym</p>
                            </div>
                        </div>
                        <p class="text-gray-700">
                            "Les programmes personnalisés et le suivi des progrès ont vraiment boosté la motivation de mes clients."
                        </p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-12 w-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                    PL
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900">Paul Leroy</h4>
                                <p class="text-gray-600">Membre premium</p>
                            </div>
                        </div>
                        <p class="text-gray-700">
                            "L'application est géniale ! Je peux suivre mes progrès et réserver mes cours en quelques clics."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Prêt à transformer votre salle ?</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-blue-100">
                Commencez gratuitement avec notre version de démonstration.
            </p>
            <a href="{{ route('login') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 sm:w-auto transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-rocket mr-2"></i>
                Essayer maintenant
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center">
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dumbbell text-white text-xl"></i>
                    </div>
                </div>
                <p class="mt-4 text-base text-gray-400">
                    GYM Management Pro - Système complet de gestion de salle de sport
                </p>
                <p class="mt-2 text-sm text-gray-500">
                    © {{ date('Y') }} Tous droits réservés.
                </p>
                <div class="mt-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Accéder au système
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.relative').forEach(card => {
            observer.observe(card);
        });
    </script>
</body>
</html>