@extends('layouts.app')

@section('title', 'Classes')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des classes</h1>
    @include('components.generic-list', ['items' => $classes ?? null])
</div>
@endsection
