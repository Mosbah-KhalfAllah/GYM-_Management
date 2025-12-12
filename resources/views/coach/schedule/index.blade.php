@extends('layouts.app')

@section('title', 'Planning - Coach')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Planning (Coach)</h1>
    @include('components.generic-list', ['items' => $schedule ?? null])
</div>
@endsection

