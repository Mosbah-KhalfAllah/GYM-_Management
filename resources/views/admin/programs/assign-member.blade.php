@extends('layouts.app')

@section('title', 'Assigner un programme')
@section('page-title', 'Assigner un programme')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Assigner un programme à {{ $member->full_name }}</h2>
        </div>

        @if($availablePrograms->count() > 0)
            <form action="{{ route('admin.programs.assignMember', $member) }}" method="POST">
                @csrf
                
                <div class="space-y-4 mb-6">
                    <h3 class="text-lg font-medium text-gray-700">Programmes disponibles</h3>
                    
                    @foreach($availablePrograms as $program)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="radio" name="program_id" value="{{ $program->id }}" class="mt-1" required>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-medium text-gray-900">{{ $program->title }}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $program->level === 'beginner' ? 'bg-green-100 text-green-800' : 
                                               ($program->level === 'intermediate' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($program->level) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mt-1">{{ $program->description }}</p>
                                    <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                        <span><i class="fas fa-user-tie mr-1"></i>{{ $program->coach->full_name }}</span>
                                        <span><i class="fas fa-calendar mr-1"></i>{{ $program->duration_days }} jours</span>
                                        <span><i class="fas fa-target mr-1"></i>{{ ucfirst(str_replace('_', ' ', $program->goal)) }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.members.show', $member) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Assigner le programme
                    </button>
                </div>
            </form>
        @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun programme disponible</h3>
                <p class="text-gray-600 mb-4">Tous les programmes actifs sont déjà assignés à ce membre ou aucun programme n'est disponible.</p>
                <a href="{{ route('admin.members.show', $member) }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Retour
                </a>
            </div>
        @endif

        @if($member->programs->count() > 0)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-700 mb-4">Programmes déjà assignés</h3>
                <div class="space-y-3">
                    @foreach($member->programs as $assignedProgram)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $assignedProgram->title }}</h4>
                                <p class="text-sm text-gray-600">Coach: {{ $assignedProgram->coach->full_name }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    Assigné
                                </span>
                                <form action="{{ route('admin.programs.unassignMember', [$assignedProgram, $member]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors" onclick="return confirm('Désassigner ce programme ?')">
                                        Désassigner
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection