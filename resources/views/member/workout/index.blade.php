@extends('layouts.app')

@section('title', 'Entraîement - Membre')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Entraîements</h1>
    @include('components.generic-list', ['items' => $workouts ?? $exercises ?? null])
</div>
@endsection
