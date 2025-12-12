@extends('layouts.app')

@section('title', 'Ajouter un Équipement')
@section('page-title', 'Ajouter un Équipement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Informations de l'équipement</h2>
        
        <form action="{{ route('admin.equipment.store') }}" method="POST" id="equipmentForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations générales -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Informations générales</h3>
                    
                    <x-form-field
                        name="name"
                        label="Nom de l'équipement"
                        type="text"
                        placeholder="Tapis de course, Haltères 10kg..."
                        :required="true"
                        maxlength="100"
                        :error="$errors->first('name')"
                        :value="old('name')"
                    />
                    
                    <x-form-field
                        name="brand"
                        label="Marque"
                        type="text"
                        placeholder="Technogym, Life Fitness..."
                        maxlength="50"
                        :error="$errors->first('brand')"
                        :value="old('brand')"
                    />
                    
                    <x-form-field
                        name="model"
                        label="Modèle"
                        type="text"
                        placeholder="Modèle de l'équipement"
                        maxlength="50"
                        :error="$errors->first('model')"
                        :value="old('model')"
                    />
                    
                    <x-form-field
                        name="serial_number"
                        label="Numéro de série"
                        type="text"
                        placeholder="SN123456789"
                        maxlength="50"
                        :error="$errors->first('serial_number')"
                        :value="old('serial_number')"
                    />
                </div>
                
                <!-- Statut et localisation -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Statut et localisation</h3>
                    
                    <x-form-field
                        name="status"
                        label="Statut"
                        type="select"
                        :required="true"
                        :error="$errors->first('status')"
                        :value="old('status')"
                    >
                        <option value="available">Disponible</option>
                        <option value="in_use">En cours d'utilisation</option>
                        <option value="maintenance">En maintenance</option>
                        <option value="out_of_order">Hors service</option>
                    </x-form-field>
                    
                    <x-form-field
                        name="location"
                        label="Emplacement"
                        type="text"
                        placeholder="Salle 1, Zone cardio..."
                        maxlength="100"
                        :error="$errors->first('location')"
                        :value="old('location')"
                    />
                    
                    <x-form-field
                        name="purchase_date"
                        label="Date d'achat"
                        type="date"
                        max="{{ now()->format('Y-m-d') }}"
                        :error="$errors->first('purchase_date')"
                        :value="old('purchase_date')"
                    />
                    
                    <x-form-field
                        name="purchase_price"
                        label="Prix d'achat (€)"
                        type="number"
                        placeholder="1500.00"
                        step="0.01"
                        min="0"
                        max="999999.99"
                        :error="$errors->first('purchase_price')"
                        :value="old('purchase_price')"
                    />
                </div>
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="mt-6 space-y-4">
                <h3 class="text-lg font-medium text-gray-700">Informations supplémentaires</h3>
                
                <x-form-field
                    name="description"
                    label="Description"
                    type="textarea"
                    placeholder="Description détaillée de l'équipement..."
                    maxlength="500"
                    :error="$errors->first('description')"
                    :value="old('description')"
                    help="Maximum 500 caractères"
                />
                
                <x-form-field
                    name="maintenance_notes"
                    label="Notes de maintenance"
                    type="textarea"
                    placeholder="Instructions spéciales, fréquence de maintenance..."
                    maxlength="300"
                    :error="$errors->first('maintenance_notes')"
                    :value="old('maintenance_notes')"
                    help="Maximum 300 caractères"
                />
            </div>
            
            <!-- Actions -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.equipment.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Ajouter l'équipement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('equipmentForm');
    const serialInput = form.querySelector('input[name="serial_number"]');
    
    // Validation du numéro de série (format alphanumérique)
    if (serialInput) {
        serialInput.addEventListener('input', function() {
            const value = this.value.toUpperCase();
            this.value = value;
            
            if (value && !/^[A-Z0-9]+$/.test(value)) {
                this.setCustomValidity('Le numéro de série doit contenir uniquement des lettres et des chiffres');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>
@endsection