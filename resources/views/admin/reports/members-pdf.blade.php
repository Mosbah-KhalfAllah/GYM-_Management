<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Membres</title>
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
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport des Membres</div>
        <div class="period">Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    </div>

    <div class="stats">
        <div class="stat-item"><strong>Total membres:</strong> {{ $members->count() }}</div>
        <div class="stat-item"><strong>Membres actifs:</strong> {{ $members->where('membership.status', 'active')->count() }}</div>
        <div class="stat-item"><strong>Adhésions expirées:</strong> {{ $members->where('membership.status', 'expired')->count() }}</div>
    </div>

    @if($members->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr>
                    <td>{{ $member->last_name }}</td>
                    <td>{{ $member->first_name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone ?? 'N/A' }}</td>
                    <td>{{ $member->membership ? ucfirst($member->membership->status) : 'Aucune adhésion' }}</td>
                    <td>{{ $member->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucun membre trouvé pour cette période.</p>
    @endif
</body>
</html>