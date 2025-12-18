@extends('layouts.app')

@section('title', 'Créer un Cours Collectif')
@section('page-title', 'Créer un Cours Collectif')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations du cours</h2>
        
        <form action="{{ route('admin.classes.store') }}" method="POST" id="classForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations générales</h3>
                    
                    <x-form-field
                        name="name"
                        label="Nom du cours"
                        type="text"
                        placeholder="Yoga, Pilates, CrossFit..."
                        :required="true"
                        :error="$errors->first('name')"
                        :value="old('name')"
                    />
                    
                    <x-form-field
                        name="description"
                        label="Description"
                        type="textarea"
                        placeholder="Description du cours..."
                        maxlength="500"
                        :error="$errors->first('description')"
                        :value="old('description')"
                        help="Maximum 500 caractères"
                    />
                    
                    <x-form-field
                        name="coach_id"
                        label="Coach"
                        type="select"
                        :required="true"
                        :error="$errors->first('coach_id')"
                        :value="old('coach_id')"
                    >
                        <option value="">Sélectionner un coach</option>
                        @foreach($coaches ?? [] as $coach)
                            <option value="{{ $coach->id }}">{{ $coach->full_name }} - {{ $coach->phone ?? 'N/A' }}</option>
                        @endforeach
                    </x-form-field>
                    
                    <x-form-field
                        name="max_participants"
                        label="Nombre maximum de participants"
                        type="number"
                        placeholder="20"
                        :required="true"
                        min="1"
                        max="100"
                        :error="$errors->first('max_participants')"
                        :value="old('max_participants')"
                        help="Entre 1 et 100 participants"
                    />
                </div>
                
                <!-- Horaires et planning -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Horaires et planning</h3>
                    
                    <x-form-field
                        name="start_date"
                        label="Date de début"
                        type="date"
                        :required="true"
                        min="{{ now()->format('Y-m-d') }}"
                        :error="$errors->first('start_date')"
                        :value="old('start_date')"
                        help="Date du premier cours"
                    />
                    
                    <x-form-field
                        name="start_time"
                        label="Heure de début"
                        type="time"
                        :required="true"
                        :error="$errors->first('start_time')"
                        :value="old('start_time')"
                    />
                    
                    <x-form-field
                        name="end_time"
                        label="Heure de fin"
                        type="time"
                        :required="true"
                        :error="$errors->first('end_time')"
                        :value="old('end_time')"
                    />
                    
                    <x-form-field
                        name="duration_minutes"
                        label="Durée (minutes)"
                        type="number"
                        placeholder="60"
                        :required="true"
                        min="15"
                        max="180"
                        :error="$errors->first('duration_minutes')"
                        :value="old('duration_minutes')"
                        help="Entre 15 et 180 minutes"
                    />
                </div>
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Informations supplémentaires</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-form-field
                        name="price"
                        label="Prix (€)"
                        type="number"
                        placeholder="15.00"
                        step="0.01"
                        min="0"
                        max="999.99"
                        :error="$errors->first('price')"
                        :value="old('price')"
                        help="Prix par séance"
                    />
                    
                    <x-form-field
                        name="location"
                        label="Lieu/Salle"
                        type="text"
                        placeholder="Salle A, Studio 1..."
                        :error="$errors->first('location')"
                        :value="old('location')"
                    />
                </div>
                
                <x-form-field
                    name="requirements"
                    label="Prérequis"
                    type="textarea"
                    placeholder="Niveau requis, équipement nécessaire..."
                    maxlength="300"
                    :error="$errors->first('requirements')"
                    :value="old('requirements')"
                    help="Maximum 300 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.classes.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Créer le cours
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('classForm');
    const startTimeInput = form.querySelector('input[name="start_time"]');
    const endTimeInput = form.querySelector('input[name="end_time"]');
    const durationInput = form.querySelector('input[name="duration_minutes"]');
    
    // Calculer automatiquement la durée
    function calculateDuration() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date(`2000-01-01T${startTimeInput.value}`);
            const end = new Date(`2000-01-01T${endTimeInput.value}`);
            
            if (end > start) {
                const diffMs = end - start;
                const diffMins = Math.floor(diffMs / 60000);
                durationInput.value = diffMins;
            }
        }
    }
    
    // Calculer l'heure de fin automatiquement
    function calculateEndTime() {
        if (startTimeInput.value && durationInput.value) {
            const start = new Date(`2000-01-01T${startTimeInput.value}`);
            const duration = parseInt(durationInput.value);
            
            if (duration > 0) {
                start.setMinutes(start.getMinutes() + duration);
                endTimeInput.value = start.toTimeString().slice(0, 5);
            }
        }
    }
    
    startTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
    durationInput.addEventListener('change', calculateEndTime);
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;
        
        if (startTime && endTime && startTime >= endTime) {
            e.preventDefault();
            alert('L\'heure de fin doit être après l\'heure de début.');
            return false;
        }
    });
});
</script>
@endsection