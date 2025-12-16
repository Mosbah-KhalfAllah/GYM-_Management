<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport Présences</title>
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
        .time { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport des Présences</div>
        <div class="period">Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    </div>

    <div class="stats">
        <div class="stat-item"><strong>Total présences:</strong> {{ $attendances->count() }}</div>
        <div class="stat-item"><strong>Membres uniques:</strong> {{ $attendances->unique('user_id')->count() }}</div>
        <div class="stat-item"><strong>Moyenne/jour:</strong> {{ $dailyStats->count() > 0 ? number_format($attendances->count() / $dailyStats->count(), 1) : '0' }}</div>
    </div>

    @if($attendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Email</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Durée</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->first_name }} {{ $attendance->user->last_name }}</td>
                    <td>{{ $attendance->user->email }}</td>
                    <td class="time">{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</td>
                    <td class="time">{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : 'En cours' }}</td>
                    <td class="time">
                        @if($attendance->check_out)
                            {{ \Carbon\Carbon::parse($attendance->check_in)->diffForHumans(\Carbon\Carbon::parse($attendance->check_out), true) }}
                        @else
                            En cours
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($attendance->check_in)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Aucune présence trouvée pour cette période.</p>
    @endif
</body>
</html>