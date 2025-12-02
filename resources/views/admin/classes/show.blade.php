@extends('layouts.app')

@section('title', 'Détails de la classe')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Détails de la classe</h1>
    @if(isset($class))
        <div class="bg-white p-4 rounded shadow-sm">
            @foreach($class->toArray() as $key => $value)
                <div class="mb-2">
                    <strong>{{ ucwords(str_replace(['_','-'], ' ', $key)) }}:</strong>
                    <span class="text-gray-700">{{ is_array($value) ? json_encode($value) : $value }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Aucune information disponible pour cette classe.</p>
    @endif
</div>
@endsection
