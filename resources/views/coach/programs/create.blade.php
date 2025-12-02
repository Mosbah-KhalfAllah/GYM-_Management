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
                    <input name="title" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('title') }}">
                    @error('title')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm text-gray-700">Durée (semaines)</label>
                    <input name="duration_weeks" type="number" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('duration_weeks', 4) }}">
                    @error('duration_weeks')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm text-gray-700">Description</label>
                <textarea name="description" rows="4" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">{{ old('description') }}</textarea>
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
