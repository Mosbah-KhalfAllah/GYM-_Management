@extends('layouts.app')

@section('title', 'Créer programme - Coach')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Créer un programme</h1>

        <form action="{{ route('coach.programs.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Titre</label>
                    <input name="title" required maxlength="150" minlength="3" @error('title') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror value="{{ old('title') }}" placeholder="Nom du programme">
                    @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Durée (semaines)</label>
                    <input name="duration_weeks" type="number" required min="1" max="52" @error('duration_weeks') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror value="{{ old('duration_weeks', 4) }}">
                    @error('duration_weeks')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm text-gray-700">Description</label>
                <textarea name="description" rows="4" maxlength="1000" @error('description') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror placeholder="Décrivez votre programme...">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('coach.programs.index') }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Créer</button>
            </div>
        </form>
    </div>
</div>
@endsection

