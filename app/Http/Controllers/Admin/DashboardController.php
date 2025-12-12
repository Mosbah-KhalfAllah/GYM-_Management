<?php
// app/Http\Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Membership;
use App\Models\ClassModel;
use App\Models\Equipment;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => User::members()->count(),
            'active_members' => User::members()->active()->count(),
            'total_coaches' => User::coaches()->count(),
            'today_attendance' => Attendance::today()->count(),
            'active_memberships' => Membership::active()->count(),
            'expiring_memberships' => Membership::expiringSoon()->count(),
            'total_classes' => ClassModel::count(),
            'upcoming_classes' => ClassModel::where('schedule_time', '>', now())
                ->where('status', 'scheduled')
                ->count(),
            'equipment_issues' => Equipment::whereIn('status', ['maintenance', 'out_of_order'])->count(),
            'monthly_revenue' => Payment::whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->sum('amount'),
        ];

        // Données pour les graphiques
        $attendanceData = $this->getAttendanceChartData();
        $revenueData = $this->getRevenueChartData();
        $membershipData = $this->getMembershipChartData();

        // Dernières activités
        $recentAttendances = Attendance::with('user')
            ->latest()
            ->take(10)
            ->get();

        $recentPayments = Payment::with('user')
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'attendanceData',
            'revenueData',
            'membershipData',
            'recentAttendances',
            'recentPayments'
        ));
    }

    private function getAttendanceChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Attendance::whereDate('check_in', $date)->count();
            $data[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getRevenueChartData()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
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
            'active' => Membership::active()->count(),
            'expired' => Membership::where('status', 'expired')->count(),
            'cancelled' => Membership::where('status', 'cancelled')->count(),
            'pending' => Membership::where('status', 'pending')->count(),
        ];
    }
}