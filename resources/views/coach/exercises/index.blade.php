@extends('layouts.app')

@section('title', 'Exercices - Coach')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Exercices (Coach)</h1>
    @include('components.generic-list', ['items' => $exercises ?? null])
</div>
@endsection
