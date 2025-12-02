<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkoutProgram;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = WorkoutProgram::with('coach')->latest()->paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.programs.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
        ]);

        WorkoutProgram::create(array_merge($validated, ['is_active' => true]));
        return redirect()->route('admin.programs.index')
            ->with('success', 'Programme créé avec succès.');
    }

    public function show(WorkoutProgram $program)
    {
        $program->load(['coach', 'exercises', 'members']);
        return view('admin.programs.show', compact('program'));
    }

    public function edit(WorkoutProgram $program)
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.programs.edit', compact('program', 'coaches'));
    }

    public function update(Request $request, WorkoutProgram $program)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
            'is_active' => 'boolean',
        ]);

        $program->update($validated);
        return redirect()->route('admin.programs.index')
            ->with('success', 'Programme mis à jour avec succès.');
    }

    public function destroy(WorkoutProgram $program)
    {
        $program->update(['is_active' => false]);
        return redirect()->route('admin.programs.index')
            ->with('success', 'Programme désactivé avec succès.');
    }
}