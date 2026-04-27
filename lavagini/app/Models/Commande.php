<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tarif;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'zone_id',
        'nombre_vehicules',
        'type_service',
        'adresse_service',
        'statut',
        'mode_paiement',
        'montant',
        'description'
    ];

    public function getMontantAttribute($value)
    {
        if ((float) $value > 0) {
            return (float) $value;
        }

        $tarifsParDefaut = [
            'lavage_standard' => 100,
            'lavage_complet' => 150,
            'lavage_premium' => 250,
        ];

        $tarif = Tarif::where('type_service', $this->type_service)->first();
        $prixUnitaire = $tarif ? (float) $tarif->prix_unitaire : ($tarifsParDefaut[$this->type_service] ?? 0);

        return $prixUnitaire * (int) $this->nombre_vehicules;
    }

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Relation avec la zone géographique
    public function zone()
    {
        return $this->belongsTo(ZoneGeographique::class, 'zone_id');
    }

    // Relation avec la mission
    public function mission()
    {
        return $this->hasOne(Mission::class);
    }

    // Relation avec le paiement
    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

    // Relation avec la facture
    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    // Relation avec l'évaluation
    public function evaluation()
    {
        return $this->hasOne(Evaluation::class);
    }
}
