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
