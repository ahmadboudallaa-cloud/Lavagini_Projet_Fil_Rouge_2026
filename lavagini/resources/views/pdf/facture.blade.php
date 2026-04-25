<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $facture->numero_facture }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000000;
            background-color: #ffffff;
            margin: 0;
            padding: 24px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border: 2px solid #000000;
        }

        .header {
            text-align: center;
            margin-bottom: 36px;
            padding-bottom: 24px;
            border-bottom: 3px solid #000000;
        }

        .header h1 {
            color: #000000;
            margin: 0;
            font-size: 44px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .header p {
            color: #333333;
            margin: 8px 0;
            font-size: 14px;
        }

        .header h2 {
            color: #000000;
            margin: 18px 0 8px 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .header .numero {
            color: #000000;
            font-size: 18px;
            font-weight: 700;
        }

        .info-section {
            margin: 24px 0;
            background-color: #ffffff;
            border: 1px solid #000000;
            padding: 18px;
        }

        .info-section h3 {
            background: #000000;
            color: #ffffff;
            padding: 12px 16px;
            margin: -18px -18px 18px -18px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.04em;
        }

        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #dddddd;
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row strong {
            color: #000000;
            font-weight: 700;
            flex-shrink: 0;
        }

        .info-row span {
            color: #222222;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
            border: 1px solid #000000;
            background-color: #ffffff;
        }

        table th {
            background: #000000;
            color: #ffffff;
            padding: 14px;
            text-align: left;
            font-weight: 700;
            font-size: 14px;
            border-right: 1px solid #ffffff;
        }

        table th:last-child {
            border-right: none;
        }

        table td {
            padding: 14px;
            border-top: 1px solid #dddddd;
            color: #000000;
        }

        table tr:nth-child(even) td {
            background-color: #f5f5f5;
        }

        .total-section {
            background-color: #f5f5f5;
            border: 2px solid #000000;
            padding: 22px;
            margin-top: 28px;
        }

        .total-label {
            text-align: right;
            color: #000000;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.04em;
        }

        .total {
            text-align: right;
            font-size: 30px;
            font-weight: 800;
            color: #000000;
            margin: 0;
        }

        .footer {
            margin-top: 44px;
            text-align: center;
            font-size: 12px;
            color: #444444;
            border-top: 1px solid #000000;
            padding-top: 24px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .brand {
            color: #000000;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.08em;
        }

        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .container {
                border: none;
                padding: 24px;
            }

            .info-section,
            .total-section,
            table {
                break-inside: avoid;
            }
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
