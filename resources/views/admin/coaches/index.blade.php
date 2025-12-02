@extends('layouts.app')

@section('title', 'Coachs')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Liste des coachs</h1>
    @include('components.generic-list', ['items' => $coaches ?? null])
</div>
@endsection
