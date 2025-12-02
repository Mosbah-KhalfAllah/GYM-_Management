@extends('layouts.app')

@section('title', 'Classes - Membre')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des classes (Membre)</h1>
    @include('components.generic-list', ['items' => $classes ?? null])
</div>
@endsection
