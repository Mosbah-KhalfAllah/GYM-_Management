@extends('layouts.app')

@section('title', 'Créer un Challenge')
@section('page-title', 'Créer un Challenge')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations du challenge</h2>
        
        <form action="{{ route('admin.challenges.store') }}" method="POST" id="challengeForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations générales</h3>
                    
                    <x-form-field
                        name="name"
                        label="Nom du challenge"
                        type="text"
                        placeholder="Challenge 30 jours, Perte de poids..."
                        :required="true"
                        maxlength="100"
                        :error="$errors->first('name')"
                        :value="old('name')"
                    />
                    
                    <x-form-field
                        name="description"
                        label="Description"
                        type="textarea"
                        placeholder="Objectifs, règles du challenge..."
                        :required="true"
                        maxlength="1000"
                        :error="$errors->first('description')"
                        :value="old('description')"
                        help="Maximum 1000 caractères"
                    />
                    
                    <x-form-field
                        name="type"
                        label="Type de challenge"
                        type="select"
                        :required="true"
                        :error="$errors->first('type')"
                        :value="old('type')"
                    >
                        <option value="">Sélectionner un type</option>
                        <option value="weight_loss">Perte de poids</option>
                        <option value="muscle_gain">Prise de muscle</option>
                        <option value="endurance">Endurance</option>
                        <option value="strength">Force</option>
                        <option value="attendance">Assiduité</option>
                        <option value="other">Autre</option>
                    </x-form-field>
                </div>
                
                <!-- Dates et récompenses -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Planning et récompenses</h3>
                    
                    <x-form-field
                        name="start_date"
                        label="Date de début"
                        type="date"
                        :required="true"
                        min="{{ now()->format('Y-m-d') }}"
                        :error="$errors->first('start_date')"
                        :value="old('start_date')"
                    />
                    
                    <x-form-field
                        name="end_date"
                        label="Date de fin"
                        type="date"
                        :required="true"
                        min="{{ now()->addDay()->format('Y-m-d') }}"
                        :error="$errors->first('end_date')"
                        :value="old('end_date')"
                    />
                    
                    <x-form-field
                        name="max_participants"
                        label="Nombre maximum de participants"
                        type="number"
                        placeholder="50"
                        min="1"
                        max="500"
                        :error="$errors->first('max_participants')"
                        :value="old('max_participants')"
                        help="Laisser vide pour illimité"
                    />
                    
                    <x-form-field
                        name="reward"
                        label="Récompense"
                        type="text"
                        placeholder="Bon d'achat, séance gratuite..."
                        maxlength="200"
                        :error="$errors->first('reward')"
                        :value="old('reward')"
                    />
                </div>
            </div>
            
            <!-- Critères et règles -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Critères et règles</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-field
                        name="target_value"
                        label="Objectif cible"
                        type="number"
                        placeholder="10"
                        step="0.1"
                        min="0"
                        :error="$errors->first('target_value')"
                        :value="old('target_value')"
                        help="Ex: 10 kg, 100 séances..."
                    />
                    
                    <x-form-field
                        name="target_unit"
                        label="Unité de mesure"
                        type="select"
                        :error="$errors->first('target_unit')"
                        :value="old('target_unit')"
                    >
                        <option value="">Sélectionner une unité</option>
                        <option value="kg">Kilogrammes (kg)</option>
                        <option value="sessions">Séances</option>
                        <option value="minutes">Minutes</option>
                        <option value="km">Kilomètres</option>
                        <option value="reps">Répétitions</option>
                        <option value="points">Points</option>
                    </x-form-field>
                </div>
                
                <x-form-field
                    name="rules"
                    label="Règles du challenge"
                    type="textarea"
                    placeholder="Conditions de participation, critères de validation..."
                    maxlength="500"
                    :error="$errors->first('rules')"
                    :value="old('rules')"
                    help="Maximum 500 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.challenges.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-trophy mr-2"></i>
                    Créer le challenge
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('challengeForm');
    const startDateInput = form.querySelector('input[name="start_date"]');
    const endDateInput = form.querySelector('input[name="end_date"]');
    
    // Validation des dates
    function validateDates() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (endDate <= startDate) {
                endDateInput.setCustomValidity('La date de fin doit être après la date de début');
            } else {
                endDateInput.setCustomValidity('');
            }
        }
    }
    
    startDateInput.addEventListener('change', function() {
        // Mettre à jour la date minimum de fin
        const minEndDate = new Date(this.value);
        minEndDate.setDate(minEndDate.getDate() + 1);
        endDateInput.min = minEndDate.toISOString().split('T')[0];
        validateDates();
    });
    
    endDateInput.addEventListener('change', validateDates);
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        validateDates();
        
        if (!form.checkValidity()) {
            e.preventDefault();
            alert('Veuillez corriger les erreurs dans le formulaire.');
        }
    });
});
</script>
@endsection