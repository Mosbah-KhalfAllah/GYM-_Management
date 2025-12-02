@extends('layouts.app')

@section('title', 'Présences')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Suivi des présences</h1>
    @include('components.generic-list', ['items' => $attendances ?? null])
</div>
@endsection
