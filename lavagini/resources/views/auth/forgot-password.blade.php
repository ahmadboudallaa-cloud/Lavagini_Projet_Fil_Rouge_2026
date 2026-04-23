@extends('layouts.auth')

@section('title', 'Mot de passe oublié')

@section('styles')
<style>
    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        padding: 2rem;
    }

    /* Forgot Password Card */
    .forgot-card {
        background: #4a4a4a;
        border-radius: 40px;
        padding: 3rem 2.5rem;
        width: 100%;
        max-width: 450px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    /* Logo */
    .logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 1rem;
    }

    .logo-container img {
        width: 80px;
        height: 80px;
        filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%);
    }

    .logo-text {
        color: #00C2FF;
        font-weight: 800;
        font-size: 1rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        margin-top: 0.5rem;
    }

    /* Title */
    .forgot-title {
        color: #00C2FF;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* Info Box */
    .info-box {
        background: #333333;
        color: #cccccc;
        padding: 1rem 1.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        font-size: 0.85rem;
        text-align: center;
        line-height: 1.5;
    }

    /* Alert */
    .alert {
        width: 100%;
        padding: 1rem 1.5rem;
        border-radius: 20px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        text-align: center;
    }

    .alert-success {
        background: #1a4d2e;
        color: #4ade80;
        border: 1px solid #22c55e;
    }

    .alert-error {
        background: #4d1a1a;
        color: #f87171;
        border: 1px solid #ef4444;
    }

    /* Form */
    .form-group {
        width: 100%;
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        margin-left: 1.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.9rem 1.5rem;
        border-radius: 50px;
        border: none;
        background: #2b2b2b;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        transition: all 0.3s;
    }

    .form-group input:focus {
        background: #333333;
        box-shadow: 0 0 0 2px #00C2FF;
    }

    .form-group input::placeholder {
        color: #666666;
    }

    /* Submit Button */
    .btn-submit {
        background: #00C2FF;
        color: #000;
        padding: 0.8rem 3rem;
        border-radius: 50px;
        border: none;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-submit:hover {
        background: #00a8d6;
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(0, 194, 255, 0.4);
    }

    /* Back Link */
    .back-link {
        margin-top: 1.5rem;
        text-align: center;
    }

    .back-link a {
        color: #00C2FF;
        font-size: 0.85rem;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .back-link a:hover {
        opacity: 0.8;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<!-- Forgot Password Card -->
<div class="forgot-card">
    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('assets/logo.png') }}" alt="LAVAGINI">
        
    </div>

    <!-- Title -->
    <h2 class="forgot-title">Mot de passe oublié</h2>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- Info Box -->
    <div class="info-box">
        Entrez votre email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
    </div>

    <!-- Form -->
    <form action="/forgot-password" method="POST" style="width: 100%;">
        @csrf
        
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" placeholder="votre@email.com" required>
            @error('email')
                <span style="color: #f87171; font-size: 0.75rem; margin-left: 1.5rem; display: block; margin-top: 0.5rem;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Envoyer le lien de réinitialisation</button>
    </form>

    <!-- Back Link -->
    <div class="back-link">
        <a href="/login">← Retour à la connexion</a>
    </div>
</div>
@endsection
