@extends('layouts.app')

@section('title', 'Exporter rapports')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Export des données</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 cursor-pointer hover:bg-blue-100 transition">
                <h3 class="font-semibold text-blue-900 mb-2">📊 Présences</h3>
                <p class="text-sm text-blue-700 mb-4">Exporter la liste des présences</p>
                <a href="{{ route('admin.reports.export', ['type' => 'attendance']) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">Télécharger</a>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 cursor-pointer hover:bg-green-100 transition">
                <h3 class="font-semibold text-green-900 mb-2">💳 Paiements</h3>
                <p class="text-sm text-green-700 mb-4">Exporter la liste des paiements</p>
                <a href="{{ route('admin.reports.export', ['type' => 'payments']) }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">Télécharger</a>
            </div>

            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 cursor-pointer hover:bg-purple-100 transition">
                <h3 class="font-semibold text-purple-900 mb-2">👥 Membres</h3>
                <p class="text-sm text-purple-700 mb-4">Exporter la liste des membres</p>
                <a href="{{ route('admin.reports.export', ['type' => 'members']) }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 text-sm">Télécharger</a>
            </div>
        </div>

        @if($data && count($data) > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold mb-4">Aperçu des données ({{ ucfirst($type) }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            @if($type === 'attendance')
                                <th class="px-4 py-2 text-left">Membre</th>
                                <th class="px-4 py-2 text-left">Entrée</th>
                                <th class="px-4 py-2 text-left">Sortie</th>
                            @elseif($type === 'payments')
                                <th class="px-4 py-2 text-left">Membre</th>
                                <th class="px-4 py-2 text-left">Montant</th>
                                <th class="px-4 py-2 text-left">Date</th>
                            @else
                                <th class="px-4 py-2 text-left">Nom</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Téléphone</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->take(5) as $row)
                        <tr class="border-b hover:bg-gray-50">
                            @if($type === 'attendance')
                                <td class="px-4 py-2">{{ $row->user->first_name }} {{ $row->user->last_name }}</td>
                                <td class="px-4 py-2">{{ $row->check_in ? $row->check_in->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-4 py-2">{{ $row->check_out ? $row->check_out->format('d/m/Y H:i') : '-' }}</td>
                            @elseif($type === 'payments')
                                <td class="px-4 py-2">{{ $row->user->first_name }} {{ $row->user->last_name }}</td>
                                <td class="px-4 py-2">{{ number_format($row->amount, 2) }} €</td>
                                <td class="px-4 py-2">{{ $row->created_at->format('d/m/Y') }}</td>
                            @else
                                <td class="px-4 py-2">{{ $row->first_name }} {{ $row->last_name }}</td>
                                <td class="px-4 py-2">{{ $row->email }}</td>
                                <td class="px-4 py-2">{{ $row->phone ?? '-' }}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-xs text-gray-500 mt-2">{{ count($data) }} enregistrement(s) au total</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

