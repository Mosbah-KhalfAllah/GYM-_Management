{{-- resources/views/admin/members/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Modifier le Membre')
@section('page-title', 'Modifier le Membre')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Modifier le membre</h2>
                <p class="text-gray-600">{{ $member->full_name }}</p>
            </div>
            <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
            </div>
        </div>
        
        <form action="{{ route('admin.members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations personnelles</h3>
                    
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                        <input type="text" id="first_name" name="first_name" required
                            pattern="[a-zA-ZÀ-ÿ\s'\-]+"
                            title="Le prénom ne doit contenir que des lettres"
                            placeholder="Votre prénom (lettres uniquement)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('first_name', $member->first_name) }}">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Lettres, tirets et apostrophes uniquement</p>
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" id="last_name" name="last_name" required
                            pattern="[a-zA-ZÀ-ÿ\s'\-]+"
                            title="Le nom ne doit contenir que des lettres"
                            placeholder="Votre nom (lettres uniquement)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('last_name', $member->last_name) }}">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Lettres, tirets et apostrophes uniquement</p>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('email', $member->email) }}">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input type="tel" id="phone" name="phone"
                            pattern="[\d\s\+\-\(\)]+"
                            maxlength="20"
                            title="Format: chiffres et caractères +, -, ( )"
                            placeholder="06 12 34 56 78"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('phone', $member->phone) }}">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Format: +33 6 12 34 56 78 ou 06 12 34 56 78</p>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations supplémentaires</h3>
                    
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
                        <input type="date" id="birth_date" name="birth_date"
                            max="{{ now()->subYears(10)->format('Y-m-d') }}"
                            min="1920-01-01"
                            title="L'\u00e2ge minimum requis est 10 ans"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('birth_date', $member->birth_date ? $member->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Âge minimum: 10 ans (max: {{ now()->subYears(10)->format('d/m/Y') }})</p>
                    </div>
                    
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                        <select id="gender" name="gender"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionnez</option>
                            <option value="male" {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>Homme</option>
                            <option value="female" {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>Femme</option>
                            <option value="other" {{ old('gender', $member->gender) == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-1">Contact d'urgence</label>
                        <input type="tel" id="emergency_contact" name="emergency_contact"
                            pattern="[\d\s\+\-\(\)]+"
                            maxlength="20"
                            title="Format: chiffres et caractères +, -, ( )"
                            placeholder="06 12 34 56 78"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('emergency_contact', $member->emergency_contact) }}">
                        @error('emergency_contact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Numéro de téléphone valide</p>
                    </div>
                    
                    <div>
                        <label for="is_active" class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                class="rounded border border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Compte actif</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Address -->
            <div class="mt-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <textarea id="address" name="address" rows="2"
                    maxlength="500"
                    placeholder="Adresse complète..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >{{ old('address', $member->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Max 500 caractères</p>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.members.show', $member) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    // Function to validate name fields (disallow numbers)
    function validateNameField(field) {
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
                errorMsg.className = 'birth-date-error text-red-500 text-sm mt-1';
                errorMsg.textContent = errorText;
                field.parentElement.appendChild(errorMsg);
            } else {
                errorMsg.textContent = errorText;
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
    
    // Prevent form submission if names contain digits or birth date is invalid
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
