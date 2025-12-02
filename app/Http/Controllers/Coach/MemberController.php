<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkoutProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $coach = Auth::user();
        
        // Membres assignés aux programmes du coach
        $members = User::whereHas('programs', function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        })
        ->with(['membership', 'programs'])
        ->latest()
        ->paginate(10);
        
        return view('coach.members.index', compact('members'));
    }

    public function show(User $member)
    {
        $coach = Auth::user();
        
        // Vérifier que le membre est assigné au coach
        $isAssigned = $member->programs()
            ->where('coach_id', $coach->id)
            ->exists();
            
        if (!$isAssigned) {
            abort(404, 'Membre non assigné à ce coach.');
        }
        
        $member->load(['membership', 'attendances' => function ($query) {
            $query->latest()->take(10);
        }, 'programs' => function ($query) use ($coach) {
            $query->where('coach_id', $coach->id);
        }]);
        
        $programs = WorkoutProgram::where('coach_id', $coach->id)
            ->where('is_active', true)
            ->get();
        
        return view('coach.members.show', compact('member', 'programs'));
    }

    public function edit(User $member)
    {
        $coach = Auth::user();
        
        // Vérifier que le membre est assigné au coach
        $isAssigned = $member->programs()
            ->where('coach_id', $coach->id)
            ->exists();
            
        if (!$isAssigned) {
            abort(404, 'Membre non assigné à ce coach.');
        }
        
        return view('coach.members.edit', compact('member'));
    }

    public function update(Request $request, User $member)
    {
        $coach = Auth::user();
        
        // Vérifier que le membre est assigné au coach
        $isAssigned = $member->programs()
            ->where('coach_id', $coach->id)
            ->exists();
            
        if (!$isAssigned) {
            abort(404, 'Membre non assigné à ce coach.');
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
        ]);
        
        $member->update($validated);
        
        return redirect()->route('coach.members.show', $member->id)
            ->with('success', 'Profil du membre mis à jour avec succès.');
    }

    public function assignProgramForm(User $member)
    {
        $coach = Auth::user();
        
        // Vérifier que le membre est assigné au coach
        $isAssigned = $member->programs()
            ->where('coach_id', $coach->id)
            ->exists();
            
        if (!$isAssigned) {
            abort(404, 'Membre non assigné à ce coach.');
        }
        
        $currentPrograms = $member->programs()->pluck('workout_programs.id')->toArray();
        
        $availablePrograms = WorkoutProgram::where('coach_id', $coach->id)
            ->where('is_active', true)
            ->whereNotIn('id', $currentPrograms)
            ->get();
        
        $member->load('programs');
        
        return view('coach.members.assign', compact('member', 'availablePrograms'));
    }

    public function assignProgram(Request $request, User $member)
    {
        $coach = Auth::user();
        
        // Vérifier que le membre est assigné au coach
        $isAssigned = $member->programs()
            ->where('coach_id', $coach->id)
            ->exists();
            
        if (!$isAssigned) {
            abort(404, 'Membre non assigné à ce coach.');
        }
        
        $programId = $request->input('program_id');
        
        // Vérifier que le programme appartient au coach
        $program = WorkoutProgram::findOrFail($programId);
        if ($program->coach_id !== $coach->id) {
            abort(403, 'Vous ne pouvez pas assigner ce programme.');
        }
        
        // Vérifier que le programme n'est pas déjà assigné
        if ($member->programs()->where('program_id', $programId)->exists()) {
            return redirect()->route('coach.members.assignProgram', $member->id)
                ->with('error', 'Ce programme est déjà assigné au membre.');
        }
        
        // Assigner le programme
        $member->programs()->attach($programId, [
            'status' => 'active',
            'current_day' => 1,
            'completion_percentage' => 0,
        ]);
        
        return redirect()->route('coach.members.show', $member->id)
            ->with('success', 'Programme assigné avec succès.');
    }
}