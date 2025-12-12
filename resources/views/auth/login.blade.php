<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GYM Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
            <!-- Logo -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-dumbbell text-white text-3xl"></i>
                </div>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                    GYM PRO
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Système de gestion de salle de sport
                </p>
            </div>

            <!-- Formulaire de connexion -->
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST" id="loginForm" novalidate>
                @csrf
                
                <!-- Messages d'erreur -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Champ email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="votre@email.com"
                        >
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500 hidden" id="emailError">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="emailMsg"></p>
                </div>

                <!-- Champ mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                            placeholder="Votre mot de passe"
                        >
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-red-500 hidden" id="passwordError">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="passwordMsg"></p>
                </div>

                <!-- Remember me & Forgot password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            Se souvenir de moi
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            Mot de passe oublié?
                        </a>
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200"></i>
                        </span>
                        Se connecter
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 mt-6 pt-6 border-t border-gray-200">
                <p>© {{ date('Y') }} GYM Management. Tous droits réservés.</p>
                <p class="mt-1">Version 1.0.0</p>
            </div>
        </div>
    </div>

    <!-- Script pour améliorer l'UX -->
    <script>
        // Validation login
        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showError(input, errorSpan, errorMsg, msg) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-300');
            errorSpan.classList.remove('hidden');
            errorMsg.classList.remove('hidden');
            errorMsg.textContent = msg;
        }

        function clearError(input, errorSpan, errorMsg) {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
            errorSpan.classList.add('hidden');
            errorMsg.classList.add('hidden');
        }

        // Real-time email validation
        emailInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, document.getElementById('emailError'), document.getElementById('emailMsg'), 'L\'email est requis');
            } else if (!validateEmail(this.value)) {
                showError(this, document.getElementById('emailError'), document.getElementById('emailMsg'), 'Email invalide');
            } else {
                clearError(this, document.getElementById('emailError'), document.getElementById('emailMsg'));
            }
        });

        emailInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, document.getElementById('emailError'), document.getElementById('emailMsg'), 'L\'email est requis');
            } else if (!validateEmail(this.value)) {
                showError(this, document.getElementById('emailError'), document.getElementById('emailMsg'), 'Email invalide');
            } else {
                clearError(this, document.getElementById('emailError'), document.getElementById('emailMsg'));
            }
        });

        // Real-time password validation
        passwordInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                showError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Le mot de passe est requis');
            } else if (this.value.length < 6) {
                showError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Minimum 6 caractères');
            } else {
                clearError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'));
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Le mot de passe est requis');
            } else if (this.value.length < 6) {
                showError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Minimum 6 caractères');
            } else {
                clearError(this, document.getElementById('passwordError'), document.getElementById('passwordMsg'));
            }
        });

        // Form submission validation
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Email validation
            if (emailInput.value.trim() === '') {
                showError(emailInput, document.getElementById('emailError'), document.getElementById('emailMsg'), 'L\'email est requis');
                isValid = false;
            } else if (!validateEmail(emailInput.value)) {
                showError(emailInput, document.getElementById('emailError'), document.getElementById('emailMsg'), 'Email invalide');
                isValid = false;
            } else {
                clearError(emailInput, document.getElementById('emailError'), document.getElementById('emailMsg'));
            }

            // Password validation
            if (passwordInput.value.trim() === '') {
                showError(passwordInput, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Le mot de passe est requis');
                isValid = false;
            } else if (passwordInput.value.length < 6) {
                showError(passwordInput, document.getElementById('passwordError'), document.getElementById('passwordMsg'), 'Minimum 6 caractères');
                isValid = false;
            } else {
                clearError(passwordInput, document.getElementById('passwordError'), document.getElementById('passwordMsg'));
            }

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez corriger les erreurs dans le formulaire');
                return;
            }

            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Connexion en cours...';
            button.disabled = true;
        });

        // Toggle password visibility
        const togglePassword = document.createElement('span');
        togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
        togglePassword.className = 'absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600 transition-colors';
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        passwordInput.parentNode.appendChild(togglePassword);

        // Auto-focus on email field
        emailInput.focus();
    </script>
</body>
</html>
