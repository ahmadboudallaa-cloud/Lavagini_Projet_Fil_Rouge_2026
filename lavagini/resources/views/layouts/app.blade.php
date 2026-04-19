<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - LAVAGINI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3498db;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #3498db;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 1.5rem;
        }

        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            width: 350px;
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            font-weight: bold;
            color: #2c3e50;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.3s;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: #e3f2fd;
        }

        .notification-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.3rem;
        }

        .notification-message {
            color: #666;
            font-size: 0.9rem;
        }

        .notification-time {
            color: #999;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .notification-empty {
            padding: 2rem;
            text-align: center;
            color: #999;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-success {
            background-color: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background-color: #229954;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="logo">LAVAGINI</div>
        <ul>
            @guest
                <li><a href="/">Accueil</a></li>
                <li><a href="/login">Connexion</a></li>
                <li><a href="/register">Inscription</a></li>
            @else
                <li><a href="/dashboard">Tableau de bord</a></li>
                <li><a href="/profil">Mon Profil</a></li>
                <li>
                    <div class="notification-icon" onclick="toggleNotifications()">
                        🔔
                        @php
                            $notificationsNonLues = auth()->user()->notifications()->where('lu', false)->count();
                        @endphp
                        @if($notificationsNonLues > 0)
                        <span class="notification-badge">{{ $notificationsNonLues }}</span>
                        @endif
                    </div>
                </li>
                <li>
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1rem;">Déconnexion</button>
                    </form>
                </li>
            @endguest
        </ul>
        
        @auth
        <div id="notificationDropdown" class="notification-dropdown">
            <div class="notification-header">Notifications</div>
            @php
                $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->limit(5)->get();
            @endphp
            @forelse($notifications as $notification)
            <div class="notification-item {{ !$notification->lu ? 'unread' : '' }}" onclick="markAsRead({{ $notification->id }})">
                <div class="notification-title">{{ $notification->titre }}</div>
                <div class="notification-message">{{ $notification->message }}</div>
                <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <div class="notification-empty">Aucune notification</div>
            @endforelse
        </div>
        @endauth
    </nav>

    <main>
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-error">{{ session('error') }}</div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 LAVAGINI - Tous droits réservés</p>
    </footer>

    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/lire`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            }).then(() => {
                location.reload();
            });
        }

        // Fermer le dropdown si on clique ailleurs
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const icon = document.querySelector('.notification-icon');
            if (dropdown && icon && !dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
