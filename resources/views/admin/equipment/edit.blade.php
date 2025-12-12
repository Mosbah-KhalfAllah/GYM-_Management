@extends('layouts.app')

@section('title', 'Modifier équipement')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier l'équipement</h1>

        <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Nom</label>
                    <input name="name" required maxlength="100" @error('name') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror value="{{ old('name', $equipment->name) }}" placeholder="Ex: Haltère 10kg">
                    @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Emplacement</label>
                    <input name="location" maxlength="100" @error('location') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror value="{{ old('location', $equipment->location) }}" placeholder="Ex: Salle 1">
                    @error('location')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Statut</label>
                    <select name="status" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="available" {{ old('status', $equipment->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                        <option value="maintenance" {{ old('status', $equipment->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="broken" {{ old('status', $equipment->status) == 'broken' ? 'selected' : '' }}>Cassé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Notes</label>
                    <textarea name="notes" rows="3" maxlength="500" @error('notes') class="mt-1 block w-full rounded !border !border-red-500 px-3 py-2" @else class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" @enderror placeholder="Remarques supplémentaires...">{{ old('notes', $equipment->notes) }}</textarea>
                    @error('notes')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.equipment.index') }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection

