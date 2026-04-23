# Système de Réinitialisation de Mot de Passe

## Fonctionnalités Implémentées

### 1. Demande de Réinitialisation
- Page `/forgot-password` avec formulaire email
- Génération d'un token unique sécurisé
- Stockage dans la table `password_reset_tokens`
- Envoi d'email avec lien de réinitialisation

### 2. Réinitialisation
- Page `/reset-password/{token}` avec formulaire
- Validation du token (existence, correspondance, expiration)
- Token valide pendant 60 minutes
- Mise à jour du mot de passe
- Suppression du token après utilisation

### 3. Sécurité
- Token hashé dans la base de données
- Expiration automatique après 60 minutes
- Suppression des anciens tokens avant création d'un nouveau
- Validation email + token + confirmation mot de passe

## Configuration Email

### Option 1 : Gmail (Recommandé pour les tests)

1. **Activer l'authentification à 2 facteurs** sur votre compte Gmail
2. **Créer un mot de passe d'application** :
   - Allez sur https://myaccount.google.com/security
   - Cliquez sur "Mots de passe des applications"
   - Sélectionnez "Autre" et nommez-le "LAVAGINI"
   - Copiez le mot de passe généré (16 caractères)

3. **Configurer `.env`** :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx  (mot de passe d'application)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre_email@gmail.com"
MAIL_FROM_NAME="LAVAGINI"
```

### Option 2 : Mailtrap (Pour les tests sans vrai email)

1. Créez un compte sur https://mailtrap.io
2. Copiez les identifiants SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre_username_mailtrap
MAIL_PASSWORD=votre_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lavagini.com"
MAIL_FROM_NAME="LAVAGINI"
```

### Option 3 : Mode Log (Emails dans les logs)

Si vous ne voulez pas configurer d'email pour l'instant :

```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log`

## Flux Utilisateur

1. **Utilisateur oublie son mot de passe**
   - Va sur `/login`
   - Clique sur "Mot de passe oublié ?"
   - Redirigé vers `/forgot-password`

2. **Demande de réinitialisation**
   - Entre son email
   - Soumet le formulaire
   - Reçoit un email avec un lien

3. **Réinitialisation**
   - Clique sur le lien dans l'email
   - Redirigé vers `/reset-password/{token}?email=...`
   - Entre nouveau mot de passe + confirmation
   - Soumet le formulaire
   - Redirigé vers `/login` avec message de succès

## Fichiers Créés/Modifiés

### Contrôleur
- `app/Http/Controllers/Web/WebAuthController.php`
  - `showForgotPassword()` - Affiche formulaire demande
  - `sendResetLink()` - Envoie email avec token
  - `showResetPassword()` - Affiche formulaire réinitialisation
  - `resetPassword()` - Traite la réinitialisation

### Vues
- `resources/views/auth/forgot-password.blade.php` - Formulaire demande
- `resources/views/auth/reset-password.blade.php` - Formulaire nouveau mot de passe
- `resources/views/emails/reset-password.blade.php` - Template email
- `resources/views/auth/login.blade.php` - Ajout lien "Mot de passe oublié"

### Routes
- `GET /forgot-password` - Afficher formulaire demande
- `POST /forgot-password` - Envoyer email
- `GET /reset-password/{token}` - Afficher formulaire réinitialisation
- `POST /reset-password` - Traiter réinitialisation

### Base de données
- Table `password_reset_tokens` (déjà existante)
  - `email` (primary key)
  - `token` (hashé)
  - `created_at`

## Messages d'Erreur

- "Aucun compte trouvé avec cet email" - Email n'existe pas
- "Token invalide ou expiré" - Token introuvable
- "Token invalide" - Token ne correspond pas
- "Ce lien a expiré" - Plus de 60 minutes
- "Erreur lors de l'envoi de l'email" - Problème SMTP

## Messages de Succès

- "✅ Un lien de réinitialisation a été envoyé à votre email"
- "✅ Votre mot de passe a été réinitialisé avec succès !"

## Tests

### Test complet avec Gmail
1. Configurez Gmail dans `.env`
2. Redémarrez : `php artisan config:clear`
3. Allez sur `/forgot-password`
4. Entrez votre email
5. Vérifiez votre boîte mail
6. Cliquez sur le lien
7. Créez un nouveau mot de passe
8. Connectez-vous avec le nouveau mot de passe

### Test avec Mailtrap
1. Configurez Mailtrap dans `.env`
2. Même processus
3. Vérifiez les emails sur mailtrap.io

### Test avec Log
1. Configurez `MAIL_MAILER=log`
2. Faites une demande
3. Vérifiez `storage/logs/laravel.log`
4. Copiez le lien du log
5. Testez la réinitialisation

## Sécurité

✅ Token hashé en base de données
✅ Expiration après 60 minutes
✅ Suppression après utilisation
✅ Validation email + token
✅ Confirmation mot de passe
✅ Minimum 6 caractères

## Personnalisation

### Changer la durée d'expiration
Dans `WebAuthController::resetPassword()`, ligne :
```php
if (now()->diffInMinutes($resetRecord->created_at) > 60) {
```
Changez `60` par le nombre de minutes souhaité.

### Changer le design de l'email
Modifiez `resources/views/emails/reset-password.blade.php`

### Changer les messages
Modifiez les messages dans `WebAuthController.php`
