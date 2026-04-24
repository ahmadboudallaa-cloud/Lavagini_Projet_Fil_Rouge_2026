<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - LAVAGINI Laveur</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cyan: { custom: '#00C2FF' },
                        dark: { bg: '#000000', card: '#333333', hover: '#404040' }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { background-color: #000000; color: #ffffff; }

        .notification-dropdown, .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .notification-dropdown.show, .user-dropdown.show { display: block; }
        .notification-dropdown { width: 350px; max-height: 400px; overflow-y: auto; margin-top: 0.5rem; }
        .user-dropdown { min-width: 200px; margin-top: 0.5rem; }
        .notification-dropdown-item { padding: 1rem; border-bottom: 1px solid #2a2a2a; cursor: pointer; }
        .notification-dropdown-item:hover { background: #2a2a2a; }
        .notification-dropdown-item.non-lue { background: rgba(0, 194, 255, 0.1); }

        /* Actif Sidebar Item */
        .menu-item.active {
            background-color: #00C2FF;
            color: #ffffff;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            font-weight: 600;
        }
        
        /* Burger Menu */
        .burger-btn {
            display: none;
            z-index: 100;
            background: #333;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
        }
        
        .burger-btn span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: #00C2FF;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .burger-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(7px, 7px);
        }
        
        .burger-btn.active span:nth-child(2) {
            opacity: 0;
        }
        
        .burger-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        aside {
            transition: transform 0.3s ease;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            aside {
                width: 220px !important;
            }
            
            main {
                margin-left: 220px !important;
            }
            
            aside nav a {
                font-size: 1rem !important;
                padding-left: 1.5rem !important;
            }
        }
        
        @media (max-width: 768px) {
            .burger-btn {
                display: flex !important;
            }
            
            aside {
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                width: 280px !important;
                height: 100vh !important;
                transform: translateX(-100%) !important;
                z-index: 9999 !important;
                box-shadow: 2px 0 10px rgba(0,0,0,0.5) !important;
            }
            
            aside.active {
                transform: translateX(0) !important;
            }
            
            main {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            header {
                padding: 1rem !important;
                padding-left: 1rem !important;
            }
            
            header h3 {
                font-size: 0.95rem !important;
            }
            
            header .user-menu span {
                display: none !important;
            }
            
            .px-10 {
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
            
            .notification-dropdown {
                width: 280px !important;
                right: 0 !important;
            }
            
            aside nav a {
                font-size: 1.1rem !important;
                padding-left: 2rem !important;
            }
        }
        
        @media (max-width: 640px) {
            header {
                padding: 0.75rem !important;
                padding-left: 0.75rem !important;
            }
            
            header h3 {
                font-size: 0.85rem !important;
            }
            
            .px-10 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            
            .notification-dropdown {
                width: 250px !important;
                right: -20px !important;
            }
        }
        
        @media (max-width: 480px) {
            .burger-btn {
                top: 15px !important;
                left: 15px !important;
                width: 40px !important;
                height: 40px !important;
            }
            
            aside {
                width: 260px !important;
            }
            
            header {
                padding: 0.5rem !important;
                padding-left: 0.5rem !important;
            }
            
            header h3 {
                font-size: 0.8rem !important;
            }
            
            .notification-dropdown {
                width: calc(100vw - 40px) !important;
                right: -100px !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="flex min-h-screen font-sans overflow-x-hidden">

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <aside class="w-[280px] bg-dark-card fixed h-full z-50 flex flex-col py-6" id="sidebar">
        <div class="flex flex-col items-center mb-8 mt-4">
            <div class="w-32 h-32 mb-3 flex items-center justify-center">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo Lavagini" class="w-full h-full object-contain">
            </div>
        </div>

        <nav class="flex flex-col w-full pr-4 space-y-2">
            <a href="/laveur/dashboard" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition {{ request()->is('laveur/dashboard') ? 'active' : '' }}">Mission</a>
            <a href="/chat" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition {{ request()->is('chat*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i> Messagerie
            </a>
            <a href="/profil" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition mt-4 {{ request()->is('profil') ? 'active' : '' }}">Mon Profil</a>
        </nav>
    </aside>

    <main class="flex-1 ml-[280px] bg-dark-bg min-h-screen flex flex-col">
        <header class="flex justify-between items-center px-8 py-4 bg-[#2b2b2b] sticky top-0 z-40">
            <div class="flex items-center space-x-4">
                <button class="burger-btn" id="burgerBtn" onclick="toggleSidebar()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <h3 class="text-gray-300 text-xl font-normal tracking-wide">@yield('page-title', 'Dashboard Laveur')</h3>
            </div>

            <div class="flex items-center space-x-6">
                <div class="relative cursor-pointer notification-icon flex items-center" onclick="toggleNotifications(event)">
                    <svg class="w-7 h-7 text-white hover:text-cyan-custom transition" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>

                    @php
                        $notificationsNonLues = auth()->user()->notifications()->where('lu', false)->count() ?? 0;
                        $dernieresNotifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->limit(5)->get();
                    @endphp

                    @if($notificationsNonLues > 0)
                        <span class="absolute -top-1 -right-1 bg-cyan-custom text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $notificationsNonLues }}</span>
                    @endif

                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                            <h4 class="text-white font-bold">Notifications</h4>
                            @if($notificationsNonLues > 0)
                                <button onclick="marquerToutCommeLu(event)" class="bg-cyan-custom text-black px-3 py-1 rounded-full text-xs font-bold">Tout marquer</button>
                            @endif
                        </div>

                        @forelse($dernieresNotifications as $notif)
                            <div class="notification-dropdown-item {{ !$notif->lu ? 'non-lue' : '' }}" onclick="marquerCommeLue(event, {{ $notif->id }})">
                                <div class="font-bold text-white mb-1">{{ $notif->titre }}</div>
                                <div class="text-sm text-gray-400 mb-1">{{ Str::limit($notif->message, 60) }}</div>
                                <div class="text-xs text-gray-500">{{ $notif->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-400 text-sm">Aucune notification</div>
                        @endforelse
                    </div>
                </div>

                <div class="relative flex items-center space-x-2 cursor-pointer user-menu" onclick="toggleUserMenu()">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center overflow-hidden">
                        @if(auth()->user()->photo_profile)
                            <img src="{{ asset('uploads/profiles/' . auth()->user()->photo_profile) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-9 h-9 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                            </svg>
                        @endif
                    </div>
                    <span class="text-white font-normal text-lg">{{ auth()->user()->name }}</span>

                    <div class="user-dropdown" id="userDropdown">
                        <a href="/profil" class="block px-4 py-3 text-white hover:bg-gray-700">Mon Profil</a>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-red-500 hover:bg-gray-700">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="px-10 py-8" style="{{ request()->is('chat*') ? 'padding: 20px 40px !important;' : '' }}">
            @if(session('success'))
                <div class="bg-cyan-custom/20 border border-cyan-custom text-cyan-custom px-4 py-3 rounded-xl mb-6 font-medium">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-xl mb-6 font-medium">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Burger Menu Functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const burgerBtn = document.getElementById('burgerBtn');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            burgerBtn.classList.toggle('active');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const burgerBtn = document.getElementById('burgerBtn');
            
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            burgerBtn.classList.remove('active');
        }
        
        // Close sidebar when clicking on menu item on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        });
        
        function toggleUserMenu() { document.getElementById('userDropdown').classList.toggle('show'); }
        function toggleNotifications(event) {
            event.stopPropagation();
            document.getElementById('notificationDropdown').classList.toggle('show');
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.user-menu')) document.getElementById('userDropdown').classList.remove('show');
            if (!event.target.closest('.notification-icon')) document.getElementById('notificationDropdown').classList.remove('show');
        });

        function marquerCommeLue(event, id) {
            event.stopPropagation();
            fetch(`/notifications/${id}/lire`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }

        function marquerToutCommeLu(event) {
            event.stopPropagation();
            fetch('/notifications/marquer-tout-lu', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => location.reload());
        }
    </script>
    @yield('scripts')
</body>
</html>
