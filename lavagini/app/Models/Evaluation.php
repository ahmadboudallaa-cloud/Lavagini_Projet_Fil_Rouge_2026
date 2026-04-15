<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'commande_id',
        'laveur_id',
        'client_id',
        'note',
        'commentaire'
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

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
