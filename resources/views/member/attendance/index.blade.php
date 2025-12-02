@extends('layouts.app')

@section('title', 'Présences - Membre')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Mes présences</h1>
    @include('components.generic-list', ['items' => $attendances ?? null])
</div>
@endsection
