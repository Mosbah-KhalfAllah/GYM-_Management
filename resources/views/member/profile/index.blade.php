@extends('layouts.app')

@section('title', 'Profil - Membre')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et vos préférences</p>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="text-yellow-800 font-semibold mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Veuillez corriger les erreurs suivantes:
            </h3>
            <ul class="text-yellow-700 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-indigo-600">{{ $stats['total_workouts'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Séances complétées</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-green-600">{{ $stats['total_exercises'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Exercices effectués</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_classes'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Classes suivies</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-purple-600">{{ $stats['challenge_points'] }}</div>
            <p class="text-gray-600 text-sm mt-2">Points de défis</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations personnelles -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-6">Informations personnelles</h2>
                
                <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                                   pattern="[a-zA-ZÀ-ÿ\s\'-]+" 
                                   placeholder="Votre prénom (lettres uniquement)"
                                   @error('first_name') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                                   required title="Le prénom ne doit contenir que des lettres">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-red-600" data-client-error style="display:none">Le prénom ne doit pas contenir de chiffres.</p>
                            <p class="mt-1 text-xs text-gray-500">Lettres, tirets et apostrophes uniquement</p>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                                   pattern="[a-zA-ZÀ-ÿ\s\'-]+"
                                   placeholder="Votre nom (lettres uniquement)"
                                   @error('last_name') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                                   required title="Le nom ne doit contenir que des lettres">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-red-600" data-client-error style="display:none">Le nom ne doit pas contenir de chiffres.</p>
                            <p class="mt-1 text-xs text-gray-500">Lettres, tirets et apostrophes uniquement</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                               placeholder="exemple@email.com"
                               @error('email') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   pattern="[\d\s\+\-\(\)]+"
                                   placeholder="+33 6 12 34 56 78"
                                   @error('phone') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                                   title="Format: chiffres et caractères +, -, ( )">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Formats: +33 6 12 34 56 78 ou 06 12 34 56 78</p>
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                            <select id="gender" name="gender" @error('gender') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror>
                                <option value="">-- Sélectionner --</option>
                                <option value="male" @selected(old('gender', $user->gender) === 'male')>Homme</option>
                                <option value="female" @selected(old('gender', $user->gender) === 'female')>Femme</option>
                                <option value="other" @selected(old('gender', $user->gender) === 'other')>Autre</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                   max="{{ now()->subYears(10)->format('Y-m-d') }}"
                                   min="1920-01-01"
                                   @error('birth_date') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                                   title="L'âge minimum requis est 10 ans">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Doit être âgé d'au moins 10 ans (max: {{ now()->subYears(10)->format('d/m/Y') }})</p>
                        </div>
                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Contact d'urgence</label>
                            <input type="tel" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" 
                                   pattern="[\d\s\+\-\(\)]+"
                                   placeholder="+33 6 12 34 56 78"
                                   @error('emergency_contact') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                                   title="Format: chiffres et caractères +, -, ( )">
                            @error('emergency_contact')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Numéro de téléphone valide</p>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" 
                               placeholder="123 Rue de la République, 75000 Paris"
                               maxlength="500"
                               @error('address') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500"><span id="address-count">0</span>/500 caractères</p>
                    </div>

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Photo de profil</label>
                        <div class="flex items-center gap-4">
                            @if($user->avatar)
                                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-300">
                                    <img src="{{ Storage::url($user->avatar) }}" alt="Photo profil" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                    <i class="fas fa-user text-2xl text-gray-400"></i>
                                </div>
                            @endif
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif" 
                                   @error('avatar') class="flex-1 px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" @else class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" @enderror>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600" data-error>{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="mt-2 text-xs text-gray-500">✓ Formats: JPG, PNG, GIF | ✓ Max 10MB</p>
                    </div>

                    <button type="submit" class="mt-6 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Changer le mot de passe -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-6">Sécurité</h2>
                
                <form action="{{ route('member.profile.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" 
                               @error('current_password') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                               required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" 
                               @error('password') class="w-full px-4 py-2 border !border-red-500 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @else class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" @enderror
                               required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>

                    <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        <i class="fas fa-key mr-2"></i>Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations d'adhésion et progression récente -->
        <div>
            <!-- Adhésion -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Mon adhésion</h2>
                
                @if($user->membership)
                    <div class="space-y-3 mb-4">
                        <div>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-semibold">{{ $user->membership->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Statut</p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-medium @if($user->membership->status === 'active') bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                {{ $user->membership->status === 'active' ? 'Actif' : 'Expiré' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date d'expiration</p>
                            <p class="font-semibold">{{ $user->membership->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Prix mensuel</p>
                            <p class="font-semibold">{{ number_format($user->membership->price, 2) }} €</p>
                        </div>
                    </div>

                    <form action="{{ route('member.profile.update-membership') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <label class="flex items-center">
                            <input type="checkbox" name="auto_renewal" @checked($user->membership->auto_renewal) class="w-4 h-4 rounded border border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Renouvellement automatique</span>
                        </label>

                        <button type="submit" class="mt-3 w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                            Mettre à jour
                        </button>
                    </form>
                @else
                    <p class="text-gray-600">Aucune adhésion active</p>
                @endif
            </div>

            <!-- Progression récente -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Progression récente</h2>
                
                @if($recentProgress->count() > 0)
                    <div class="space-y-2">
                        @foreach($recentProgress as $log)
                            <div class="flex items-center justify-between p-2 border-b border-gray-100">
                                <div>
                                    <p class="font-medium text-sm">{{ $log->exercise->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($log->reps) {{ $log->reps }} reps @endif
                                        @if($log->weight) - {{ $log->weight }} kg @endif
                                    </p>
                                </div>
                                <span class="text-xs text-green-600 font-medium">✓</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-sm">Aucune progression enregistrée</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for address field
    const addressInput = document.getElementById('address');
    const addressCount = document.getElementById('address-count');
    
    if (addressInput && addressCount) {
        // Update on input
        addressInput.addEventListener('input', function() {
            addressCount.textContent = this.value.length;
            validateField(this);
        });
        
        // Initialize counter
        addressCount.textContent = addressInput.value.length;
    }
    
    // Form validation function
    function validateField(field) {
        const fieldContainer = field.closest('div');
        const errorDisplay = fieldContainer.querySelector('[data-error]');
        const clientError = fieldContainer.querySelector('[data-client-error]');

        // Name fields: disallow digits
        if (field.id === 'first_name' || field.id === 'last_name') {
            if (/\d/.test(field.value)) {
                field.classList.remove('border-green-500', 'bg-green-50');
                field.classList.add('border-red-500', 'bg-red-50');
                if (clientError) {
                    clientError.style.display = 'block';
                }
                if (errorDisplay) {
                    errorDisplay.style.display = 'none';
                }
                return;
            } else {
                if (clientError) {
                    clientError.style.display = 'none';
                }
            }
        }

        // Check if field is valid
        const isValid = field.checkValidity() && field.value.trim() !== '';

        if (isValid && field.value.trim() !== '') {
            // Valid field
            field.classList.remove('border-red-500', 'bg-red-50');
            field.classList.add('border-green-500', 'bg-green-50');
            if (errorDisplay) {
                errorDisplay.style.display = 'none';
            }
        } else if (field.value.trim() === '' && !field.required) {
            // Empty optional field - neutral
            field.classList.remove('border-red-500', 'border-green-500', 'bg-red-50', 'bg-green-50');
            field.classList.add('border-gray-300');
            if (errorDisplay) {
                errorDisplay.style.display = 'none';
            }
        } else if (field.value.trim() !== '' && !field.checkValidity()) {
            // Invalid field
            field.classList.remove('border-green-500', 'bg-green-50');
            field.classList.add('border-red-500', 'bg-red-50');
            if (errorDisplay) {
                errorDisplay.style.display = 'block';
            }
        }
    }
    
    // Add validation to all form inputs
    const formInputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], input[type="date"]');
    formInputs.forEach(input => {
        // Validate on input
        input.addEventListener('input', function() {
            validateField(this);
        });
        
        // Validate on blur
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Initial validation
        validateField(input);
    });
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const avatarImg = document.querySelector('.w-16.h-16.rounded-full img');
                    const avatarPlaceholder = document.querySelector('.w-16.h-16.rounded-full.bg-gray-200');
                    
                    if (!avatarImg && avatarPlaceholder) {
                        // Create image element if not exists
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.alt = 'Photo profil';
                        img.className = 'w-full h-full object-cover';
                        avatarPlaceholder.innerHTML = '';
                        avatarPlaceholder.appendChild(img);
                        avatarPlaceholder.classList.remove('bg-gray-200', 'flex', 'items-center', 'justify-center');
                    } else if (avatarImg) {
                        avatarImg.src = event.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection

