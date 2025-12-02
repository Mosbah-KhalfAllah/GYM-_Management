@extends('layouts.app')

@section('title', 'Progression - Membre')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Ma progression</h1>
    @include('components.generic-list', ['items' => $recentProgress ?? $progresses ?? null])
</div>
@endsection
