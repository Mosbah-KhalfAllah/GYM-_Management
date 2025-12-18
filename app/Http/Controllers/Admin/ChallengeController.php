<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::latest()->paginate(15);
        $activeChallenges = Challenge::where('is_active', true)->count();
        $completedChallenges = Challenge::where('end_date', '<', now())->count();
        
        return view('admin.challenges.index', compact('challenges', 'activeChallenges', 'completedChallenges'));
    }

    public function create()
    {
        return view('admin.challenges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:attendance,workout,class,weight_loss,muscle_gain',
            'target_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'points_reward' => 'required|integer|min:1',
        ]);

        Challenge::create(array_merge($validated, ['is_active' => true]));
        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge créé avec succès.');
    }

    public function show(Challenge $challenge)
    {
        $challenge->load(['participants.member']);
        return view('admin.challenges.show', compact('challenge'));
    }

    public function edit(Challenge $challenge)
    {
        return view('admin.challenges.edit', compact('challenge'));
    }

    public function update(Request $request, Challenge $challenge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:attendance,workout,class,weight_loss,muscle_gain',
            'target_value' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'points_reward' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $challenge->update($validated);
        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge mis à jour avec succès.');
    }

    public function destroy(Challenge $challenge)
    {
        if ($challenge->participants()->count() > 0) {
            return redirect()->route('admin.challenges.index')
                ->with('error', 'Impossible de supprimer un challenge avec des participants.');
        }
        
        $challenge->delete();
        return redirect()->route('admin.challenges.index')
            ->with('success', 'Challenge supprimé avec succès.');
    }
}