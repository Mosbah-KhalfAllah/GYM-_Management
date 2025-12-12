@extends('layouts.app')

@section('title', 'Entraînement - Membre')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Entraînement d'aujourd'hui</h1>
        <p class="opacity-90">{{ now()->format('d F Y') }}</p>
    </div>

    <!-- Weekly Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium">Jours d'entraînement</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $weekStats['workout_days'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Cette semaine</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium">Minutes d'entraînement</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ round($weekStats['total_minutes']) }}</p>
            <p class="text-xs text-gray-500 mt-2">Cette semaine</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium">Exercices complétés</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $weekStats['completed_exercises'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Cette semaine</p>
        </div>
    </div>

    <!-- Today's Workout -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-dumbbell mr-3 text-red-600"></i>
                Séance d'aujourd'hui
            </h2>
            @if($activeProgram)
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    Jour {{ $activeProgram->pivot->current_day }} sur {{ $activeProgram->duration_days }}
                </span>
            @endif
        </div>

        @if($activeProgram && count($todaysExercises) > 0)
            <div class="space-y-4">
                @foreach($todaysExercises as $exercise)
                    <div class="border border-gray-200 rounded-lg p-5 hover:border-red-300 hover:shadow-lg transition-all bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-lg flex items-center justify-center text-white">
                                        <i class="fas fa-dumbbell"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $exercise->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $exercise->description }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium uppercase">Séries</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $exercise->sets }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium uppercase">Reps</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $exercise->reps }}</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium uppercase">Repos</p>
                                        <p class="text-2xl font-bold text-gray-800">{{ $exercise->rest_seconds }}s</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-xs text-gray-600 font-medium uppercase">Intensité</p>
                                        <p class="text-lg font-bold text-gray-800">
                                            @if($exercise->difficulty === 'easy')
                                                <span class="text-green-600">Facile</span>
                                            @elseif($exercise->difficulty === 'medium')
                                                <span class="text-yellow-600">Moyen</span>
                                            @else
                                                <span class="text-red-600">Difficile</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($exercise->video_url)
                                    <div class="mt-4">
                                        <a href="{{ $exercise->video_url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                            <i class="fas fa-play-circle mr-2"></i>
                                            Voir la vidéo
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-6">
                                <button type="button" 
                                        class="complete-exercise-btn w-16 h-16 rounded-full border-3 border-gray-300 flex items-center justify-center hover:border-green-500 hover:bg-green-50 transition-all"
                                        data-exercise-id="{{ $exercise->id }}"
                                        title="Marquer comme complété">
                                    <i class="fas fa-check text-2xl text-gray-400"></i>
                                </button>
                                <p class="text-xs text-gray-600 text-center mt-2">Complété</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Daily Progress -->
                <div class="mt-8 p-6 bg-gradient-to-r from-red-50 to-red-100 rounded-lg border border-red-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-lg font-semibold text-gray-800">Progression du jour</span>
                        <span class="text-2xl font-bold text-red-600" id="progress-text">0/{{ count($todaysExercises) }} exercices</span>
                    </div>
                    <div class="w-full bg-gray-300 rounded-full h-4">
                        <div id="progress-bar" class="bg-gradient-to-r from-red-500 to-red-600 h-4 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pas d'entraînement prévu</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas de programme actif ou pas d'exercices prévus pour aujourd'hui.</p>
                <a href="{{ route('member.program.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-colors font-medium">
                    <i class="fas fa-search mr-2"></i>
                    Voir les programmes disponibles
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($currentAttendance)
                <form action="{{ route('member.workout.check-out') }}" method="POST" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-center font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Terminer la séance
                    </button>
                </form>
            @else
                <form action="{{ route('member.workout.check-in') }}" method="POST" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-center font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Commencer la séance
                    </button>
                </form>
            @endif
            <a href="{{ route('member.progress.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                <i class="fas fa-chart-line mr-2"></i>
                Ma progression
            </a>
            <a href="{{ route('member.attendance.index') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-center font-medium">
                <i class="fas fa-history mr-2"></i>
                Historique
            </a>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.querySelectorAll('.complete-exercise-btn').forEach(button => {
        button.addEventListener('click', function() {
            const exerciseId = this.getAttribute('data-exercise-id');
            const btn = this;

            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i>';
            btn.disabled = true;

            // Simulate API call to mark as completed
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check text-2xl text-green-600"></i>';
                btn.classList.remove('border-gray-300', 'hover:border-green-500', 'hover:bg-green-50');
                btn.classList.add('border-green-500', 'bg-green-100');

                updateProgress();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Exercice complété!',
                    text: 'Excellent travail! Continue comme ça.',
                    timer: 2000,
                    showConfirmButton: false,
                    position: 'top-end'
                });
            }, 800);
        });
    });

    function updateProgress() {
        const completedButtons = document.querySelectorAll('.complete-exercise-btn i.fa-check.text-green-600');
        const totalButtons = document.querySelectorAll('.complete-exercise-btn');
        const completed = completedButtons.length;
        const total = totalButtons.length;
        const percentage = (completed / total) * 100;

        // Update progress bar
        const progressBar = document.getElementById('progress-bar');
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }

        // Update progress text
        const progressText = document.getElementById('progress-text');
        if (progressText) {
            progressText.textContent = `${completed}/${total} exercices`;
        }
    }
</script>
@endsection
@endsection

