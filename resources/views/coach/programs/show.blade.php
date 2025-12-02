@extends('layouts.app')

@section('title', 'Détails programme - Coach')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">{{ $program->title ?? 'Programme' }}</h1>
                <p class="text-sm text-gray-500">Durée : {{ $program->duration_weeks ?? '—' }} semaines</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('coach.programs.edit', $program) }}" class="px-3 py-2 bg-blue-600 text-white rounded">Modifier</a>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Description</h3>
            <p class="text-gray-700">{{ $program->description ?? 'Aucune description.' }}</p>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Membres inscrits</h3>
            @include('components.generic-list', ['items' => $members ?? $program->members ?? collect()])
        </div>
    </div>
</div>
@endsection
