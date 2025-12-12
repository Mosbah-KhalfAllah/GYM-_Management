<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    /**
     * Display today's workout.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier la présence actuelle
        $currentAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->whereDate('check_in', today())
            ->first();
        
        // Programme actif
        $activeProgram = $user->activeProgram;
        $todaysExercises = [];
        
        if ($activeProgram) {
            $todaysExercises = Exercise::where('program_id', $activeProgram->id)
                ->where('day_number', $activeProgram->pivot->current_day)
                ->get();
            
            // Exercices complétés aujourd'hui
            $completedExercises = \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user, $activeProgram) {
                    $query->where('member_id', $user->id)
                        ->where('program_id', $activeProgram->id);
                })
                ->where('workout_date', today())
                ->where('completed', true)
                ->pluck('exercise_id')
                ->toArray();
        }
        
        // Statistiques de la semaine
        $weekStats = [
            'workout_days' => Attendance::where('user_id', $user->id)
                ->whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])
                ->selectRaw('DISTINCT DATE(check_in) as date')
                ->count(),
            'total_minutes' => Attendance::where('user_id', $user->id)
                ->whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('duration_minutes'),
            'completed_exercises' => \App\Models\ExerciseLog::whereHas('memberProgram', function ($query) use ($user) {
                    $query->where('member_id', $user->id);
                })
                ->whereBetween('workout_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->where('completed', true)
                ->count(),
        ];
        
        return view('member.workout.index', compact(
            'currentAttendance',
            'activeProgram',
            'todaysExercises',
            'completedExercises',
            'weekStats'
        ));
    }

    /**
     * Check in for workout.
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà check-in aujourd'hui
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->whereDate('check_in', today())
            ->first();
        
        if ($existingAttendance) {
            return redirect()->route('member.workout.index')
                ->with('error', 'Vous êtes déjà dans la salle.');
        }
        
        // Créer une nouvelle présence
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'check_in' => now(),
            'entry_method' => 'manual',
        ]);
        
        return redirect()->route('member.workout.index')
            ->with('success', 'Entrée enregistrée. Bon entraînement!');
    }

    /**
     * Check out from workout.
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        
        // Trouver la présence active
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('check_out')
            ->whereDate('check_in', today())
            ->firstOrFail();
        
        // Calculer la durée
        $duration = $attendance->check_in->diffInMinutes(now());
        
        // Mettre à jour
        $attendance->update([
            'check_out' => now(),
            'duration_minutes' => $duration,
        ]);
        
        return redirect()->route('member.workout.index')
            ->with('success', 'Sortie enregistrée. Vous avez passé ' . $duration . ' minutes à la salle.');
    }
}