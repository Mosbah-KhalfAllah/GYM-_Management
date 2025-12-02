@extends('layouts.app')

@section('title', 'Programmes - Membre')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Mes programmes</h1>
    @include('components.generic-list', ['items' => $programs ?? $memberPrograms ?? null])
</div>
@endsection
