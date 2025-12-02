@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Rapports</h1>
    <p class="text-gray-700 mb-4">Résumé rapide :</p>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="text-sm text-gray-500">Membres</div>
            <div class="text-2xl font-bold">{{ $stats['total_members'] ?? '—' }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="text-sm text-gray-500">Présences aujourd'hui</div>
            <div class="text-2xl font-bold">{{ $stats['today_attendance'] ?? '—' }}</div>
        </div>
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="text-sm text-gray-500">Revenu mensuel</div>
            <div class="text-2xl font-bold">{{ $stats['monthly_revenue'] ?? '—' }}</div>
        </div>
    </div>

    @include('components.generic-list', ['items' => $attendanceData ?? null])
</div>
@endsection
