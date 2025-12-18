@extends('layouts.app')

@section('title', 'Modifier la classe')
@section('page-title', 'Modifier la classe')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Modifier "{{ $class->name }}"</h2>
        
        <form action="{{ route('admin.classes.update', $class) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nom du cours *</label>
                        <input type="text" name="name" value="{{ old('name', $class->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $class->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Coach *</label>
                        <select name="coach_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @foreach($coaches as $coach)
                                <option value="{{ $coach->id }}" {{ old('coach_id', $class->coach_id) == $coach->id ? 'selected' : '' }}>
                                    {{ $coach->full_name }} - {{ $coach->phone ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('coach_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Capacité *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $class->capacity) }}" min="1" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('capacity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date et heure *</label>
                        <input type="datetime-local" name="schedule_time" value="{{ old('schedule_time', $class->schedule_time->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('schedule_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durée (minutes) *</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $class->duration_minutes) }}" min="15" max="180" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('duration_minutes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prix (TND)</label>
                        <input type="number" name="price" value="{{ old('price', $class->price) }}" step="0.01" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lieu/Salle</label>
                        <input type="text" name="location" value="{{ old('location', $class->location) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="scheduled" {{ old('status', $class->status) == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                            <option value="completed" {{ old('status', $class->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ old('status', $class->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.classes.show', $class) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
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

