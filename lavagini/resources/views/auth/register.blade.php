@extends('layouts.app')

@section('title', 'Inscription')

@section('styles')
<style>
    .auth-container {
        max-width: 500px;
        margin: 3rem auto;
        padding: 2rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .auth-container h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 2rem;
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

    .btn-full {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
    }

    .text-center {
        text-align: center;
        margin-top: 1rem;
    }

    .text-center a {
        color: #3498db;
        text-decoration: none;
    }

    .text-center a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <h2>Inscription</h2>
    
    <form action="/register" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Nom complet</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone">
        </div>

        <div class="form-group">
            <label for="adresse">Adresse</label>
            <input type="text" id="adresse" name="adresse">
        </div>

        <div class="form-group">
            <label for="type_client">Type de client</label>
            <select id="type_client" name="type_client">
                <option value="particulier">Particulier</option>
                <option value="agence">Agence</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success btn-full">S'inscrire</button>
    </form>

    <div class="text-center">
        <p>Déjà un compte ? <a href="/login">Se connecter</a></p>
    </div>
</div>
@endsection
