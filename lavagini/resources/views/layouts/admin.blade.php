<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - LAVAGINI Admin</title>
    
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
    
    <style>
        body { background-color: #000000; color: #ffffff; }
        
        /* CSS pour les notifications et dropdown (conservé de ton code original) */
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
        
        /* Gestion des onglets du dashboard */
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Active Sidebar Item Styling (collé à gauche, arrondi à droite) */
        .menu-item.active {
            background-color: #00C2FF;
            color: #ffffff;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>
<body class="flex min-h-screen font-sans overflow-x-hidden">

    <aside class="w-[280px] bg-dark-card fixed h-full z-50 flex flex-col py-6">
        <div class="flex flex-col items-center mb-8 mt-4">
    <div class="w-32 h-32 mb-3 flex items-center justify-center">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo Lavagini" class="w-full h-full object-contain">
    </div>
    
</div>

        <nav class="flex flex-col w-full pr-4 space-y-1">
            <a href="#" onclick="event.preventDefault(); showTab('commandes', this);" class="menu-item active py-3 pl-8 text-lg text-gray-300 hover:text-white transition">commandes</a>
            <a href="#" onclick="event.preventDefault(); showTab('missions', this);" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition">Missions</a>
            <a href="#" onclick="event.preventDefault(); showTab('clients', this);" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition">Clients</a>
            <a href="#" onclick="event.preventDefault(); showTab('laveurs', this);" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition">Laveurs</a>
            <a href="#" onclick="event.preventDefault(); showTab('zones', this);" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition">Zones</a>
            <a href="#" onclick="event.preventDefault(); showTab('parametres', this);" class="menu-item py-3 pl-8 text-lg text-gray-300 hover:text-white transition mt-4">Paramètres</a>
        </nav>
    </aside>

    <main class="flex-1 ml-[280px] bg-dark-bg min-h-screen flex flex-col">
        
        <header class="flex justify-between items-center px-8 py-4 bg-[#2b2b2b]">
            <h3 class="text-gray-300 text-xl font-normal tracking-wide">Dashboard Admin</h3>
            
            <div class="flex items-center space-x-6">
                <div class="relative cursor-pointer notification-icon flex items-center" onclick="toggleNotifications(event)">
                    <svg class="w-7 h-7 text-white hover:text-cyan-custom transition" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                    </svg>
                    
                    @php $notificationsNonLues = auth()->user()->notifications()->where('lu', false)->count() ?? 0; @endphp
                    @if($notificationsNonLues > 0)
                        <span class="absolute -top-1 -right-1 bg-cyan-custom text-black text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $notificationsNonLues }}</span>
                    @endif
                    
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                            <h4 class="text-white font-bold">Notifications</h4>
                        </div>
                        <div class="p-4 text-center text-gray-400 text-sm">Aucune nouvelle notification</div>
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
                    <span class="text-white font-normal text-lg">{{ auth()->user()->name ?? 'Admin Lavagini' }}</span>
                    
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

        <div class="px-10 py-6">
            @if(session('success'))
                <div class="bg-cyan-custom/20 border border-cyan-custom text-cyan-custom px-4 py-3 rounded-xl mb-6">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-500 px-4 py-3 rounded-xl mb-6">❌ {{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        function toggleUserMenu() { document.getElementById('userDropdown').classList.toggle('show'); }
        function toggleNotifications(event) {
            event.stopPropagation();
            document.getElementById('notificationDropdown').classList.toggle('show');
        }
        
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.user-menu')) document.getElementById('userDropdown').classList.remove('show');
            if (!event.target.closest('.notification-icon')) document.getElementById('notificationDropdown').classList.remove('show');
        });

        // Logique de navigation par le menu latéral
        function showTab(tabName, element) {
            // Cacher tous les contenus
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            // Enlever la classe active de tous les liens du menu
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
            
            // Activer le contenu et le lien cliqué
            document.getElementById(tabName).classList.add('active');
            if(element) element.classList.add('active');
            
            localStorage.setItem('activeTab', tabName);
        }

        // Restauration au rechargement
        window.addEventListener('DOMContentLoaded', () => {
            const activeTab = localStorage.getItem('activeTab') || 'commandes';
            const tabContent = document.getElementById(activeTab);
            if (tabContent) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
                tabContent.classList.add('active');
                
                // Mettre à jour le menu latéral
                document.querySelectorAll('.menu-item').forEach(el => {
                    el.classList.remove('active');
                    if(el.getAttribute('onclick') && el.getAttribute('onclick').includes(activeTab)) {
                        el.classList.add('active');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>