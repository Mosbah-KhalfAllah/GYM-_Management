<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassBooking;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the classes.
     */
    public function index()
    {
        $coachId = auth()->id();
        $classes = ClassModel::where('coach_id', $coachId)
            ->latest()
            ->paginate(10);
        
        return view('coach.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new class.
     */
    public function create()
    {
        return view('coach.classes.create');
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:240',
            'capacity' => 'required|integer|min:1|max:100',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['coach_id'] = auth()->id();
        $validated['status'] = 'scheduled';
        $validated['registered_count'] = 0;

        ClassModel::create($validated);

        return redirect()->route('coach.classes.index')
            ->with('success', 'Cours créé avec succès.');
    }

    /**
     * Display the specified class.
     */
    public function show(ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        $class->load(['bookings.member']);
        
        return view('coach.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified class.
     */
    public function edit(ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        return view('coach.classes.edit', compact('class'));
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'schedule_time' => 'required|date|after:now',
            'duration_minutes' => 'required|integer|min:30|max:240',
            'capacity' => 'required|integer|min:1|max:100',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        $class->update($validated);

        return redirect()->route('coach.classes.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        // Annuler toutes les réservations
        $class->bookings()->update(['status' => 'cancelled']);
        
        $class->delete();

        return redirect()->route('coach.classes.index')
            ->with('success', 'Cours supprimé avec succès.');
    }

    /**
     * Display attendees for a class.
     */
    public function attendees(ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        $attendees = $class->bookings()
            ->with('member')
            ->where('status', 'confirmed')
            ->get();
        
        return view('coach.classes.attendees', compact('class', 'attendees'));
    }

    /**
     * Check-in a member for a class.
     */
    public function checkIn(Request $request, ClassModel $class)
    {
        if ($class->coach_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'booking_id' => 'required|exists:class_bookings,id',
            'member_id' => 'required|exists:users,id',
        ]);

        $booking = ClassBooking::where('id', $validated['booking_id'])
            ->where('class_id', $class->id)
            ->where('member_id', $validated['member_id'])
            ->firstOrFail();

        $booking->update([
            'attended' => true,
            'check_in_time' => now(),
        ]);

        return redirect()->route('coach.classes.attendees', $class)
            ->with('success', 'Présence enregistrée avec succès.');
    }
}