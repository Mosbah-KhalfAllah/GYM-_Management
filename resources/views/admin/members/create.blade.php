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
                        :error="$errors->first('first_name')"
                        :value="old('first_name')"
                    />
                    
                    <x-form-field
                        name="last_name"
                        label="Nom"
                        type="text"
                        placeholder="Dupont"
                        :required="true"
                        :error="$errors->first('last_name')"
                        :value="old('last_name')"
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
                        :error="$errors->first('phone')"
                        :value="old('phone')"
                    />
                    
                    <x-form-field
                        name="birth_date"
                        label="Date de naissance"
                        type="date"
                        :error="$errors->first('birth_date')"
                        :value="old('birth_date')"
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
                    :error="$errors->first('address')"
                    :value="old('address')"
                />
                
                <x-form-field
                    name="emergency_contact"
                    label="Contact d'urgence"
                    type="text"
                    placeholder="Nom et téléphone"
                    :error="$errors->first('emergency_contact')"
                    :value="old('emergency_contact')"
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
@endsection