@extends('layouts.app')

@section('title', 'Participants - Classe')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Participants</h1>
            <a href="{{ route('coach.classes.index') }}" class="text-sm text-gray-600">Retour aux classes</a>
        </div>

        @if(isset($attendees) && $attendees->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-2 text-left">Membre</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Inscription</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                        @foreach($attendees as $attendee)
                            <tr>
                                <td class="px-4 py-2">{{ $attendee->member?->first_name ? $attendee->member->first_name . ' ' . $attendee->member->last_name : '—' }}</td>
                                <td class="px-4 py-2">{{ $attendee->member?->email ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $attendee->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="px-4 py-2 text-center">
                                    @if($attendee->member)
                                        <a href="{{ route('coach.members.show', $attendee->member) }}" class="text-blue-600">Voir</a>
                                    @else
                                        <span class="text-gray-500">Voir</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Aucun participant pour cette classe.</p>
        @endif
    </div>
</div>
@endsection

