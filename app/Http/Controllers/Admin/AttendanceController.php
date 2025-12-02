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

    public function scanner()
    {
        return view('admin.attendance.scanner');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        try {
            $data = json_decode($request->qr_code, true);
            
            if (!isset($data['user_id'])) {
                return response()->json(['error' => 'QR Code invalide'], 400);
            }

            $user = User::find($data['user_id']);
            
            if (!$user || $user->role !== 'member' || !$user->is_active) {
                return response()->json(['error' => 'Membre non trouvé ou inactif'], 400);
            }

            // Vérifier si l'utilisateur a déjà check-in aujourd'hui sans check-out
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
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_out',
                    'user' => $user->full_name,
                    'check_in' => $activeAttendance->check_in->format('H:i'),
                    'check_out' => now()->format('H:i'),
                ]);
            } else {
                // Check-in
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'check_in' => now(),
                    'entry_method' => 'qr_code',
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'check_in',
                    'user' => $user->full_name,
                    'check_in' => now()->format('H:i'),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur de scan: ' . $e->getMessage()], 500);
        }
    }
}