{{-- resources/views/layouts/sidebar.blade.php --}}
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-gray-900 to-gray-800 text-white shadow-xl">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gray-900">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-dumbbell text-lg"></i>
            </div>
            <span class="text-xl font-bold tracking-tight">GYM PRO</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        @if(Auth::user()->role === 'admin')
            <!-- Admin Navigation -->
            <div class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a><br>
                <a href="{{ route('admin.members.index') }}" class="sidebar-item {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Membres</span>
                </a><br>
                <a href="{{ route('admin.coaches.index') }}" class="sidebar-item {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Coachs</span>
                </a><br>
                <a href="{{ route('admin.programs.index') }}" class="sidebar-item {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                    <i class="fas fa-running"></i>
                    <span>Programmes</span>
                </a><br>
                <a href="{{ route('admin.classes.index') }}" class="sidebar-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Cours collectifs</span>
                </a><br>
                <a href="{{ route('admin.attendance.index') }}" class="sidebar-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i>
                    <span>Présences</span>
                </a><br>
                <a href="{{ route('admin.equipment.index') }}" class="sidebar-item {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                    <i class="fas fa-dumbbell"></i>
                    <span>Équipements</span>
                </a><br>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                </a><br>
                <a href="{{ route('admin.challenges.index') }}" class="sidebar-item {{ request()->routeIs('admin.challenges.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Challenges</span>
                </a><br>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Rapports</span>
                </a><br>
                <a href="{{ route('admin.inquiries.index') }}" class="sidebar-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>Demandes</span>
                </a><br>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>
        @elseif(Auth::user()->role === 'coach')
            <!-- Coach Navigation -->
            <div class="space-y-2">
                <a href="{{ route('coach.dashboard') }}" class="sidebar-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a><br>
                <a href="{{ route('coach.members.index') }}" class="sidebar-item {{ request()->routeIs('coach.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Mes Membres</span>
                </a><br>
                <a href="{{ route('coach.programs.index') }}" class="sidebar-item {{ request()->routeIs('coach.programs.*') ? 'active' : '' }}">
                    <i class="fas fa-running"></i>
                    <span>Mes Programmes</span>
                </a><br>
                <a href="{{ route('coach.classes.index') }}" class="sidebar-item {{ request()->routeIs('coach.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Mes Cours</span>
                </a><br>
                <a href="{{ route('coach.attendance.index') }}" class="sidebar-item {{ request()->routeIs('coach.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-door-open"></i>
                    <span>Présences</span>
                </a><br>
                <a href="{{ route('coach.exercises.index') }}" class="sidebar-item {{ request()->routeIs('coach.exercises.*') ? 'active' : '' }}">
                    <i class="fas fa-dumbbell"></i>
                    <span>Exercices</span>
                </a><br>
                <a href="{{ route('coach.schedule.index') }}" class="sidebar-item {{ request()->routeIs('coach.schedule.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Emploi du temps</span>
                </a>
            </div>
        @elseif(Auth::user()->role === 'member')
            <!-- Member Navigation -->
            <div class="space-y-2">
                <a href="{{ route('member.dashboard') }}" class="sidebar-item {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a><br>
                <a href="{{ route('member.program.index') }}" class="sidebar-item {{ request()->routeIs('member.program.*') ? 'active' : '' }}">
                    <i class="fas fa-running"></i>
                    <span>Mon Programme</span>
                </a><br>
                <a href="{{ route('member.workout.index') }}" class="sidebar-item {{ request()->routeIs('member.workout.*') ? 'active' : '' }}">
                    <i class="fas fa-dumbbell"></i>
                    <span>Séance du jour</span>
                </a><br>
                <a href="{{ route('member.classes.index') }}" class="sidebar-item {{ request()->routeIs('member.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Cours collectifs</span>
                </a><br>
                <a href="{{ route('member.attendance.index') }}" class="sidebar-item {{ request()->routeIs('member.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Mes présences</span>
                </a><br>
                <a href="{{ route('member.challenges.index') }}" class="sidebar-item {{ request()->routeIs('member.challenges.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Challenges</span>
                </a><br>
                <a href="{{ route('member.progress.index') }}" class="sidebar-item {{ request()->routeIs('member.progress.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Progression</span>
                </a><br>
                <a href="{{ route('member.profile.index') }}" class="sidebar-item {{ request()->routeIs('member.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Mon Profil</span>
                </a>
            </div>
        @endif

        <!-- Logout -->
        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gray-900">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-item hover:bg-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<style>
    .sidebar-item {
        @apply flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 hover:pl-6;
    }
    .sidebar-item.active {
        @apply bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg;
    }
    .sidebar-item i {
        @apply w-5 text-center;
    }
</style>
