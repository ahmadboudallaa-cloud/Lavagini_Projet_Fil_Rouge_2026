@extends('layouts.app')

@section('title', 'Mon Profil')

@section('styles')
<style>
    .profil-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .profil-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .photo-section {
        text-align: center;
        margin-bottom: 2rem;
    }

    .photo-profile {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        margin-bottom: 1rem;
    }

    .photo-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #999;
        margin: 0 auto 1rem;
        border: 5px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .profil-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .profil-card h3 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #3498db;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .badge-role {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .badge-client {
        background-color: #3498db;
        color: white;
    }

    .badge-laveur {
        background-color: #27ae60;
        color: white;
    }

    .badge-admin {
        background-color: #e74c3c;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="profil-container">
    <div class="profil-header">
        <h1>Mon Profil</h1>
        <span class="badge-role badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
    </div>

    <div class="profil-card">
        <h3>Photo de profil</h3>
        <div class="photo-section">
            @if($user->photo_profile)
                <img src="{{ asset('storage/' . $user->photo_profile) }}" alt="Photo de profil" class="photo-profile">
            @else
                <div class="photo-placeholder">👤</div>
            @endif
            
            <form action="/profil/photo/supprimer" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                @if($user->photo_profile)
                <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Supprimer la photo ?')">Supprimer la photo</button>
                @endif
            </form>
        </div>
    </div>

    <div class="profil-card">
        <h3>Informations personnelles</h3>
        
        <form action="/profil" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Nom complet *</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" value="{{ $user->telephone }}">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse" value="{{ $user->adresse }}">
            </div>

            @if($user->role === 'client')
            <div class="form-group">
                <label for="type_client">Type de client</label>
                <select id="type_client" name="type_client">
                    <option value="particulier" {{ $user->type_client === 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="agence" {{ $user->type_client === 'agence' ? 'selected' : '' }}>Agence</option>
                </select>
            </div>
            @endif

            <div class="form-group">
                <label for="photo_profile">Changer la photo de profil</label>
                <input type="file" id="photo_profile" name="photo_profile" accept="image/*">
                <small style="color: #999;">Format accepté : JPG, PNG, GIF (max 2MB)</small>
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                <a href="/dashboard" class="btn btn-primary">Retour au dashboard</a>
            </div>
        </form>
    </div>
</div>
@endsection
