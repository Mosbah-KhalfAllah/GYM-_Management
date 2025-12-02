@extends('layouts.app')

@section('title', 'Ajouter équipement')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Ajouter un équipement</h1>

        <form action="{{ route('admin.equipment.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Nom</label>
                    <input name="name" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('name') }}">
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Emplacement</label>
                    <input name="location" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('location') }}">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Statut</label>
                    <select name="status" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">
                        <option value="available">Disponible</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="broken">Cassé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.equipment.index') }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Ajouter</button>
            </div>
        </form>
    </div>
</div>
@endsection
