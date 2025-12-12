@extends('layouts.app')

@section('title', 'Modifier exercice - Coach')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier l'exercice</h1>

        <form action="{{ route('coach.exercises.update', $exercise) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700">Nom</label>
                    <input name="name" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" value="{{ old('name', $exercise->name) }}">
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Programme associé</label>
                    <select name="program_id" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">Aucun</option>
                        @foreach($programs ?? [] as $program)
                            <option value="{{ $program->id }}" {{ old('program_id', $exercise->program_id) == $program->id ? 'selected' : '' }}>{{ $program->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">{{ old('description', $exercise->description) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('coach.exercises.show', $exercise) }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection

