@extends('layouts.app')

@section('title', 'Détail de la Demande')
@section('page-title', 'Détail de la Demande')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Demande #{{ $inquiry->id }}</h2>
            <a href="{{ route('admin.inquiries.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations Contact -->
            <div class="card">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Informations de contact</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nom complet</label>
                        <p class="text-gray-900">{{ $inquiry->full_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">
                            <a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:underline">
                                {{ $inquiry->email }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Téléphone</label>
                        <p class="text-gray-900">
                            <a href="tel:{{ $inquiry->phone }}" class="text-blue-600 hover:underline">
                                {{ $inquiry->phone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informations Demande -->
            <div class="card">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Détails de la demande</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sujet</label>
                        <p class="text-gray-900">
                            <span class="badge badge-info">{{ $inquiry->subject_label }}</span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Statut</label>
                        <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="form-select w-auto">
                                <option value="pending" {{ $inquiry->status === 'pending' ? 'selected' : '' }}>🟡 En attente</option>
                                <option value="contacted" {{ $inquiry->status === 'contacted' ? 'selected' : '' }}>🔵 Contactée</option>
                                <option value="resolved" {{ $inquiry->status === 'resolved' ? 'selected' : '' }}>🟢 Résolue</option>
                            </select>
                        </form>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date de création</label>
                        <p class="text-gray-900">{{ $inquiry->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message -->
        <div class="mt-6">
            <div class="card">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Message</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $inquiry->message }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end gap-4">
            <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer cette demande ?')">
                    <i class="fas fa-trash mr-2"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection