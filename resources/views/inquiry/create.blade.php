@extends('layouts.app')

@section('title', 'Demande de Renseignements')
@section('page-title', 'Demande de Renseignements')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Formulaire de contact</h2>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        <form action="{{ route('inquiry.store') }}" method="POST" id="inquiryForm">
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
                </div>
                
                <!-- Contact Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Contact et demande</h3>
                    
                    <x-form-field
                        name="phone"
                        label="Téléphone"
                        type="tel"
                        placeholder="06 12 34 56 78"
                        :required="true"
                        pattern="[\d\s\+\-\(\)]+"
                        maxlength="20"
                        title="Format: chiffres et caractères +, -, ( )"
                        :error="$errors->first('phone')"
                        :value="old('phone')"
                        help="Format: +33 6 12 34 56 78 ou 06 12 34 56 78"
                    />
                    
                    <x-form-field
                        name="subject"
                        label="Sujet"
                        type="select"
                        :required="true"
                        :error="$errors->first('subject')"
                        :value="old('subject')"
                    >
                        <option value="">Choisir un sujet</option>
                        <option value="membership">Adhésion / Abonnement</option>
                        <option value="classes">Cours collectifs</option>
                        <option value="personal_training">Coaching personnel</option>
                        <option value="other">Autre</option>
                    </x-form-field>
                </div>
            </div>
            
            <!-- Message -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Votre message</h3>
                
                <x-form-field
                    name="message"
                    label="Message"
                    type="textarea"
                    placeholder="Décrivez votre demande..."
                    :required="true"
                    maxlength="500"
                    :error="$errors->first('message')"
                    :value="old('message')"
                    help="Maximum 500 caractères"
                />
            </div>
            
            <!-- Form Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('home') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('inquiryForm');
    const messageTextarea = form.querySelector('textarea[name="message"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    
    // Function to validate name fields (disallow numbers)
    function validateNameField(field) {
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
    
    // Character counter for message
    if (messageTextarea) {
        const counter = document.createElement('div');
        counter.className = 'text-sm text-gray-500 mt-1';
        messageTextarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = 500 - messageTextarea.value.length;
            counter.textContent = `${messageTextarea.value.length}/500 caractères`;
            counter.className = remaining < 50 ? 'text-sm text-red-500 mt-1' : 'text-sm text-gray-500 mt-1';
        }
        
        messageTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }
    
    // Phone validation
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length >= 10) {
                this.setCustomValidity('');
                this.classList.add('border-green-500');
                this.classList.remove('border-red-500');
            } else {
                this.setCustomValidity('Le numéro doit contenir au moins 10 chiffres');
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            }
        });
    }
    
    // Add validation to name fields
    const firstNameInput = form.querySelector('input[name="first_name"]');
    const lastNameInput = form.querySelector('input[name="last_name"]');
    
    [firstNameInput, lastNameInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                validateNameField(this);
            });
            input.addEventListener('blur', function() {
                validateNameField(this);
            });
        }
    });
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        if (firstNameInput && /\d/.test(firstNameInput.value)) {
            isValid = false;
            firstNameInput.classList.add('border-red-500');
        }
        
        if (lastNameInput && /\d/.test(lastNameInput.value)) {
            isValid = false;
            lastNameInput.classList.add('border-red-500');
        }
        
        if (phoneInput && phoneInput.value) {
            let phoneValue = phoneInput.value.replace(/[^0-9]/g, '');
            if (phoneValue.length < 10) {
                isValid = false;
                phoneInput.classList.add('border-red-500');
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire.');
        }
    });
});
</script>
@endsection