<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function membersReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        // Récupérer tous les membres actifs au lieu de filtrer par date de création
        $members = User::where('role', 'member')
            ->where('is_active', true)
            ->with('membership')
            ->get();

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.members-pdf', compact('members', 'startDate', 'endDate'));
            return $pdf->download('rapport-membres-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('admin.reports.members', compact('members', 'startDate', 'endDate'));
    }

    public function paymentsReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $payments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        $totalRevenue = $payments->sum('amount');
        $paymentsByMethod = $payments->groupBy('payment_method');

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.payments-pdf', compact('payments', 'totalRevenue', 'paymentsByMethod', 'startDate', 'endDate'));
            return $pdf->download('rapport-paiements-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('admin.reports.payments', compact('payments', 'totalRevenue', 'paymentsByMethod', 'startDate', 'endDate'));
    }

    public function attendanceReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $attendances = Attendance::whereBetween('check_in', [$startDate, $endDate])
            ->with('user')
            ->get();

        $dailyStats = $attendances->groupBy(function($attendance) {
            return Carbon::parse($attendance->check_in)->format('Y-m-d');
        });

        if ($request->get('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.attendance-pdf', compact('attendances', 'dailyStats', 'startDate', 'endDate'));
            return $pdf->download('rapport-presences-' . now()->format('Y-m-d') . '.pdf');
        }

        return view('admin.reports.attendance', compact('attendances', 'dailyStats', 'startDate', 'endDate'));
    }

    public function exportMembersExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        
        $members = User::where('role', 'member')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('membership')
            ->get();

        $csvData = "Nom,Prénom,Email,Téléphone,Statut,Date d'inscription\n";
        
        foreach ($members as $member) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s,%s\n",
                $member->last_name,
                $member->first_name,
                $member->email,
                $member->phone,
                $member->membership->status ?? 'N/A',
                $member->created_at->format('d/m/Y')
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="membres-' . now()->format('Y-m-d') . '.csv"');
    }
}