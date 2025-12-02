@extends('layouts.app')

@section('title', 'Détails exercice - Coach')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $exercise->name ?? 'Exercice' }}</h1>
                <p class="text-sm text-gray-500">Programme : {{ $exercise->program->title ?? '—' }}</p>
            </div>
            <a href="{{ route('coach.exercises.edit', $exercise) }}" class="px-3 py-2 bg-blue-600 text-white rounded">Modifier</a>
        </div>

        <div class="mt-4">
            <h3 class="text-lg font-semibold">Description</h3>
            <p class="text-gray-700">{{ $exercise->description ?? 'Aucune description.' }}</p>
        </div>
    </div>
</div>
@endsection
