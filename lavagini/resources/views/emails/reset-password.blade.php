<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .content {
            padding: 2rem;
        }
        .button {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 1rem 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 1rem;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 LAVAGINI</h1>
            <p>Réinitialisation de mot de passe</p>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
            
            <div style="text-align: center; margin: 2rem 0;">
                <a href="{{ url('/reset-password/' . $token . '?email=' . $email) }}" class="button">
                    Réinitialiser mon mot de passe
                </a>
            </div>

            <p><strong>Ce lien est valable pendant 60 minutes.</strong></p>
            
            <p>Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.</p>
            
            <p style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee; color: #666; font-size: 0.9rem;">
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
                <a href="{{ url('/reset-password/' . $token . '?email=' . $email) }}">{{ url('/reset-password/' . $token . '?email=' . $email) }}</a>
            </p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} LAVAGINI - Service de lavage de véhicules</p>
        </div>
    </div>
</body>
</html>
