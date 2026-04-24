<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'commande_id',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relations
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Méthode pour obtenir l'autre participant
    public function getOtherUser($userId)
    {
        return $this->user1_id == $userId ? $this->user2 : $this->user1;
    }

    // Méthode pour obtenir le dernier message
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Méthode pour compter les messages non lus
    public function unreadCount($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
