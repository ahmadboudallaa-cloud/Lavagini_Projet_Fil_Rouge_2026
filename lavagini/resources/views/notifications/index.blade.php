<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            padding: 2rem;
        }

        .notifications-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .notifications-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notifications-header h2 {
            color: #2c3e50;
        }

        .btn-marquer-tout {
            padding: 0.5rem 1rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-marquer-tout:hover {
            background: #2980b9;
        }

        .notification-item {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.non-lue {
            background: #e3f2fd;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .notification-titre {
            font-weight: bold;
            color: #2c3e50;
        }

        .notification-date {
            font-size: 0.85rem;
            color: #999;
        }

        .notification-message {
            color: #555;
            margin-bottom: 0.5rem;
        }

        .notification-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .badge-commande {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-mission {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .badge-paiement {
            background: #e8f5e9;
            color: #388e3c;
        }

        .badge-evaluation {
            background: #fff3e0;
            color: #f57c00;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #999;
        }

        .btn-retour {
            display: inline-block;
            margin: 1rem;
            padding: 0.8rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-retour:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <a href="/dashboard" class="btn-retour">← Retour au Dashboard</a>

    <div class="notifications-container">
        <div class="notifications-header">
            <h2>Mes Notifications</h2>
            @if($notifications->where('lu', false)->count() > 0)
            <button class="btn-marquer-tout" onclick="marquerToutCommeLu()">Tout marquer comme lu</button>
            @endif
        </div>

        @forelse($notifications as $notification)
        <div class="notification-item {{ !$notification->lu ? 'non-lue' : '' }}" id="notif-{{ $notification->id }}">
            <div class="notification-header">
                <span class="notification-titre">{{ $notification->titre }}</span>
                <span class="notification-date">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            <p class="notification-message">{{ $notification->message }}</p>
            <span class="notification-badge badge-{{ $notification->type }}">{{ ucfirst($notification->type) }}</span>
            @if(!$notification->lu)
            <button onclick="marquerCommeLue({{ $notification->id }})" style="margin-left: 1rem; padding: 0.3rem 0.8rem; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer;">Marquer comme lu</button>
            @endif
        </div>
        @empty
        <div class="empty-state">
            <p>Aucune notification pour le moment</p>
        </div>
        @endforelse
    </div>

    <script>
        function marquerCommeLue(id) {
            fetch(`/notifications/${id}/lire`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            });
        }

        function marquerToutCommeLu() {
            fetch('/notifications/marquer-tout-lu', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            });
        }
    </script>
</body>
</html>
