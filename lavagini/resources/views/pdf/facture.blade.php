<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #ffffff;
            background-color: #000000;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 194, 255, 0.2);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #00C2FF;
            padding-bottom: 30px;
        }
        .header h1 {
            color: #00C2FF;
            margin: 0;
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .header p {
            color: #cccccc;
            margin: 10px 0;
            font-size: 14px;
        }
        .header h2 {
            color: #ffffff;
            margin: 20px 0 10px 0;
            font-size: 24px;
        }
        .header .numero {
            color: #00C2FF;
            font-size: 18px;
            font-weight: bold;
        }
        .info-section {
            margin: 30px 0;
            background-color: #2b2b2b;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid #00C2FF;
        }
        .info-section h3 {
            background: linear-gradient(135deg, #00C2FF 0%, #0099cc 100%);
            color: #000000;
            padding: 12px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            font-size: 16px;
        }
        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #333333;
            display: flex;
            justify-content: space-between;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-row strong {
            color: #00C2FF;
            font-weight: 600;
        }
        .info-row span {
            color: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background-color: #2b2b2b;
            border-radius: 12px;
            overflow: hidden;
        }
        table th {
            background: linear-gradient(135deg, #00C2FF 0%, #0099cc 100%);
            color: #000000;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }
        table td {
            padding: 15px;
            border-bottom: 1px solid #333333;
            color: #ffffff;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            background-color: #2b2b2b;
            border-radius: 12px;
            padding: 25px;
            margin-top: 30px;
            border: 2px solid #00C2FF;
        }
        .total {
            text-align: right;
            font-size: 32px;
            font-weight: bold;
            color: #00C2FF;
            margin: 0;
        }
        .total-label {
            text-align: right;
            color: #cccccc;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666666;
            border-top: 1px solid #333333;
            padding-top: 30px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer .brand {
            color: #00C2FF;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LAVAGINI</h1>
            <p>Service de lavage de véhicules à domicile</p>
            <h2>FACTURE</h2>
            <p class="numero">{{ $facture->numero_facture }}</p>
        </div>

        <div class="info-section">
            <h3>Informations Client</h3>
            <div class="info-row">
                <strong>Nom :</strong>
                <span>{{ $facture->commande->client->name }}</span>
            </div>
            <div class="info-row">
                <strong>Email :</strong>
                <span>{{ $facture->commande->client->email }}</span>
            </div>
            <div class="info-row">
                <strong>Téléphone :</strong>
                <span>{{ $facture->commande->client->telephone ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <strong>Type :</strong>
                <span>{{ ucfirst($facture->commande->client->type_client ?? 'Particulier') }}</span>
            </div>
        </div>

        <div class="info-section">
            <h3>Détails de la commande</h3>
            <div class="info-row">
                <strong>Numéro de commande :</strong>
                <span>#{{ str_pad($facture->commande_id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <strong>Date de service :</strong>
                <span>{{ $facture->commande->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <strong>Adresse :</strong>
                <span>{{ $facture->commande->adresse_service }}</span>
            </div>
            @if($facture->commande->zone)
            <div class="info-row">
                <strong>Zone :</strong>
                <span>{{ $facture->commande->zone->nom }} - {{ $facture->commande->zone->ville }}</span>
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
                    <td>{{ number_format($facture->montant / $facture->commande->nombre_vehicules, 2) }} DH</td>
                    <td>{{ number_format($facture->montant, 2) }} DH</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-label">MONTANT TOTAL</div>
            <div class="total">{{ number_format($facture->montant, 2) }} DH</div>
        </div>

        <div class="info-section">
            <h3>Informations de paiement</h3>
            <div class="info-row">
                <strong>Mode de paiement :</strong>
                <span>{{ ucfirst(str_replace('_', ' ', $facture->paiement->mode_paiement)) }}</span>
            </div>
            <div class="info-row">
                <strong>Statut :</strong>
                <span>{{ ucfirst($facture->paiement->statut) }}</span>
            </div>
            <div class="info-row">
                <strong>Date de paiement :</strong>
                <span>{{ $facture->paiement->date_paiement ? $facture->paiement->date_paiement->format('d/m/Y à H:i') : 'En attente' }}</span>
            </div>
            <div class="info-row">
                <strong>Date de facture :</strong>
                <span>{{ $facture->date_facture->format('d/m/Y à H:i') }}</span>
            </div>
        </div>

        <div class="footer">
            <p class="brand">LAVAGINI</p>
            <p>Service de lavage de véhicules à domicile</p>
            <p>Merci de votre confiance !</p>
            <p>Cette facture a été générée automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
