<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the exercises.
     */
    public function index()
    {
        $coachId = auth()->id();
        $exercises = Exercise::whereHas('program', function ($query) use ($coachId) {
            $query->where('coach_id', $coachId);
        })->with('program')->latest()->paginate(10);
        
        return view('coach.exercises.index', compact('exercises'));
    }

    /**
     * Show the form for creating a new exercise.
     */
    public function create()
    {
        $coachId = auth()->id();
        $programs = WorkoutProgram::where('coach_id', $coachId)->where('is_active', true)->get();
        
        return view('coach.exercises.create', compact('programs'));
    }

    /**
     * Store a newly created exercise in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:workout_programs,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sets' => 'required|integer|min:1',
            'reps' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_seconds' => 'required|integer|min:0',
            'day_number' => 'required|integer|min:1',
            'video_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        // Vérifier que le programme appartient au coach
        $program = WorkoutProgram::findOrFail($validated['program_id']);
        if ($program->coach_id !== auth()->id()) {
            abort(403);
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('exercises', 'public');
        }

        Exercise::create($validated);

        return redirect()->route('coach.exercises.index')
            ->with('success', 'Exercice créé avec succès.');
    }

    /**
     * Display the specified exercise.
     */
    public function show(Exercise $exercise)
    {
        // Vérifier que l'exercice appartient à un programme du coach
        if ($exercise->program->coach_id !== auth()->id()) {
            abort(403);
        }

        return view('coach.exercises.show', compact('exercise'));
    }

    /**
     * Show the form for editing the specified exercise.
     */
    public function edit(Exercise $exercise)
    {
        if ($exercise->program->coach_id !== auth()->id()) {
            abort(403);
        }

        $coachId = auth()->id();
        $programs = WorkoutProgram::where('coach_id', $coachId)->where('is_active', true)->get();

        return view('coach.exercises.edit', compact('exercise', 'programs'));
    }

    /**
     * Update the specified exercise in storage.
     */
    public function update(Request $request, Exercise $exercise)
    {
        if ($exercise->program->coach_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'program_id' => 'required|exists:workout_programs,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sets' => 'required|integer|min:1',
            'reps' => 'required|integer|min:1',
            'weight' => 'nullable|numeric|min:0',
            'rest_seconds' => 'required|integer|min:0',
            'day_number' => 'required|integer|min:1',
            'video_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        // Vérifier que le nouveau programme appartient au coach
        $program = WorkoutProgram::findOrFail($validated['program_id']);
        if ($program->coach_id !== auth()->id()) {
            abort(403);
        }

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($exercise->image_path) {
                \Storage::disk('public')->delete($exercise->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('exercises', 'public');
        }

        $exercise->update($validated);

        return redirect()->route('coach.exercises.index')
            ->with('success', 'Exercice mis à jour avec succès.');
    }

    /**
     * Remove the specified exercise from storage.
     */
    public function destroy(Exercise $exercise)
    {
        if ($exercise->program->coach_id !== auth()->id()) {
            abort(403);
        }

        // Supprimer l'image si elle existe
        if ($exercise->image_path) {
            \Storage::disk('public')->delete($exercise->image_path);
        }

        $exercise->delete();

        return redirect()->route('coach.exercises.index')
            ->with('success', 'Exercice supprimé avec succès.');
    }
}