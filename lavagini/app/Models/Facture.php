<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'paiement_id',
        'numero_facture',
        'montant',
        'date_facture'
    ];

    protected $casts = [
        'date_facture' => 'datetime',
    ];

    public function getMontantAttribute($value)
    {
        if ((float) $value > 0) {
            return (float) $value;
        }

        if ($this->paiement && (float) $this->paiement->montant > 0) {
            return (float) $this->paiement->montant;
        }

        if ($this->commande) {
            $tarifsParDefaut = [
                'lavage_standard' => 100,
                'lavage_complet' => 150,
                'lavage_premium' => 250,
            ];

            $tarif = Tarif::where('type_service', $this->commande->type_service)->first();
            $prixUnitaire = $tarif ? (float) $tarif->prix_unitaire : ($tarifsParDefaut[$this->commande->type_service] ?? 0);

            return $prixUnitaire * (int) $this->commande->nombre_vehicules;
        }

        return 0.0;
    }

    // Relation avec la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relation avec le paiement
    public function paiement()
    {
        return $this->belongsTo(Paiement::class);
    }
}
