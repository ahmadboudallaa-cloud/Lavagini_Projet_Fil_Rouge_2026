<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #3498db;
            margin: 0;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-section h3 {
            background-color: #3498db;
            color: white;
            padding: 10px;
            margin: 0 0 10px 0;
        }
        .info-row {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .info-row strong {
            display: inline-block;
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background-color: #3498db;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            color: #27ae60;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAVAGINI</h1>
        <p>Service de lavage de véhicules à domicile</p>
        <h2>FACTURE</h2>
        <p><strong>{{ $facture->numero_facture }}</strong></p>
    </div>

    <div class="info-section">
        <h3>Informations Client</h3>
        <div class="info-row">
            <strong>Nom :</strong> {{ $facture->commande->client->name }}
        </div>
        <div class="info-row">
            <strong>Email :</strong> {{ $facture->commande->client->email }}
        </div>
        <div class="info-row">
            <strong>Téléphone :</strong> {{ $facture->commande->client->telephone ?? 'N/A' }}
        </div>
        <div class="info-row">
            <strong>Type :</strong> {{ ucfirst($facture->commande->client->type_client ?? 'Particulier') }}
        </div>
    </div>

    <div class="info-section">
        <h3>Détails de la commande</h3>
        <div class="info-row">
            <strong>Numéro de commande :</strong> #{{ str_pad($facture->commande_id, 3, '0', STR_PAD_LEFT) }}
        </div>
        <div class="info-row">
            <strong>Date de service :</strong> {{ $facture->commande->created_at->format('d/m/Y') }}
        </div>
        <div class="info-row">
            <strong>Adresse :</strong> {{ $facture->commande->adresse_service }}
        </div>
        @if($facture->commande->zone)
        <div class="info-row">
            <strong>Zone :</strong> {{ $facture->commande->zone->nom }} - {{ $facture->commande->zone->ville }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $facture->commande->type_service)) }}</td>
                <td>{{ $facture->commande->nombre_vehicules }} véhicule(s)</td>
                <td>{{ number_format($facture->montant / $facture->commande->nombre_vehicules, 2) }}€</td>
                <td>{{ number_format($facture->montant, 2) }}€</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        TOTAL : {{ number_format($facture->montant, 2) }}€
    </div>

    <div class="info-section">
        <h3>Informations de paiement</h3>
        <div class="info-row">
            <strong>Mode de paiement :</strong> {{ ucfirst(str_replace('_', ' ', $facture->paiement->mode_paiement)) }}
        </div>
        <div class="info-row">
            <strong>Statut :</strong> {{ ucfirst($facture->paiement->statut) }}
        </div>
        <div class="info-row">
            <strong>Date de paiement :</strong> {{ $facture->paiement->date_paiement ? $facture->paiement->date_paiement->format('d/m/Y à H:i') : 'En attente' }}
        </div>
        <div class="info-row">
            <strong>Date de facture :</strong> {{ $facture->date_facture->format('d/m/Y à H:i') }}
        </div>
    </div>

    <div class="footer">
        <p>LAVAGINI - Service de lavage de véhicules à domicile</p>
        <p>Merci de votre confiance !</p>
        <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
