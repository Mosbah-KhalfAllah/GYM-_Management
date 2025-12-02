@extends('layouts.app')

@section('title', 'Programmes')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des programmes</h1>
    @include('components.generic-list', ['items' => $programs ?? null])
</div>
@endsection
