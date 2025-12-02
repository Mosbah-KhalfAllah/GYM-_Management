@extends('layouts.app')

@section('title', 'Paiements')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Historique des paiements</h1>
    @include('components.generic-list', ['items' => $payments ?? null])
</div>
@endsection
