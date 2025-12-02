@extends('layouts.app')

@section('title', 'Challenges')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des challenges</h1>
    @include('components.generic-list', ['items' => $challenges ?? null])
</div>
@endsection
