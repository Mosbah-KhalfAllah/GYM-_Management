<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\WorkoutProgram;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index()
    {
        $coach = Auth::user();
        $programs = WorkoutProgram::where('coach_id', $coach->id)
            ->withCount(['exercises', 'members'])
            ->latest()
            ->paginate(10);
            
        return view('coach.programs.index', compact('programs'));
    }

    public function create()
    {
        return view('coach.programs.create');
    }

    public function store(Request $request)
    {
        $coach = Auth::user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
        ]);

        $program = WorkoutProgram::create(array_merge($validated, [
            'coach_id' => $coach->id,
            'is_active' => true,
        ]));

        return redirect()->route('coach.programs.show', $program)
            ->with('success', 'Programme créé avec succès.');
    }

    public function show(WorkoutProgram $program)
    {
        $this->authorize('view', $program);
        
        $program->load(['exercises', 'members']);
        $members = User::where('role', 'member')
            ->where('is_active', true)
            ->get();
            
        return view('coach.programs.show', compact('program', 'members'));
    }

    public function edit(WorkoutProgram $program)
    {
        $this->authorize('update', $program);
        return view('coach.programs.edit', compact('program'));
    }

    public function update(Request $request, WorkoutProgram $program)
    {
        $this->authorize('update', $program);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
            'is_active' => 'boolean',
        ]);

        $program->update($validated);
        return redirect()->route('coach.programs.show', $program)
            ->with('success', 'Programme mis à jour avec succès.');
    }

    public function destroy(WorkoutProgram $program)
    {
        $this->authorize('delete', $program);
        
        if ($program->members()->count() > 0) {
            return redirect()->route('coach.programs.index')
                ->with('error', 'Impossible de supprimer un programme avec des membres assignés.');
        }
        
        $program->delete();
        return redirect()->route('coach.programs.index')
            ->with('success', 'Programme supprimé avec succès.');
    }

    public function assign(Request $request, WorkoutProgram $program)
    {
        $this->authorize('update', $program);
        
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
        ]);

        $member = User::findOrFail($request->member_id);
        
        // Vérifier si le membre a déjà ce programme
        if ($member->programs()->where('program_id', $program->id)->exists()) {
            return redirect()->back()
                ->with('error', 'Ce membre a déjà ce programme.');
        }

        // Assigner le programme au membre
        $member->programs()->attach($program->id, [
            'start_date' => $request->start_date,
            'end_date' => $request->start_date->addDays($program->duration_days),
            'current_day' => 1,
            'completion_percentage' => 0,
            'status' => 'active',
        ]);

        return redirect()->back()
            ->with('success', 'Programme assigné avec succès.');
    }
}