@extends('layouts.app')

@section('title', 'Connexion')

@section('styles')
<style>
    .auth-container {
        max-width: 400px;
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

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .form-group input:focus {
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
    <h2>Connexion</h2>
    
    <form action="/login" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
    </form>

    <div class="text-center">
        <p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>
    </div>
</div>
@endsection
