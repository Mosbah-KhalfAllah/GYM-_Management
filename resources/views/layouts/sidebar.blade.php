<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl border-r border-gray-200">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-dumbbell text-white text-xl"></i>
            </div>
            <span class="text-2xl font-black text-gray-800">GYM <span class="text-blue-600">PRO</span></span>
        </div>
    </div>



    <!-- Navigation -->
    <nav class="mt-6 px-3">
        @if(Auth::user()->role === 'admin')
            <!-- Admin Menu -->
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <br>
                <a href="{{ route('admin.members.index') }}" class="sidebar-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Membres</span>
                </a>
                <br>
                <a href="{{ route('admin.coaches.index') }}" class="sidebar-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Coachs</span>
                </a>
                <br>
                <a href="{{ route('admin.programs.index') }}" class="sidebar-link {{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Programmes</span>
                </a>
                <br>
                <a href="{{ route('admin.classes.index') }}" class="sidebar-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Cours</span>
                </a>
                <br>
                <a href="{{ route('admin.challenges.index') }}" class="sidebar-link {{ request()->routeIs('admin.challenges.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Défis</span>
                </a>
                <br>
                <a href="{{ route('admin.equipment.index') }}" class="sidebar-link {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                    <i class="fas fa-tools"></i>
                    <span>Équipements</span>
                </a>
                <br>
                <a href="{{ route('admin.attendance.index') }}" class="sidebar-link {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Présences</span>
                </a>
                <br>
                <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                </a>
                <br>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Rapports</span>
                </a>
                <br>
                <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    <span>Demandes</span>
                </a>
            </div>

        @elseif(Auth::user()->role === 'coach')
            <!-- Coach Menu -->
            <div class="space-y-2">
                <a href="{{ route('coach.dashboard') }}" class="sidebar-link {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <br>
                <a href="{{ route('coach.members.index') }}" class="sidebar-link {{ request()->routeIs('coach.members.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Mes Membres</span>
                </a>
                <br>
                <a href="{{ route('coach.programs.index') }}" class="sidebar-link {{ request()->routeIs('coach.programs.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Programmes</span>
                </a>
                <br>
                <a href="{{ route('coach.exercises.index') }}" class="sidebar-link {{ request()->routeIs('coach.exercises.*') ? 'active' : '' }}">
                    <i class="fas fa-dumbbell"></i>
                    <span>Exercices</span>
                </a>
                <br>
                <a href="{{ route('coach.classes.index') }}" class="sidebar-link {{ request()->routeIs('coach.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Mes Cours</span>
                </a>
                <br>
                <a href="{{ route('coach.schedule.index') }}" class="sidebar-link {{ request()->routeIs('coach.schedule.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span>Planning</span>
                </a>
                <br>
                <a href="{{ route('coach.attendance.index') }}" class="sidebar-link {{ request()->routeIs('coach.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Présences</span>
                </a>
            </div>

        @else
            <!-- Member Menu -->
            <div class="space-y-2">
                <a href="{{ route('member.dashboard') }}" class="sidebar-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <br>
                <a href="{{ route('member.program.index') }}" class="sidebar-link {{ request()->routeIs('member.program.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Mon Programme</span>
                </a>
                <br>
                <a href="{{ route('member.classes.index') }}" class="sidebar-link {{ request()->routeIs('member.classes.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Cours</span>
                </a>
                <br>
                <a href="{{ route('member.challenges.index') }}" class="sidebar-link {{ request()->routeIs('member.challenges.*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Défis</span>
                </a>
                <br>
                <a href="{{ route('member.attendance.index') }}" class="sidebar-link {{ request()->routeIs('member.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span>Mes Présences</span>
                </a>
                <br>
                <a href="{{ route('member.payments.index') }}" class="sidebar-link {{ request()->routeIs('member.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                </a>
                <br>
                <a href="{{ route('member.progress.index') }}" class="sidebar-link {{ request()->routeIs('member.progress.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Progrès</span>
                </a>
            </div>
        @endif
    </nav>

    <!-- Logout -->
    <div class="absolute bottom-0 left-0 right-0 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </div>
</div>

<style>
.sidebar-link {
    @apply flex items-center space-x-3 px-4 py-3 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all duration-200 group;
}

.sidebar-link.active {
    @apply text-white bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg;
}

.sidebar-link i {
    @apply w-5 text-center;
}

.sidebar-section {
    @apply mb-4;
}

.sidebar-section-title {
    @apply flex items-center space-x-3 px-4 py-2 text-gray-400 text-sm font-semibold uppercase tracking-wider;
}

.sidebar-submenu {
    @apply ml-4 space-y-1;
}

.sidebar-sublink {
    @apply flex items-center space-x-3 px-4 py-2 text-blue-200 hover:text-white hover:bg-blue-500 rounded-lg transition-all duration-200 text-sm;
}

.sidebar-sublink.active {
    @apply text-white bg-blue-500;
}

.sidebar-sublink i {
    @apply w-4 text-center text-xs;
}
</style>