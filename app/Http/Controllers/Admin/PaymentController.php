<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user')->latest()->paginate(15);
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        
        return view('admin.payments.index', compact('payments', 'totalRevenue', 'pendingPayments', 'monthlyRevenue'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        return view('admin.payments.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        Payment::create(array_merge($validated, [
            'payment_id' => 'PAY_' . uniqid(),
            'currency' => 'USD',
            'payment_gateway' => 'manual',
        ]));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function show(Payment $payment)
    {
        $payment->load('user');
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        return view('admin.payments.edit', compact('payment', 'members'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|in:pending,completed,failed,refunded',
        ]);

        $payment->update($validated);
        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement mis à jour avec succès.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')
            ->with('success', 'Paiement supprimé avec succès.');
    }
}