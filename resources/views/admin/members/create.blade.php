{{-- resources/views/admin/members/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Ajouter un Membre')
@section('page-title', 'Ajouter un Membre')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations du membre</h2>
        
        <form action="{{ route('admin.members.store') }}" method="POST" id="memberForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations personnelles</h3>
                    
                    <x-form-field
                        name="first_name"
                        label="Prénom"
                        type="text"
                        placeholder="Jean"
                        :required="true"
                        pattern="[a-zA-ZÀ-ÿÀ-ÿ\s'\-]+"
                        title="Le prénom ne doit contenir que des lettres"
                        :error="$errors->first('first_name')"
                        :value="old('first_name')"
                        help="Lettres, tirets et apostrophes uniquement"
                    />
                    
                    <x-form-field
                        name="last_name"
                        label="Nom"
                        type="text"
                        placeholder="Dupont"
                        :required="true"
                        pattern="[a-zA-ZÀ-ÿÀ-ÿ\s'\-]+"
                        title="Le nom ne doit contenir que des lettres"
                        :error="$errors->first('last_name')"
                        :value="old('last_name')"
                        help="Lettres, tirets et apostrophes uniquement"
                    />
                    
                    <x-form-field
                        name="email"
                        label="Email"
                        type="email"
                        placeholder="jean.dupont@exemple.com"
                        :required="true"
                        :error="$errors->first('email')"
                        :value="old('email')"
                    />
                    
                    <x-form-field
                        name="phone"
                        label="Téléphone"
                        type="tel"
                        placeholder="06 12 34 56 78"
                        pattern="[\d\s\+\-\(\)]+"
                        maxlength="20"
                        title="Format: chiffres et caractères +, -, ( )"
                        :error="$errors->first('phone')"
                        :value="old('phone')"
                        help="Format: +33 6 12 34 56 78 ou 06 12 34 56 78"
                    />
                    
                    <x-form-field
                        name="birth_date"
                        label="Date de naissance"
                        type="date"
                        max="{{ now()->subYears(10)->format('Y-m-d') }}"
                        min="1920-01-01"
                        title="L'age minimum requis est 10 ans"
                        :error="$errors->first('birth_date')"
                        :value="old('birth_date')"
                        help="Age minimum: 10 ans (max: {{ now()->subYears(10)->format('d/m/Y') }})"
                    />
                </div>
                
                <!-- Account Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Compte et sécurité</h3>
                    
                    <x-form-field
                        name="password"
                        label="Mot de passe"
                        type="password"
                        placeholder="Minimum 6 caractères"
                        :required="true"
                        :error="$errors->first('password')"
                    />
                    
                    <x-form-field
                        name="password_confirmation"
                        label="Confirmer le mot de passe"
                        type="password"
                        placeholder="Répétez le mot de passe"
                        :required="true"
                        :error="$errors->first('password_confirmation')"
                    />
                    
                    <x-form-field
                        name="gender"
                        label="Genre"
                        type="select"
                        :error="$errors->first('gender')"
                        :value="old('gender')"
                    >
                        <option value="male">Homme</option>
                        <option value="female">Femme</option>
                        <option value="other">Autre</option>
                    </x-form-field>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Informations supplémentaires</h3>
                
                <x-form-field
                    name="address"
                    label="Adresse"
                    type="textarea"
                    placeholder="Adresse complète..."
                    maxlength="500"
                    :error="$errors->first('address')"
                    :value="old('address')"
                    help="Max 500 caractères"
                />
                
                <x-form-field
                    name="emergency_contact"
                    label="Contact d'urgence"
                    type="tel"
                    placeholder="06 12 34 56 78"
                    pattern="[\d\s\+\-\(\)]+"
                    maxlength="20"
                    title="Format: chiffres et caractères +, -, ( )"
                    :error="$errors->first('emergency_contact')"
                    :value="old('emergency_contact')"
                    help="Numéro de téléphone valide"
                />
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer le membre
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('memberForm');
    
    // Function to validate name fields (disallow numbers)
    function validateNameField(field) {
        const container = field.closest('div');
        
        // Check if contains digits
        if (/\d/.test(field.value)) {
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300', 'border-green-500');
            return false;
        } else if (field.value.trim() !== '') {
            field.classList.add('border-green-500');
            field.classList.remove('border-gray-300', 'border-red-500');
            return true;
        } else {
            field.classList.add('border-gray-300');
            field.classList.remove('border-red-500', 'border-green-500');
            return true;
        }
    }
    
    // Function to validate birth date
    function validateBirthDate(field) {
        const birthDate = new Date(field.value);
        const tenYearsAgo = new Date();
        tenYearsAgo.setFullYear(tenYearsAgo.getFullYear() - 10);
        const minDate = new Date('1920-01-01');
        
        // Find or create error message element
        let errorMsg = field.parentElement.querySelector('.birth-date-error');
        
        if (!field.value) {
            field.classList.remove('border-red-500', 'border-green-500');
            field.classList.add('border-gray-300');
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
            return true;
        }
        
        let errorText = '';
        if (birthDate > tenYearsAgo) {
            errorText = 'L\'age minimum requis est 10 ans.';
        } else if (birthDate < minDate) {
            errorText = 'La date de naissance doit etre apres 1920.';
        }
        
        if (errorText) {
            field.classList.add('border-red-500');
            field.classList.remove('border-gray-300', 'border-green-500');
            
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'birth-date-error mt-2 text-sm text-red-600 flex items-center';
                errorMsg.innerHTML = '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd" /></svg> ' + errorText;
                field.parentElement.appendChild(errorMsg);
            } else {
                errorMsg.innerHTML = '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd" /></svg> ' + errorText;
            }
            errorMsg.style.display = 'block';
            return false;
        } else {
            field.classList.add('border-green-500');
            field.classList.remove('border-gray-300', 'border-red-500');
            
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
            return true;
        }
    }
    
    // Add validation to first_name and last_name
    const firstNameInput = form.querySelector('input[name="first_name"]');
    const lastNameInput = form.querySelector('input[name="last_name"]');
    const birthDateInput = form.querySelector('input[name="birth_date"]');
    
    [firstNameInput, lastNameInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                validateNameField(this);
            });
            input.addEventListener('blur', function() {
                validateNameField(this);
            });
            // Initial validation
            validateNameField(input);
        }
    });
    
    // Add validation to birth_date
    if (birthDateInput) {
        birthDateInput.addEventListener('change', function() {
            validateBirthDate(this);
        });
        birthDateInput.addEventListener('blur', function() {
            validateBirthDate(this);
        });
    }
    
    // Prevent form submission if names contain digits
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (firstNameInput && /\d/.test(firstNameInput.value)) {
            isValid = false;
            firstNameInput.classList.add('border-red-500');
            firstNameInput.classList.remove('border-green-500');
        }
        
        if (lastNameInput && /\d/.test(lastNameInput.value)) {
            isValid = false;
            lastNameInput.classList.add('border-red-500');
            lastNameInput.classList.remove('border-green-500');
        }
        
        if (birthDateInput && birthDateInput.value) {
            if (!validateBirthDate(birthDateInput)) {
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            if (/\d/.test(firstNameInput.value) || /\d/.test(lastNameInput.value)) {
                alert('Le prenom et le nom ne doivent pas contenir de chiffres.');
            } else if (birthDateInput && birthDateInput.value) {
                const birthDate = new Date(birthDateInput.value);
                const tenYearsAgo = new Date();
                tenYearsAgo.setFullYear(tenYearsAgo.getFullYear() - 10);
                const minDate = new Date('1920-01-01');
                
                if (birthDate > tenYearsAgo) {
                    alert('L\'age minimum requis est 10 ans.');
                } else if (birthDate < minDate) {
                    alert('La date de naissance doit etre apres 1920.');
                }
            }
        }
    });
});
</script>
@endsection
