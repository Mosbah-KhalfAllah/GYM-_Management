@extends('layouts.app')

@section('title', 'Créer un Programme')
@section('page-title', 'Créer un Programme')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations du programme</h2>
        
        <form action="{{ route('admin.programs.store') }}" method="POST" id="programForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations générales</h3>
                    
                    <x-form-field
                        name="name"
                        label="Nom du programme"
                        type="text"
                        placeholder="Prise de masse, Cardio intensif..."
                        :required="true"
                        maxlength="100"
                        :error="$errors->first('name')"
                        :value="old('name')"
                    />
                    
                    <x-form-field
                        name="description"
                        label="Description"
                        type="textarea"
                        placeholder="Objectifs, contenu du programme..."
                        :required="true"
                        maxlength="1000"
                        :error="$errors->first('description')"
                        :value="old('description')"
                        help="Maximum 1000 caractères"
                    />
                    
                    <x-form-field
                        name="coach_id"
                        label="Coach responsable"
                        type="select"
                        :required="true"
                        :error="$errors->first('coach_id')"
                        :value="old('coach_id')"
                    >
                        <option value="">Sélectionner un coach</option>
                        @foreach($coaches ?? [] as $coach)
                            <option value="{{ $coach->id }}">{{ $coach->full_name }}</option>
                        @endforeach
                    </x-form-field>
                </div>
                
                <!-- Paramètres du programme -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Paramètres</h3>
                    
                    <x-form-field
                        name="difficulty_level"
                        label="Niveau de difficulté"
                        type="select"
                        :required="true"
                        :error="$errors->first('difficulty_level')"
                        :value="old('difficulty_level')"
                    >
                        <option value="">Sélectionner un niveau</option>
                        <option value="beginner">Débutant</option>
                        <option value="intermediate">Intermédiaire</option>
                        <option value="advanced">Avancé</option>
                    </x-form-field>
                    
                    <x-form-field
                        name="duration_weeks"
                        label="Durée (semaines)"
                        type="number"
                        placeholder="8"
                        :required="true"
                        min="1"
                        max="52"
                        :error="$errors->first('duration_weeks')"
                        :value="old('duration_weeks')"
                        help="Entre 1 et 52 semaines"
                    />
                    
                    <x-form-field
                        name="sessions_per_week"
                        label="Séances par semaine"
                        type="number"
                        placeholder="3"
                        :required="true"
                        min="1"
                        max="7"
                        :error="$errors->first('sessions_per_week')"
                        :value="old('sessions_per_week')"
                        help="Entre 1 et 7 séances"
                    />
                    
                    <x-form-field
                        name="session_duration"
                        label="Durée par séance (minutes)"
                        type="number"
                        placeholder="60"
                        min="15"
                        max="180"
                        :error="$errors->first('session_duration')"
                        :value="old('session_duration')"
                        help="Entre 15 et 180 minutes"
                    />
                </div>
            </div>
            
            <!-- Objectifs et prérequis -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Objectifs et prérequis</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-field
                        name="goal"
                        label="Objectif principal"
                        type="select"
                        :required="true"
                        :error="$errors->first('goal')"
                        :value="old('goal')"
                    >
                        <option value="">Sélectionner un objectif</option>
                        <option value="weight_loss">Perte de poids</option>
                        <option value="muscle_gain">Prise de muscle</option>
                        <option value="strength">Gain de force</option>
                        <option value="endurance">Amélioration endurance</option>
                        <option value="flexibility">Flexibilité</option>
                        <option value="general_fitness">Forme générale</option>
                    </x-form-field>
                    
                    <x-form-field
                        name="max_participants"
                        label="Nombre max de participants"
                        type="number"
                        placeholder="20"
                        min="1"
                        max="100"
                        :error="$errors->first('max_participants')"
                        :value="old('max_participants')"
                        help="Laisser vide pour illimité"
                    />
                </div>
                
                <x-form-field
                    name="prerequisites"
                    label="Prérequis"
                    type="textarea"
                    placeholder="Niveau requis, équipement nécessaire..."
                    maxlength="500"
                    :error="$errors->first('prerequisites')"
                    :value="old('prerequisites')"
                    help="Maximum 500 caractères"
                />
                
                <x-form-field
                    name="equipment_needed"
                    label="Équipement nécessaire"
                    type="textarea"
                    placeholder="Haltères, tapis, élastiques..."
                    maxlength="300"
                    :error="$errors->first('equipment_needed')"
                    :value="old('equipment_needed')"
                    help="Maximum 300 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.programs.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-running mr-2"></i>
                    Créer le programme
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('programForm');
    const durationInput = form.querySelector('input[name="duration_weeks"]');
    const sessionsInput = form.querySelector('input[name="sessions_per_week"]');
    const sessionDurationInput = form.querySelector('input[name="session_duration"]');
    
    // Calculer le total d'heures du programme
    function calculateTotalHours() {
        const weeks = parseInt(durationInput.value) || 0;
        const sessionsPerWeek = parseInt(sessionsInput.value) || 0;
        const sessionDuration = parseInt(sessionDurationInput.value) || 0;
        
        if (weeks && sessionsPerWeek && sessionDuration) {
            const totalMinutes = weeks * sessionsPerWeek * sessionDuration;
            const totalHours = Math.round(totalMinutes / 60 * 10) / 10;
            
            // Afficher le total (créer un élément si nécessaire)
            let totalDisplay = document.getElementById('total-hours');
            if (!totalDisplay) {
                totalDisplay = document.createElement('div');
                totalDisplay.id = 'total-hours';
                totalDisplay.className = 'text-sm text-blue-600 font-medium mt-2';
                sessionDurationInput.parentNode.appendChild(totalDisplay);
            }
            totalDisplay.textContent = `Total: ${totalHours}h sur ${weeks} semaines`;
        }
    }
    
    [durationInput, sessionsInput, sessionDurationInput].forEach(input => {
        if (input) {
            input.addEventListener('input', calculateTotalHours);
        }
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const duration = parseInt(durationInput.value);
        const sessions = parseInt(sessionsInput.value);
        
        if (duration && sessions && duration * sessions > 365) {
            e.preventDefault();
            alert('Le programme semble trop intensif (plus de 365 séances). Veuillez ajuster la durée ou la fréquence.');
        }
    });
});
</script>
@endsection