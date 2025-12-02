<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('coach')->latest()->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.classes.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:30|max:240',
            'capacity' => 'required|integer|min:1|max:100',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        ClassModel::create(array_merge($validated, [
            'status' => 'scheduled',
            'registered_count' => 0,
        ]));

        return redirect()->route('admin.classes.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function show(ClassModel $class)
    {
        $class->load(['coach', 'bookings.member']);
        return view('admin.classes.show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $coaches = User::where('role', 'coach')->where('is_active', true)->get();
        return view('admin.classes.edit', compact('class', 'coaches'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'coach_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:30|max:240',
            'capacity' => 'required|integer|min:1|max:100',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $class->update($validated);
        return redirect()->route('admin.classes.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(ClassModel $class)
    {
        if ($class->registered_count > 0) {
            return redirect()->route('admin.classes.index')
                ->with('error', 'Impossible de supprimer un cours avec des inscriptions.');
        }
        
        $class->delete();
        return redirect()->route('admin.classes.index')
            ->with('success', 'Cours supprimé avec succès.');
    }
}