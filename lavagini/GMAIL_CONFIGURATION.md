# Configuration Gmail pour l'envoi d'emails

## Problème
Gmail refuse l'authentification avec votre mot de passe normal pour des raisons de sécurité.

## Solution : Mot de passe d'application Gmail

### Étape 1 : Activer l'authentification à 2 facteurs

1. Allez sur https://myaccount.google.com/security
2. Cherchez "Validation en deux étapes"
3. Cliquez sur "Activer" si ce n'est pas déjà fait
4. Suivez les instructions (SMS ou application Google Authenticator)

### Étape 2 : Créer un mot de passe d'application

1. Retournez sur https://myaccount.google.com/security
2. Cherchez "Mots de passe des applications" (App passwords)
3. Si vous ne voyez pas cette option, assurez-vous que la validation en 2 étapes est activée
4. Cliquez sur "Mots de passe des applications"
5. Sélectionnez :
   - Application : **Autre (nom personnalisé)**
   - Nom : **LAVAGINI**
6. Cliquez sur "Générer"
7. **Copiez le mot de passe de 16 caractères** (format : xxxx xxxx xxxx xxxx)

### Étape 3 : Configurer Laravel

Ouvrez le fichier `.env` et modifiez :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=lavagini@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx  (collez le mot de passe d'application ici)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="lavagini@gmail.com"
MAIL_FROM_NAME="LAVAGINI"
```

**IMPORTANT** : Utilisez le mot de passe d'application (16 caractères), PAS votre mot de passe Gmail normal !

### Étape 4 : Redémarrer Laravel

```bash
php artisan config:clear
php artisan cache:clear
```

### Étape 5 : Tester

1. Allez sur `/forgot-password`
2. Entrez votre email
3. Vérifiez votre boîte mail

## Alternative : Mailtrap (Pour les tests)

Si vous ne voulez pas configurer Gmail maintenant, utilisez Mailtrap :

1. Créez un compte gratuit sur https://mailtrap.io
2. Allez dans "Email Testing" → "Inboxes" → "My Inbox"
3. Copiez les identifiants SMTP

Modifiez `.env` :

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

Avec Mailtrap, les emails n'arrivent pas vraiment, mais vous pouvez les voir sur mailtrap.io.

## Alternative : Mode Log (Sans configuration)

Pour tester sans configurer d'email :

```env
MAIL_MAILER=log
```

Les emails seront écrits dans `storage/logs/laravel.log`. Vous pourrez copier le lien de réinitialisation depuis le log.

## Vérification

Pour vérifier que votre configuration fonctionne :

```bash
php artisan tinker
```

Puis dans tinker :

```php
Mail::raw('Test email', function($message) {
    $message->to('votre_email@gmail.com')
            ->subject('Test LAVAGINI');
});
```

Si ça fonctionne, vous recevrez un email de test.

## Erreurs Courantes

### "Username and Password not accepted"
→ Vous utilisez votre mot de passe Gmail normal au lieu du mot de passe d'application

### "App passwords not available"
→ Activez d'abord l'authentification à 2 facteurs

### "Invalid credentials"
→ Vérifiez que vous avez bien copié les 16 caractères sans espaces supplémentaires

## Liens Utiles

- Sécurité Google : https://myaccount.google.com/security
- Mots de passe d'application : https://myaccount.google.com/apppasswords
- Documentation Gmail SMTP : https://support.google.com/mail/answer/7126229
