@extends('layouts.app')

@section('title', 'Créer un paiement')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Enregistrer un paiement</h1>

        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Membre</label>
                    <select name="member_id" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">
                        <option value="">Sélectionnez un membre</option>
                        @foreach($members ?? [] as $member)
                            <option value="{{ $member->id }}">{{ $member->first_name }} {{ $member->last_name }} ({{ $member->email }})</option>
                        @endforeach
                    </select>
                    @error('member_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Montant</label>
                        <input name="amount" type="number" step="0.01" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('amount') }}">
                        @error('amount')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Méthode</label>
                        <select name="method" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">
                            <option value="cash">Espèces</option>
                            <option value="card">Carte</option>
                            <option value="online">En ligne</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Date</label>
                    <input name="paid_at" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('paid_at') }}">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Note (optionnel)</label>
                    <textarea name="note" rows="2" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
