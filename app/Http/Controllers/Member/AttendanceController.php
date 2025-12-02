<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance history.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filtres
        $filter = $request->get('filter', 'month');
        $date = $request->get('date', now()->format('Y-m'));
        
        $query = Attendance::where('user_id', $user->id);
        
        switch ($filter) {
            case 'day':
                $query->whereDate('check_in', $date);
                break;
                
            case 'week':
                $startOfWeek = Carbon::parse($date)->startOfWeek();
                $endOfWeek = Carbon::parse($date)->endOfWeek();
                $query->whereBetween('check_in', [$startOfWeek, $endOfWeek]);
                break;
                
            case 'month':
                $startOfMonth = Carbon::parse($date)->startOfMonth();
                $endOfMonth = Carbon::parse($date)->endOfMonth();
                $query->whereBetween('check_in', [$startOfMonth, $endOfMonth]);
                break;
                
            case 'year':
                $startOfYear = Carbon::parse($date)->startOfYear();
                $endOfYear = Carbon::parse($date)->endOfYear();
                $query->whereBetween('check_in', [$startOfYear, $endOfYear]);
                break;
        }
        
        $attendances = $query->orderBy('check_in', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'today' => Attendance::where('user_id', $user->id)
                ->whereDate('check_in', today())
                ->count(),
            
            'this_week' => Attendance::where('user_id', $user->id)
                ->whereBetween('check_in', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            
            'this_month' => Attendance::where('user_id', $user->id)
                ->whereMonth('check_in', now()->month)
                ->whereYear('check_in', now()->year)
                ->count(),
            
            'total' => Attendance::where('user_id', $user->id)->count(),
            
            'average_duration' => Attendance::where('user_id', $user->id)
                ->whereNotNull('duration_minutes')
                ->avg('duration_minutes'),
        ];
        
        // Tendances (7 derniers jours)
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Attendance::where('user_id', $user->id)
                ->whereDate('check_in', $date)
                ->count();
            
            $trendData[] = [
                'date' => $date->format('D'),
                'count' => $count,
            ];
        }
        
        // Heures préférées
        $hourlyData = [];
        for ($hour = 6; $hour <= 22; $hour++) {
            $count = Attendance::where('user_id', $user->id)
                ->whereRaw('HOUR(check_in) = ?', [$hour])
                ->count();
            
            $hourlyData[] = [
                'hour' => sprintf('%02d:00', $hour),
                'count' => $count,
            ];
        }
        
        return view('member.attendance.index', compact(
            'attendances',
            'stats',
            'trendData',
            'hourlyData',
            'filter',
            'date'
        ));
    }
}