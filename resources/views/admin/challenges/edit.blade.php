@extends('layouts.app')

@section('title', 'Modifier le challenge')
@section('page-title', 'Modifier le challenge')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Modifier "{{ $challenge->title }}"</h2>
        
        <form action="{{ route('admin.challenges.update', $challenge) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre *</label>
                        <input type="text" name="title" value="{{ old('title', $challenge->title) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $challenge->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="attendance" {{ old('type', $challenge->type) == 'attendance' ? 'selected' : '' }}>Présence</option>
                            <option value="workout" {{ old('type', $challenge->type) == 'workout' ? 'selected' : '' }}>Entraînement</option>
                            <option value="weight_loss" {{ old('type', $challenge->type) == 'weight_loss' ? 'selected' : '' }}>Perte de poids</option>
                        </select>
                        @error('type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Objectif *</label>
                        <input type="number" name="target_value" value="{{ old('target_value', $challenge->target_value) }}" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('target_value')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de début *</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $challenge->start_date->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('start_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin *</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $challenge->end_date->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('end_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Points de récompense</label>
                        <input type="number" name="points_reward" value="{{ old('points_reward', $challenge->points_reward) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('points_reward')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $challenge->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Challenge actif</span>
                        </label>
                        @error('is_active')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.challenges.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

