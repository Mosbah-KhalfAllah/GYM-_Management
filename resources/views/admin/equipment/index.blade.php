@extends('layouts.app')

@section('title', 'Équipements')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Liste des équipements</h1>
        <a href="{{ route('admin.equipment.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Ajouter équipement</a>
    </div>

    @if(isset($equipment) && $equipment->count())
        @include('components.generic-list', ['items' => $equipment])
    @else
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-600">Aucun équipement trouvé. Cliquez sur "Ajouter équipement" pour en créer un.</p>
        </div>
    @endif
</div>
@endsection
