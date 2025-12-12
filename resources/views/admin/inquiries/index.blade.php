@extends('layouts.app')

@section('title', 'Demandes de Renseignements')
@section('page-title', 'Demandes de Renseignements')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Liste des demandes</h2>
            <div class="flex gap-2">
                <span class="badge badge-warning">{{ $inquiries->where('status', 'pending')->count() }} En attente</span>
                <span class="badge badge-info">{{ $inquiries->where('status', 'contacted')->count() }} Contactées</span>
                <span class="badge badge-success">{{ $inquiries->where('status', 'resolved')->count() }} Résolues</span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sujet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inquiries as $inquiry)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="font-medium text-gray-900">{{ $inquiry->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $inquiry->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $inquiry->phone }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="badge badge-info">{{ $inquiry->subject_label }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    {{ Str::limit($inquiry->message, 100) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-sm border-0 bg-transparent">
                                        <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>🟡 En attente</option>
                                        <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>🔵 Contactée</option>
                                        <option value="resolved" {{ $inquiry->status === 'resolved' ? 'selected' : '' }}>🟢 Résolue</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $inquiry->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Supprimer cette demande ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune demande de renseignements
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $inquiries->links() }}
        </div>
    </div>
</div>
@endsection