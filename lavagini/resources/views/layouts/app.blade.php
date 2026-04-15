<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <li>
                    <form action="/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1rem;">Déconnexion</button>
                    </form>
                </li>
            @endguest
        </ul>
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

    @yield('scripts')
</body>
</html>
