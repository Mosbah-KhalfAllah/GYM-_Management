<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_members' => User::where('role', 'member')->count(),
            'active_members' => User::where('role', 'member')->where('is_active', true)->count(),
            'new_members_this_month' => User::where('role', 'member')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'attendance_today' => Attendance::whereDate('check_in', today())->count(),
            'attendance_this_month' => Attendance::whereMonth('check_in', now()->month)->count(),
            'active_memberships' => Membership::where('status', 'active')->count(),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'total_classes' => ClassModel::count(),
            'upcoming_classes' => ClassModel::where('schedule_time', '>', now())
                ->where('status', 'scheduled')
                ->count(),
        ];

        // Données pour les graphiques
        $attendanceData = $this->getAttendanceChartData();
        $revenueData = $this->getRevenueChartData();
        $membershipData = $this->getMembershipChartData();

        return view('admin.reports.index', compact('stats', 'attendanceData', 'revenueData', 'membershipData'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'attendance');
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        switch ($type) {
            case 'attendance':
                $data = Attendance::with('user')
                    ->whereBetween('check_in', [$startDate, $endDate])
                    ->get();
                break;
            case 'payments':
                $data = Payment::with('user')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                break;
            case 'members':
                $data = User::where('role', 'member')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
                break;
            default:
                $data = [];
        }

        // En production, vous utiliseriez une librairie comme maatwebsite/excel
        // Pour l'instant, on retourne une vue simple
        return view('admin.reports.export', compact('data', 'type', 'startDate', 'endDate'));
    }

    private function getAttendanceChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Attendance::whereDate('check_in', $date)->count();
            $data[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getRevenueChartData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Payment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'completed')
                ->sum('amount');
            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }
        return $data;
    }

    private function getMembershipChartData()
    {
        return [
            'active' => Membership::where('status', 'active')->count(),
            'expired' => Membership::where('status', 'expired')->count(),
            'cancelled' => Membership::where('status', 'cancelled')->count(),
            'pending' => Membership::where('status', 'pending')->count(),
        ];
    }
}