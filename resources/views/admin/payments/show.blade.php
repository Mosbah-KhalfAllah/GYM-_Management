@extends('layouts.app')

@section('title', 'Détails paiement')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Paiement #{{ $payment->id }}</h1>
            <div class="text-sm text-gray-500">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '—' }}</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Membre</p>
                <p class="font-medium">{{ $payment->member->first_name ?? '—' }} {{ $payment->member->last_name ?? '' }}</p>
                <p class="text-sm text-gray-500">{{ $payment->member->email ?? '' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Montant</p>
                <p class="font-medium">{{ number_format($payment->amount, 2) }} {{ $payment->currency ?? 'USD' }}</p>
                <p class="text-sm text-gray-500">Méthode: {{ ucfirst($payment->method) }}</p>
            </div>
        </div>

        @if($payment->note)
            <div class="mt-4">
                <p class="text-sm text-gray-600">Note</p>
                <p class="text-gray-700">{{ $payment->note }}</p>
            </div>
        @endif

        <div class="mt-6 flex gap-3">
            <a href="{{ route('admin.payments.edit', $payment) }}" class="px-4 py-2 bg-blue-600 text-white rounded">Modifier</a>
            <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border rounded">Retour</a>
        </div>
    </div>
</div>
@endsection
