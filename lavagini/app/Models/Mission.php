<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'laveur_id',
        'statut',
        'date_debut',
        'date_fin',
        'temps_passe',
        'commentaire'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    // Relation avec la commande
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    // Relation avec le laveur
    public function laveur()
    {
        return $this->belongsTo(User::class, 'laveur_id');
    }
}
