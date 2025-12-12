<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $membership = $user->membership;
        $payments = Payment::where('user_id', $user->id)->latest()->paginate(10);
        
        return view('member.payments.index', compact('membership', 'payments'));
    }
    
    public function onlinePayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0|max:999999.99',
            'membership_type' => 'required|in:mensuel,trimestriel,semestriel,annuel',
        ]);

        $membershipTypes = [
            'mensuel' => 'Adhésion Mensuelle',
            'trimestriel' => 'Adhésion Trimestrielle', 
            'semestriel' => 'Adhésion Semestrielle',
            'annuel' => 'Adhésion Annuelle'
        ];

        Payment::create([
            'user_id' => Auth::id(),
            'payment_id' => 'PAY_' . strtoupper(uniqid()),
            'amount' => $validated['amount'],
            'currency' => 'TND',
            'status' => 'pending',
            'payment_method' => 'online',
            'payment_gateway' => 'online',
            'description' => $membershipTypes[$validated['membership_type']] . ' - Paiement en ligne',
        ]);

        return redirect()->route('member.payments.index')
            ->with('success', 'Paiement en ligne initié. En attente de validation.');
    }
}