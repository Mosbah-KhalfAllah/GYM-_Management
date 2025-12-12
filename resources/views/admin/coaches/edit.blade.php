@extends('layouts.app')

@section('title', 'Modifier coach')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Modifier le coach</h1>
            <a href="{{ route('admin.coaches.index') }}" class="text-gray-600 hover:text-gray-900">✕</a>
        </div>

        <form action="{{ route('admin.coaches.update', $coach->id) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <x-form-field label="Prénom" name="first_name" type="text" placeholder="Ex: Jean" pattern="^[a-zA-ZÀ-ÿ\s'\-]+$" title="Le prénom ne doit contenir que des lettres" value="{{ $coach->first_name }}" required />
                <x-form-field label="Nom" name="last_name" type="text" placeholder="Ex: Dupont" pattern="^[a-zA-ZÀ-ÿ\s'\-]+$" title="Le nom ne doit contenir que des lettres" value="{{ $coach->last_name }}" required />
            </div>

            <x-form-field label="Email" name="email" type="email" placeholder="coach@gym.com" value="{{ $coach->email }}" required />
            <x-form-field label="Téléphone" name="phone" type="tel" pattern="^[\d\s\+\-\(\)]+$" placeholder="06 12 34 56 78" value="{{ $coach->phone }}" />

            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ $coach->is_active ? 'checked' : '' }} class="h-4 w-4 text-blue-600 rounded">
                <label for="is_active" class="text-sm text-gray-700">Coach actif</label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Mettre à jour</button>
                <a href="{{ route('admin.coaches.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection

