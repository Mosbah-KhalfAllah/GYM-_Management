<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('user')
            ->latest()
            ->paginate(20);
        
        $todayCount = Attendance::whereDate('check_in', today())->count();
        $weekCount = Attendance::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        return view('admin.attendance.index', compact('attendances', 'todayCount', 'weekCount'));
    }

    public function showRecord()
    {
        return view('admin.attendance.record');
    }

    public function record(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'action' => 'required|in:check_in,check_out',
        ]);

        try {
            $user = User::find($request->user_id);
            
            if (!$user || $user->role !== 'member' || !$user->is_active) {
                return response()->json(['error' => 'Membre non trouvé ou inactif'], 400);
            }

            if ($request->action === 'check_in') {
                // Vérifier si l'utilisateur a déjà un check-in actif
                $activeAttendance = Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', today())
                    ->whereNull('check_out')
                    ->first();

                if ($activeAttendance) {
                    return response()->json(['error' => 'Cet utilisateur a déjà une présence active'], 400);
                }

                // Créer un nouveau check-in
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'check_in' => now(),
                    'entry_method' => 'button',
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_in',
                    'user' => $user->full_name,
                    'check_in' => now()->format('H:i'),
                ]);
            } else {
                // Check-out
                $activeAttendance = Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', today())
                    ->whereNull('check_out')
                    ->first();

                if (!$activeAttendance) {
                    return response()->json(['error' => 'Aucune présence active pour cet utilisateur'], 400);
                }

                $activeAttendance->update([
                    'check_out' => now(),
                    'duration_minutes' => $activeAttendance->check_in->diffInMinutes(now()),
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_out',
                    'user' => $user->full_name,
                    'check_out' => now()->format('H:i'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    public function searchMembers(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $members = User::where('role', 'member')
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$query%"])
                  ->orWhere('email', 'like', "%$query%")
                  ->orWhere('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%");
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->limit(10)
            ->get();

        return response()->json($members);
    }
}
