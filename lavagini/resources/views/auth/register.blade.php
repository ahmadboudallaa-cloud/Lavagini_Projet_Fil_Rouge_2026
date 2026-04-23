@extends('layouts.auth')

@section('title', 'Inscription')

@section('styles')
<style>
    body {
        min-height: 100vh;
        display: flex;
        background: #000;
        overflow-y: auto;
    }

    .split-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
        background: #000;
        align-items: stretch;
    }

    .left-half {
        width: 43%;
        position: relative;
        overflow: hidden;
        background: #111;
    }

    .left-half::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.18);
    }

    .left-half img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center center;
        filter: brightness(0.52) contrast(1.02);
    }

    .right-half {
        width: 57%;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 2rem 1.5rem;
        overflow-y: auto;
    }

    .btn-accueil {
        position: absolute;
        top: 22px;
        left: 50%;
        transform: translateX(-50%);
        background: #2e2e2e;
        color: #f5f5f5;
        padding: 0.33rem 1.15rem;
        border-radius: 999px;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        line-height: 1;
        transition: background 0.25s ease, transform 0.25s ease;
    }

    .btn-accueil:hover {
        background: #3a3a3a;
        transform: translateX(-50%) translateY(-1px);
    }

    .register-card {
        width: 100%;
        max-width: 430px;
        background: #5b5b5b;
        border-radius: 18px;
        padding: 1.9rem 2.15rem 1.8rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.34);
        margin: 4.2rem 0 1.6rem;
    }

    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.18rem;
    }

    .logo-container img {
        width: 78px;
        height: 78px;
        filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%);
    }

    .register-title {
        color: #00c2ff;
        font-size: 2.45rem;
        line-height: 1;
        font-weight: 700;
        margin-bottom: 1.35rem;
    }

    .register-form {
        width: 100%;
    }

    .form-group {
        width: 100%;
        margin-bottom: 0.95rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.45rem;
        margin-left: 0.45rem;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 400;
        opacity: 0.92;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.84rem 1.15rem;
        border: none;
        border-radius: 999px;
        background: #2f2b2b;
        color: #ffffff;
        font-size: 0.95rem;
        outline: none;
        transition: background 0.25s ease, box-shadow 0.25s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        background: #383434;
        box-shadow: 0 0 0 2px #00c2ff;
    }

    .form-group select option {
        background: #2f2b2b;
        color: #ffffff;
    }

    .submit-wrap {
        margin-top: 0.3rem;
        text-align: center;
    }

    .btn-submit {
        min-width: 126px;
        padding: 0.68rem 1.55rem;
        border: none;
        border-radius: 999px;
        background: #00c2ff;
        color: #000;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
    }

    .btn-submit:hover {
        background: #00addf;
        transform: translateY(-1px);
        box-shadow: 0 5px 20px rgba(0, 194, 255, 0.22);
    }

    .login-link {
        margin-top: 1.05rem;
        color: #ffffff;
        font-size: 0.72rem;
        line-height: 1.3;
    }

    .login-link a {
        color: #00c2ff;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 900px) {
        .split-container {
            flex-direction: column;
        }

        .left-half {
            width: 100%;
            height: 38vh;
            min-height: 260px;
        }

        .right-half {
            width: 100%;
            min-height: 62vh;
            padding: 4.5rem 1rem 2rem;
        }
    }

    @media (max-width: 520px) {
        .register-card {
            max-width: 100%;
            padding: 1.35rem 1.2rem 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="split-container">
    <div class="left-half">
        <img src="{{ asset('assets/lavage6.jpg') }}" alt="Lavage professionnel">
    </div>

    <div class="right-half">
        <a href="/" class="btn-accueil">Accueil</a>

        <div class="register-card">
            <div class="logo-container">
                <img src="{{ asset('assets/logo.png') }}" alt="Lavagini">
            </div>

            <h2 class="register-title">Inscription</h2>

            <form action="/register" method="POST" class="register-form">
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
                    <label for="telephone">Telephone</label>
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

                <div class="submit-wrap">
                    <button type="submit" class="btn-submit">S'inscrire</button>
                </div>
            </form>

            <div class="login-link">
                Deja un compte ? <a href="/login">Se connecter</a>
            </div>
        </div>
    </div>
</div>
@endsection
