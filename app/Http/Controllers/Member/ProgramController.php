<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\WorkoutProgram;
use App\Models\MemberProgram;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    /**
     * Display a listing of available programs.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Programmes disponibles
        $availablePrograms = WorkoutProgram::where('is_active', true)
            ->with('coach')
            ->whereDoesntHave('members', function ($query) use ($user) {
                $query->where('member_id', $user->id)
                    ->whereIn('status', ['active', 'paused']);
            })
            ->latest()
            ->get();
        
        // Programme actif de l'utilisateur
        $activeProgram = $user->programs()->wherePivot('status', 'active')->first();
        
        // Historique des programmes
        $programHistory = $user->programs()
            ->wherePivot('status', 'completed')
            ->withPivot(['start_date', 'end_date', 'completion_percentage'])
            ->latest()
            ->get();
        
        return view('member.program.index', compact(
            'availablePrograms',
            'activeProgram',
            'programHistory'
        ));
    }

    /**
     * Enroll in a program.
     */
    public function enroll(Request $request, WorkoutProgram $program)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà un programme actif
        if ($user->activeProgram) {
            return redirect()->route('member.program.index')
                ->with('error', 'Vous avez déjà un programme actif. Terminez-le ou mettez-le en pause avant de commencer un nouveau.');
        }
        
        // Vérifier si le programme est actif
        if (!$program->is_active) {
            return redirect()->route('member.program.index')
                ->with('error', 'Ce programme n\'est plus disponible.');
        }
        
        // S'inscrire au programme
        $user->programs()->attach($program->id, [
            'start_date' => now(),
            'end_date' => now()->addDays($program->duration_days),
            'current_day' => 1,
            'completed_exercises' => 0,
            'completion_percentage' => 0,
            'status' => 'active',
        ]);
        
        return redirect()->route('member.program.index')
            ->with('success', 'Vous êtes maintenant inscrit au programme "' . $program->title . '"');
    }

    /**
     * Mark an exercise as completed.
     */
    public function completeExercise(Request $request, Exercise $exercise)
    {
        $user = Auth::user();
        
        // Vérifier si l'exercice appartient au programme actif de l'utilisateur
        $activeProgram = $user->activeProgram;
        
        if (!$activeProgram || $exercise->program_id !== $activeProgram->id) {
            return response()->json([
                'success' => false,
                'message' => 'Exercice non trouvé dans votre programme actif.'
            ], 404);
        }
        
        // Vérifier si l'exercice est pour le jour actuel
        if ($exercise->day_number !== $activeProgram->pivot->current_day) {
            return response()->json([
                'success' => false,
                'message' => 'Cet exercice n\'est pas prévu pour aujourd\'hui.'
            ], 400);
        }
        
        // Enregistrer la complétion
        $log = \App\Models\ExerciseLog::updateOrCreate(
            [
                'member_program_id' => $activeProgram->pivot->id,
                'exercise_id' => $exercise->id,
                'workout_date' => today(),
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );
        
        // Mettre à jour la progression
        $completedExercises = \App\Models\ExerciseLog::where('member_program_id', $activeProgram->pivot->id)
            ->where('workout_date', today())
            ->where('completed', true)
            ->count();
        
        $totalExercisesToday = Exercise::where('program_id', $activeProgram->id)
            ->where('day_number', $activeProgram->pivot->current_day)
            ->count();
        
        $percentage = $totalExercisesToday > 0 ? ($completedExercises / $totalExercisesToday) * 100 : 0;
        
        return response()->json([
            'success' => true,
            'completed_exercises' => $completedExercises,
            'total_exercises' => $totalExercisesToday,
            'percentage' => round($percentage),
            'message' => 'Exercice marqué comme terminé!'
        ]);
    }
}