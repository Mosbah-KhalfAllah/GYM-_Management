<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['user:id,first_name,last_name,email'])->latest();
        $member = null;
        
        // Filtrer par membre si spécifié
        if ($request->filled('member_id')) {
            $query->where('user_id', $request->member_id);
            $member = User::with('membership')->find($request->member_id);
        }
        
        $attendances = $query->paginate(15);
        
        $todayCount = Attendance::whereDate('check_in', today())->count();
        $weekCount = Attendance::whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        return view('admin.attendance.index', compact('attendances', 'todayCount', 'weekCount'))->with('member', $member ?? null);
    }

    public function showRecord()
    {
        return view('admin.attendance.record');
    }

    public function record(Request $request)
    {
        // Si member_id est fourni directement (depuis la page du membre)
        if ($request->filled('member_id')) {
            $user = User::find($request->member_id);
            
            if (!$user || $user->role !== 'member' || !$user->is_active) {
                return back()->with('error', 'Membre non trouvé ou inactif');
            }

            // Vérifier s'il y a une présence active
            $activeAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('check_in', today())
                ->whereNull('check_out')
                ->first();

            if ($activeAttendance) {
                // Check-out
                $activeAttendance->update([
                    'check_out' => now(),
                    'duration_minutes' => $activeAttendance->check_in->diffInMinutes(now()),
                ]);
                return back()->with('success', 'Sortie enregistrée pour ' . $user->full_name);
            } else {
                // Check-in
                Attendance::create([
                    'user_id' => $user->id,
                    'check_in' => now(),
                    'entry_method' => 'manual',
                ]);
                return back()->with('success', 'Entrée enregistrée pour ' . $user->full_name);
            }
        }

        // Code existant pour l'API
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
                $activeAttendance = Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', today())
                    ->whereNull('check_out')
                    ->first();

                if ($activeAttendance) {
                    return response()->json(['error' => 'Cet utilisateur a déjà une présence active'], 400);
                }

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

        $members = User::select('id', 'first_name', 'last_name', 'email')
            ->where('role', 'member')
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$query%"])
                  ->orWhere('email', 'like', "%$query%")
                  ->orWhere('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%");
            })
            ->limit(10)
            ->get();

        return response()->json($members);
    }
}
