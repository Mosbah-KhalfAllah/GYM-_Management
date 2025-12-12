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
            'title' => 'required|string|max:150|min:3',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
        ], [
            'title.min' => 'Le titre doit contenir au moins 3 caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 150 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
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
            'title' => 'required|string|max:150|min:3',
            'description' => 'nullable|string|max:1000',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_days' => 'required|integer|min:1',
            'goal' => 'required|in:weight_loss,muscle_gain,endurance,flexibility',
            'is_active' => 'boolean',
        ], [
            'title.min' => 'Le titre doit contenir au moins 3 caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 150 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
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

    public function assignMemberForm(User $member)
    {
        $currentPrograms = $member->programs()->pluck('workout_programs.id')->toArray();
        
        $availablePrograms = WorkoutProgram::where('is_active', true)
            ->whereNotIn('id', $currentPrograms)
            ->with('coach')
            ->get();
        
        $member->load('programs');
        
        return view('admin.programs.assign-member', compact('member', 'availablePrograms'));
    }

    public function assignMember(Request $request, User $member)
    {
        $programId = $request->input('program_id');
        
        // Vérifier que le programme existe et est actif
        $program = WorkoutProgram::findOrFail($programId);
        if (!$program->is_active) {
            abort(403, 'Ce programme n\'est pas actif.');
        }
        
        // Vérifier que le programme n'est pas déjà assigné
        if ($member->programs()->where('program_id', $programId)->exists()) {
            return redirect()->route('admin.programs.assignMemberForm', $member->id)
                ->with('error', 'Ce programme est déjà assigné au membre.');
        }
        
        // Assigner le programme
        $member->programs()->attach($programId, [
            'status' => 'active',
            'current_day' => 1,
            'completion_percentage' => 0,
        ]);
        
        return redirect()->route('admin.members.show', $member->id)
            ->with('success', 'Programme assigné avec succès.');
    }
}