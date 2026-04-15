<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'zone_id',
        'nombre_vehicules',
        'adresse_service',
        'statut',
        'mode_paiement',
        'montant',
        'description'
    ];

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
