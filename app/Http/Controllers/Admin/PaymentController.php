<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user');
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('payment_id', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if (in_array($sortField, ['id', 'amount', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }
        
        $payments = $query->paginate(15);
        
        // Statistiques
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        return view('admin.payments.index', compact('payments', 'totalRevenue', 'pendingPayments', 'monthlyRevenue'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        $selectedMemberId = request()->query('member_id');
        return view('admin.payments.create', compact('members', 'selectedMemberId'));
    }
    
    public function quickPayment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_method' => 'required|in:cash,card,online',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['payment_id'] = 'PAY_' . strtoupper(uniqid());
        $validated['currency'] = 'EUR';
        $validated['status'] = 'completed';
        $validated['payment_gateway'] = 'manual';

        $payment = Payment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paiement enregistré avec succès',
            'payment' => $payment
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_method' => 'required|in:cash,card,online',
            'status' => 'required|in:pending,completed,failed',
            'description' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:3',
        ], [
            'user_id.required' => 'Le membre est requis.',
            'user_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'amount.required' => 'Le montant est requis.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'status.required' => 'Le statut est requis.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
        ]);

        $validated['payment_id'] = 'PAY_' . strtoupper(uniqid());
        $validated['currency'] = $validated['currency'] ?? 'EUR';
        $validated['payment_gateway'] = 'manual';

        Payment::create($validated);

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
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'payment_method' => 'required|in:cash,card,online',
            'status' => 'required|in:pending,completed,failed,refunded',
            'description' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:3',
        ], [
            'user_id.required' => 'Le membre est requis.',
            'user_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'amount.required' => 'Le montant est requis.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur à 0.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'status.required' => 'Le statut est requis.',
            'description.max' => 'La description ne peut pas dépasser 500 caractères.',
        ]);

        $validated['currency'] = $validated['currency'] ?? 'EUR';
        
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
    
    public function memberPayments($memberId)
    {
        $member = User::findOrFail($memberId);
        $payments = Payment::where('user_id', $memberId)
            ->with('user')
            ->latest()
            ->paginate(10);
            
        return view('admin.payments.member', compact('member', 'payments'));
    }
}