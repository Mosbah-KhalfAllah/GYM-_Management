{{-- resources/views/layouts/navigation.blade.php --}}
<nav class="sticky top-0 z-40 bg-white shadow-md">
    <div class="flex items-center justify-between h-16 px-6">
        <!-- Left Section -->
        <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Tableau de bord')</h1>
        </div>

        <!-- Right Section -->
        <div class="flex items-center space-x-6">
            <!-- Notifications -->
            <div class="relative">
                <button id="notificationBtn" class="relative p-2 text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border z-50">
                    <div class="p-4 border-b">
                        <h3 class="font-semibold text-gray-800">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notification Items -->
                        @if(Auth::user()->notifications && Auth::user()->notifications()->count() > 0)
                            @foreach(Auth::user()->notifications()->latest()->take(5)->get() as $notification)
                                <a href="#" class="block p-4 border-b hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            @if($notification->type === 'membership')
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-id-card text-blue-600"></i>
                                                </div>
                                            @elseif($notification->type === 'class')
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar text-green-600"></i>
                                                </div>
                                            @elseif($notification->type === 'challenge')
                                                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-trophy text-yellow-600"></i>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-bell text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-gray-800">{{ $notification->message }}</p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="p-8 text-center">
                                <i class="fas fa-bell-slash text-gray-300 text-3xl mb-2"></i>
                                <p class="text-gray-500">Aucune notification</p>
                            </div>
                        @endif
                    </div>
                    <div class="p-4 border-t">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center space-x-3 focus:outline-none">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->full_name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border z-50">
                    <div class="p-4 border-b">
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-2">
                        {{-- Lien temporaire pour Modifier le profil --}}
                        <a href="#" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                           onclick="event.preventDefault(); alert('Fonctionnalité "Modifier le profil" à venir');">
                            <i class="fas fa-user-edit mr-2"></i>Modifier le profil
                        </a>
                        
                        {{-- Lien temporaire pour Changer le mot de passe --}}
                        <a href="#" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                           onclick="event.preventDefault(); alert('Fonctionnalité "Changer le mot de passe" à venir');">
                            <i class="fas fa-key mr-2"></i>Changer le mot de passe
                        </a>
                        
                        <div class="border-t my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Toggle notifications dropdown
    document.getElementById('notificationBtn')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
        
        // Close user dropdown if open
        document.getElementById('userDropdown')?.classList.add('hidden');
    });

    // Toggle user dropdown
    document.getElementById('userMenuBtn')?.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
        
        // Close notifications dropdown if open
        document.getElementById('notificationDropdown')?.classList.add('hidden');
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const notificationDropdown = document.getElementById('notificationDropdown');
        const userDropdown = document.getElementById('userDropdown');
        
        if (notificationDropdown) notificationDropdown.classList.add('hidden');
        if (userDropdown) userDropdown.classList.add('hidden');
    });

    // Prevent dropdowns from closing when clicking inside them
    document.querySelectorAll('#notificationDropdown, #userDropdown').forEach(dropdown => {
        dropdown?.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>