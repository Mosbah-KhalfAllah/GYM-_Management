{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord Admin')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Membres</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_members'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $stats['active_members'] }} actifs
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Today's Attendance -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Présences Aujourd'hui</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['today_attendance'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-clock mr-1"></i> Temps réel
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Revenu Mensuel</p>
                    <p class="text-3xl font-bold mt-2">${{ number_format($stats['monthly_revenue'], 2) }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-chart-line mr-1"></i> Performance
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Equipment Status -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Équipements</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['equipment_issues'] }}</p>
                    <p class="text-sm mt-2 opacity-90">
                        <i class="fas fa-tools mr-1"></i> En maintenance
                    </p>
                </div>
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-dumbbell text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Présences (7 derniers jours)</h3>
                <select class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Cette semaine</option>
                    <option>Ce mois</option>
                    <option>Cette année</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Revenus (12 derniers mois)</h3>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    +12.5%
                </span>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Membership Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Membership Distribution -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Statut des Adhésions</h3>
            <div class="h-64">
                <canvas id="membershipChart"></canvas>
            </div>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Présences Récentes</h3>
                <a href="{{ route('admin.attendance.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Voir tout
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure d'entrée</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durée</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentAttendances as $attendance)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ substr($attendance->user->first_name, 0, 1) }}{{ substr($attendance->user->last_name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user->membership->type ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $attendance->check_in->format('H:i') }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendance->check_in->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $attendance->entry_method === 'qr_code' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $attendance->entry_method === 'qr_code' ? 'QR Code' : 'Manuel' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($attendance->duration_minutes)
                                        {{ $attendance->duration_minutes }} min
                                    @else
                                        En cours
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Actions Rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.members.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 transform hover:-translate-y-1">
                <i class="fas fa-user-plus text-2xl mb-2"></i>
                <span class="font-medium">Ajouter un membre</span>
            </a>
            <a href="{{ route('admin.attendance.scanner') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 transform hover:-translate-y-1">
                <i class="fas fa-qrcode text-2xl mb-2"></i>
                <span class="font-medium">Scanner QR Code</span>
            </a>
            <a href="{{ route('admin.classes.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 transform hover:-translate-y-1">
                <i class="fas fa-calendar-plus text-2xl mb-2"></i>
                <span class="font-medium">Créer un cours</span>
            </a>
            <a href="{{ route('admin.equipment.create') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg p-4 flex flex-col items-center justify-center transition-all duration-200 transform hover:-translate-y-1">
                <i class="fas fa-dumbbell text-2xl mb-2"></i>
                <span class="font-medium">Ajouter équipement</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(attendanceCtx, {
        type: 'line',
        data: {
            labels: @json(array_column($attendanceData, 'date')),
            datasets: [{
                label: 'Présences',
                data: @json(array_column($attendanceData, 'count')),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json(array_column($revenueData, 'month')),
            datasets: [{
                label: 'Revenus ($)',
                data: @json(array_column($revenueData, 'revenue')),
                backgroundColor: [
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(124, 58, 237, 0.7)',
                    'rgba(109, 40, 217, 0.7)',
                    'rgba(91, 33, 182, 0.7)',
                    'rgba(76, 29, 149, 0.7)',
                    'rgba(67, 56, 202, 0.7)'
                ],
                borderColor: [
                    'rgb(139, 92, 246)',
                    'rgb(124, 58, 237)',
                    'rgb(109, 40, 217)',
                    'rgb(91, 33, 182)',
                    'rgb(76, 29, 149)',
                    'rgb(67, 56, 202)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Membership Chart
    const membershipCtx = document.getElementById('membershipChart').getContext('2d');
    const membershipChart = new Chart(membershipCtx, {
        type: 'doughnut',
        data: {
            labels: ['Actif', 'Expiré', 'Annulé', 'En attente'],
            datasets: [{
                data: [
                    {{ $membershipData['active'] }},
                    {{ $membershipData['expired'] }},
                    {{ $membershipData['cancelled'] }},
                    {{ $membershipData['pending'] }}
                ],
                backgroundColor: [
                    '#10B981',
                    '#EF4444',
                    '#F59E0B',
                    '#6B7280'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection