# Guide Configuration Stripe

## Étapes pour obtenir vos clés Stripe

### 1. Créer un compte Stripe
- Allez sur : https://dashboard.stripe.com/register
- Inscrivez-vous gratuitement (pas besoin de carte bancaire pour le mode test)

### 2. Obtenir les clés API
- Connectez-vous à votre dashboard Stripe
- Allez dans **Developers** → **API keys**
- Vous verrez deux clés :
  - **Publishable key** (commence par `pk_test_...`)
  - **Secret key** (commence par `sk_test_...`) - Cliquez sur "Reveal test key"

### 3. Configurer dans Laravel
Ouvrez le fichier `.env` et remplacez :

```env
STRIPE_KEY=pk_test_votre_cle_publique_stripe
STRIPE_SECRET=sk_test_votre_cle_secrete_stripe
```

Par vos vraies clés :

```env
STRIPE_KEY=pk_test_51Abc123...
STRIPE_SECRET=sk_test_51Abc123...
```

### 4. Redémarrer le serveur
Après modification du `.env`, redémarrez votre serveur Laravel :
```bash
php artisan config:clear
php artisan serve
```

## Cartes de test Stripe

Une fois configuré, utilisez ces cartes pour tester :

| Carte | Numéro | Résultat |
|-------|--------|----------|
| Succès | 4242 4242 4242 4242 | Paiement réussi |
| Échec | 4000 0000 0000 0002 | Carte déclinée |
| 3D Secure | 4000 0027 6000 3184 | Authentification requise |

**Autres infos** :
- Date d'expiration : N'importe quelle date future (ex: 12/25)
- CVC : N'importe quel 3 chiffres (ex: 123)
- Code postal : N'importe lequel (ex: 12345)

## Mode Production (Plus tard)

Quand vous serez prêt pour la production :
1. Activez votre compte Stripe (vérification d'identité)
2. Utilisez les clés **Live** (commencent par `pk_live_` et `sk_live_`)
3. Changez `STRIPE_KEY` et `STRIPE_SECRET` dans `.env`

## Liens utiles

- Dashboard Stripe : https://dashboard.stripe.com
- Documentation : https://stripe.com/docs
- Cartes de test : https://stripe.com/docs/testing
