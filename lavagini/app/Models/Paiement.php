<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'montant',
        'mode_paiement',
        'statut',
        'transaction_id',
        'date_paiement'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
    ];

    // Relation avec la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relation avec la facture
    public function facture()
    {
        return $this->hasOne(Facture::class);
    }
}
