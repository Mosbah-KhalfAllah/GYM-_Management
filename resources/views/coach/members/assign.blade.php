@extends('layouts.app')

@section('title', 'Assigner un programme - Coach')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold">Assigner un programme</h1>
            <p class="text-gray-600 mt-1">{{ $member->first_name }} {{ $member->last_name }}</p>
        </div>
        <a href="{{ route('coach.members.show', $member->id) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Programmes Disponibles -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Programmes Disponibles</h2>

                @if($availablePrograms->count())
                    <div class="space-y-3">
                        @foreach($availablePrograms as $program)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg">{{ $program->title }}</h3>
                                        <p class="text-gray-600 text-sm mt-1">{{ $program->description }}</p>
                                        <div class="grid grid-cols-3 gap-4 mt-3 text-sm">
                                            <div>
                                                <p class="text-gray-600">Niveau</p>
                                                <p class="font-semibold">{{ ucfirst($program->level) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Durée</p>
                                                <p class="font-semibold">{{ $program->duration_days }} jours</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-600">Objectif</p>
                                                <p class="font-semibold">{{ ucfirst($program->goal) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('coach.members.assignProgram', $member->id) }}" method="POST" class="ml-4 h-fit">
                                        @csrf
                                        <input type="hidden" name="program_id" value="{{ $program->id }}">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                                            <i class="fas fa-plus mr-2"></i>Assigner
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-600">Aucun programme disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Programmes Actuels -->
        <div>
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-bold mb-4">Programmes Actuels</h2>
                @if($member->programs->count())
                    <div class="space-y-3">
                        @foreach($member->programs as $program)
                            <div class="border rounded-lg p-3 bg-gray-50">
                                <p class="font-semibold text-sm">{{ $program->title }}</p>
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold mt-2
                                    @if($program->pivot->status === 'active')
                                        bg-green-100 text-green-800
                                    @elseif($program->pivot->status === 'paused')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($program->pivot->status) }}
                                </span>
                                <p class="text-xs text-gray-600 mt-2">Progression: {{ $program->pivot->completion_percentage }}%</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600 text-sm">Aucun programme assigné</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

