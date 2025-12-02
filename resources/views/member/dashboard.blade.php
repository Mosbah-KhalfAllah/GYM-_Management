{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de bord Membre')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Bonjour, {{ Auth::user()->first_name }} !</h1>
                <p class="opacity-90">Prêt pour votre séance d'entraînement aujourd'hui ?</p>
                <div class="mt-4 flex items-center space-x-4">
                    @if($activeProgram)
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20">
                            <i class="fas fa-running mr-2"></i>
                            {{ $activeProgram->title }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/20">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ now()->format('d F Y') }}
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-dumbbell text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Weekly Attendance -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Présences cette semaine</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['attendance_this_week'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-calendar-week mr-1"></i> Sur 7 jours
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-qrcode text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Completed Exercises -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Exercices complétés</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['completed_exercises'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-check-circle mr-1"></i> Total
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-dumbbell text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cours à venir</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['upcoming_classes'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Réservés
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Challenge Points -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Points de challenge</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['challenge_points'] }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-trophy mr-1"></i> Cumulés
                    </p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-medal text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Workout -->
        <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Séance d'aujourd'hui</h3>
                @if($activeProgram)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        Jour {{ $activeProgram->pivot->current_day }} sur {{ $activeProgram->duration_days }}
                    </span>
                @endif
            </div>
            
            @if($activeProgram && count($todaysExercises) > 0)
                <div class="space-y-4">
                    @foreach($todaysExercises as $exercise)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-running"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $exercise->name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $exercise->description }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 grid grid-cols-3 gap-4">
                                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-600">Séries</p>
                                            <p class="text-lg font-semibold text-gray-800">{{ $exercise->sets }}</p>
                                        </div>
                                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-600">Répétitions</p>
                                            <p class="text-lg font-semibold text-gray-800">{{ $exercise->reps }}</p>
                                        </div>
                                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                                            <p class="text-sm text-gray-600">Repos</p>
                                            <p class="text-lg font-semibold text-gray-800">{{ $exercise->rest_seconds }}s</p>
                                        </div>
                                    </div>
                                    @if($exercise->video_url)
                                        <div class="mt-4">
                                            <a href="{{ $exercise->video_url }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-play-circle mr-2"></i>
                                                Voir la démonstration
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <button class="complete-exercise-btn w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-green-500 hover:bg-green-50 transition-colors"
                                            data-exercise-id="{{ $exercise->id }}">
                                        <i class="fas fa-check text-gray-400"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Progress -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Progression du jour</span>
                            <span class="text-sm font-medium text-blue-600">0/{{ count($todaysExercises) }} exercices</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-running text-3xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-800 mb-2">Aucun programme actif</h4>
                    <p class="text-gray-600 mb-6">Vous n'avez pas de programme d'entraînement actif pour aujourd'hui.</p>
                    <a href="{{ route('member.program.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Voir les programmes disponibles
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar Content -->
        <div class="space-y-6">
            <!-- Upcoming Classes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Cours à venir</h3>
                    <a href="{{ route('member.classes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($upcomingClasses as $booking)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $booking->class->name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $booking->class->schedule_time->format('d/m H:i') }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                Confirmé
                            </span>
                        </div>
                    @endforeach
                    @if($upcomingClasses->isEmpty())
                        <p class="text-center text-gray-500 py-4">Aucun cours réservé</p>
                    @endif
                </div>
            </div>

            <!-- Active Challenges -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Challenges Actifs</h3>
                    <a href="{{ route('member.challenges.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($activeChallenges as $challenge)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-yellow-300 transition-colors">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center text-white">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $challenge->title }}</h4>
                                        <p class="text-xs text-gray-600">{{ $challenge->type }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-yellow-600">
                                    {{ $challenge->participants->first()->current_progress }}/{{ $challenge->target_value }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span class="text-gray-600">Progression</span>
                                    <span class="font-medium text-gray-800">
                                        {{ round(($challenge->participants->first()->current_progress / $challenge->target_value) * 100) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2 rounded-full" 
                                         style="width: {{ ($challenge->participants->first()->current_progress / $challenge->target_value) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>{{ $challenge->points_reward }} points</span>
                                <span>J-{{ now()->diffInDays($challenge->end_date) }}</span>
                            </div>
                        </div>
                    @endforeach
                    @if($activeChallenges->isEmpty())
                        <p class="text-center text-gray-500 py-4">Aucun challenge actif</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Actions Rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('member.qrcode') }}" class="flex items-center justify-between p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <span class="font-medium">Mon QR Code</span>
                        <i class="fas fa-qrcode"></i>
                    </a>
                    <a href="{{ route('member.attendance.index') }}" class="flex items-center justify-between p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <span class="font-medium">Mes Présences</span>
                        <i class="fas fa-history"></i>
                    </a>
                    <a href="{{ route('member.classes.index') }}" class="flex items-center justify-between p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <span class="font-medium">Réserver un cours</span>
                        <i class="fas fa-calendar-plus"></i>
                    </a>
                    <a href="{{ route('member.progress.index') }}" class="flex items-center justify-between p-3 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                        <span class="font-medium">Ma Progression</span>
                        <i class="fas fa-chart-line"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendances -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Mes Présences Récentes</h3>
            <a href="{{ route('member.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Voir l'historique complet
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure d'entrée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure de sortie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentAttendances as $attendance)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_in->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '--' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendance->duration_minutes)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $attendance->duration_minutes }} min
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        En cours
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->entry_method === 'qr_code' ? 'QR Code' : 'Manuel' }}
                            </td>
                        </tr>
                    @endforeach
                    @if($recentAttendances->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-history text-3xl mb-3 opacity-50"></i>
                                <p>Aucune présence enregistrée</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Complete exercise functionality
    document.querySelectorAll('.complete-exercise-btn').forEach(button => {
        button.addEventListener('click', function() {
            const exerciseId = this.getAttribute('data-exercise-id');
            const button = this;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Mark as completed
                button.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                button.classList.remove('border-gray-300', 'hover:border-green-500', 'hover:bg-green-50');
                button.classList.add('border-green-500', 'bg-green-50');
                
                // Update progress
                updateProgress();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Exercice complété !',
                    text: 'Bien joué ! Continue comme ça.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, 1000);
        });
    });
    
    function updateProgress() {
        // Update progress bar
        const completed = document.querySelectorAll('.complete-exercise-btn i.fa-check.text-green-600').length;
        const total = document.querySelectorAll('.complete-exercise-btn').length;
        const percentage = Math.round((completed / total) * 100);
        
        // Update progress bar width
        const progressBar = document.querySelector('.bg-gradient-to-r.from-blue-500.to-blue-600');
        progressBar.style.width = `${percentage}%`;
        
        // Update text
        const progressText = document.querySelector('.text-blue-600:last-child');
        if (progressText) {
            progressText.textContent = `${completed}/${total} exercices`;
        }
    }
    
    // Check in functionality
    const checkInBtn = document.getElementById('checkInBtn');
    if (checkInBtn) {
        checkInBtn.addEventListener('click', function() {
            Swal.fire({
                title: 'Scanner votre QR Code',
                text: 'Présentez votre QR Code au scanner à l\'entrée',
                imageUrl: '{{ Auth::user()->qr_code_path }}',
                imageWidth: 200,
                imageHeight: 200,
                showCancelButton: true,
                confirmButtonText: 'J\'ai scanné',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulate check-in API call
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Entrée enregistrée !',
                            text: 'Bonne séance !',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Update button state
                        checkInBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Vous êtes dans la salle';
                        checkInBtn.disabled = true;
                        checkInBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        checkInBtn.classList.add('bg-gray-600');
                    }, 1000);
                }
            });
        });
    }
</script>
@endsection