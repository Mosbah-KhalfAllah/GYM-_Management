@extends('layouts.app')

@section('title', 'Modifier paiement')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Modifier le paiement</h1>

        <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Membre</label>
                    <select name="member_id" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">
                        @foreach($members ?? [] as $member)
                            <option value="{{ $member->id }}" {{ old('member_id', $payment->member_id) == $member->id ? 'selected' : '' }}>{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Montant</label>
                        <input name="amount" type="number" step="0.01" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('amount', $payment->amount) }}">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Méthode</label>
                        <select name="method" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">
                            <option value="cash" {{ old('method', $payment->method) == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="card" {{ old('method', $payment->method) == 'card' ? 'selected' : '' }}>Carte</option>
                            <option value="online" {{ old('method', $payment->method) == 'online' ? 'selected' : '' }}>En ligne</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Date</label>
                    <input name="paid_at" type="datetime-local" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" value="{{ old('paid_at', $payment->paid_at ? $payment->paid_at->format('Y-m-d\\TH:i') : '') }}">
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Note</label>
                    <textarea name="note" rows="2" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">{{ old('note', $payment->note) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.payments.show', $payment) }}" class="px-4 py-2 border rounded">Annuler</a>
                <button class="px-4 py-2 bg-green-600 text-white rounded">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
