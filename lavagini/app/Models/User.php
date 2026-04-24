<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'telephone', 'adresse', 'type_client', 'photo_profile'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations pour les clients
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'client_id');
    }

    // Relations pour les laveurs
    public function missions()
    {
        return $this->hasMany(Mission::class, 'laveur_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'laveur_id');
    }

    // Relations pour tous les utilisateurs
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Relations pour le chat
    public function conversationsAsUser1()
    {
        return $this->hasMany(Conversation::class, 'user1_id');
    }

    public function conversationsAsUser2()
    {
        return $this->hasMany(Conversation::class, 'user2_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Méthodes utiles
    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isLaveur()
    {
        return $this->role === 'laveur';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
