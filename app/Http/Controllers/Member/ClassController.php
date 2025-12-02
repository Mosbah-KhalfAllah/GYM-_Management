<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\ClassBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    /**
     * Display a listing of available classes.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Cours disponibles (non réservés, non passés, avec places disponibles)
        $availableClasses = ClassModel::where('status', 'scheduled')
            ->where('schedule_time', '>', now())
            ->whereColumn('registered_count', '<', 'capacity')
            ->whereDoesntHave('bookings', function ($query) use ($user) {
                $query->where('member_id', $user->id)
                    ->where('status', 'confirmed');
            })
            ->with('coach')
            ->orderBy('schedule_time')
            ->paginate(12);
        
        // Cours réservés
        $bookedClasses = ClassModel::whereHas('bookings', function ($query) use ($user) {
                $query->where('member_id', $user->id)
                    ->where('status', 'confirmed');
            })
            ->where('schedule_time', '>', now())
            ->with('coach')
            ->orderBy('schedule_time')
            ->get();
        
        // Historique des cours
        $classHistory = ClassModel::whereHas('bookings', function ($query) use ($user) {
                $query->where('member_id', $user->id);
            })
            ->where('schedule_time', '<', now())
            ->with('coach')
            ->orderBy('schedule_time', 'desc')
            ->paginate(10);
        
        return view('member.classes.index', compact(
            'availableClasses',
            'bookedClasses',
            'classHistory'
        ));
    }

    /**
     * Display the specified class.
     */
    public function show(ClassModel $class)
    {
        $user = Auth::user();
        
        $isBooked = $class->bookings()
            ->where('member_id', $user->id)
            ->where('status', 'confirmed')
            ->exists();
        
        $spotsLeft = $class->capacity - $class->registered_count;
        
        return view('member.classes.show', compact('class', 'isBooked', 'spotsLeft'));
    }

    /**
     * Book a class.
     */
    public function book(Request $request, ClassModel $class)
    {
        $user = Auth::user();
        
        // Vérifications
        if ($class->status !== 'scheduled') {
            return redirect()->route('member.classes.show', $class)
                ->with('error', 'Ce cours n\'est plus disponible pour réservation.');
        }
        
        if ($class->schedule_time <= now()) {
            return redirect()->route('member.classes.show', $class)
                ->with('error', 'Ce cours est déjà passé.');
        }
        
        if ($class->registered_count >= $class->capacity) {
            return redirect()->route('member.classes.show', $class)
                ->with('error', 'Ce cours est complet.');
        }
        
        // Vérifier si déjà réservé
        $existingBooking = $class->bookings()
            ->where('member_id', $user->id)
            ->where('status', 'confirmed')
            ->first();
        
        if ($existingBooking) {
            return redirect()->route('member.classes.show', $class)
                ->with('error', 'Vous avez déjà réservé ce cours.');
        }
        
        // Créer la réservation
        $booking = ClassBooking::create([
            'class_id' => $class->id,
            'member_id' => $user->id,
            'booking_code' => 'BK' . strtoupper(uniqid()),
            'status' => 'confirmed',
        ]);
        
        // Mettre à jour le compteur
        $class->increment('registered_count');
        
        return redirect()->route('member.classes.show', $class)
            ->with('success', 'Réservation confirmée! Votre code de réservation: ' . $booking->booking_code);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, ClassBooking $booking)
    {
        $user = Auth::user();
        
        // Vérifier que la réservation appartient à l'utilisateur
        if ($booking->member_id !== $user->id) {
            abort(403);
        }
        
        // Vérifier que le cours n'a pas encore commencé
        if ($booking->class->schedule_time <= now()) {
            return redirect()->route('member.classes.index')
                ->with('error', 'Impossible d\'annuler un cours qui a déjà commencé.');
        }
        
        // Annuler la réservation
        $booking->update(['status' => 'cancelled']);
        
        // Décrémenter le compteur
        $booking->class->decrement('registered_count');
        
        return redirect()->route('member.classes.index')
            ->with('success', 'Réservation annulée avec succès.');
    }

    /**
     * Store a new class booking (for resource route).
     */
    public function store(Request $request)
    {
        // Cette méthode est utilisée par la route resource
        // Redirige vers la méthode book spécifique
        return $this->book($request, ClassModel::findOrFail($request->class_id));
    }

    /**
     * Destroy a booking (for resource route).
     */
    public function destroy(ClassBooking $booking)
    {
        // Cette méthode est utilisée par la route resource
        // Redirige vers la méthode cancel spécifique
        return $this->cancel(request(), $booking);
    }
}