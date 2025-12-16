<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Paiements</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .period { font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .stats { margin-bottom: 20px; }
        .stat-item { display: inline-block; margin-right: 30px; }
        .amount { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport des Paiements</div>
        <div class="period">Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    </div>

    <div class="stats">
        <div class="stat-item"><strong>Total revenus:</strong> {{ number_format($totalRevenue, 2) }} DT</div>
        <div class="stat-item"><strong>Nombre paiements:</strong> {{ $payments->count() }}</div>
        <div class="stat-item"><strong>Moyenne:</strong> {{ $payments->count() > 0 ? number_format($totalRevenue / $payments->count(), 2) : '0.00' }} DT</div>
    </div>

    @if($payments->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Email</th>
                    <th>Montant</th>
                    <th>Méthode</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->user->first_name }} {{ $payment->user->last_name }}</td>
                    <td>{{ $payment->user->email }}</td>
                    <td class="amount">{{ number_format($payment->amount, 2) }} DT</td>
                    <td>{{ $payment->method_label }}</td>
                    <td>{{ $payment->status_label }}</td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun paiement trouvé pour cette période.</p>
    @endif
</body>
</html>