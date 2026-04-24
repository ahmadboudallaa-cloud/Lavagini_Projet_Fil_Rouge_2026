@extends('layouts.auth')

@section('title', 'Connexion')

@section('styles')
<style>
    body {
        min-height: 100vh;
        display: flex;
        background: #000;
    }

    .split-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
        background: #000;
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

    .login-card {
        width: 100%;
        max-width: 390px;
        background: #5b5b5b;
        border-radius: 18px;
        padding: 1.95rem 2.2rem 1.85rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.34);
    }

    .logo-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 0.12rem;
    }

    .logo-container img {
        width: 78px;
        height: 78px;
        filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%);
    }

    .logo-text {
        display: none;
    }

    .login-title {
        color: #00c2ff;
        font-size: 2.15rem;
        line-height: 1;
        font-weight: 700;
        margin-bottom: 1.45rem;
    }

    .login-form {
        width: 100%;
    }

    .form-group {
        width: 100%;
        margin-bottom: 1.08rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.48rem;
        margin-left: 0.45rem;
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 400;
        opacity: 0.92;
    }

    .form-group input {
        width: 100%;
        padding: 0.88rem 1.15rem;
        border: none;
        border-radius: 999px;
        background: #2f2b2b;
        color: #ffffff;
        font-size: 0.98rem;
        outline: none;
        transition: background 0.25s ease, box-shadow 0.25s ease;
    }

    .form-group input:focus {
        background: #383434;
        box-shadow: 0 0 0 2px #00c2ff;
    }

    .forgot-password {
        margin-top: 0.15rem;
        margin-bottom: 1.18rem;
        text-align: center;
    }

    .forgot-password a {
        color: #00c2ff;
        font-size: 0.69rem;
        text-decoration: none;
    }

    .forgot-password a:hover,
    .register-link a:hover {
        text-decoration: underline;
    }

    .submit-wrap {
        text-align: center;
    }

    .btn-submit {
        min-width: 122px;
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

    .register-link {
        margin-top: 1.08rem;
        color: #ffffff;
        font-size: 0.72rem;
        line-height: 1.3;
    }

    .register-link a {
        color: #00c2ff;
        text-decoration: none;
    }

    @media (max-width: 900px) {
        .split-container {
            flex-direction: column;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.85)), url('{{ asset('assets/login1.jpg') }}') center/cover no-repeat fixed;
        }

        .left-half {
            display: none;
        }

        .right-half {
            width: 100%;
            min-height: 100vh;
            padding: 2rem 1rem;
            background: transparent;
        }
        
        .btn-accueil {
            top: 15px;
            background: rgba(46, 46, 46, 0.9);
            backdrop-filter: blur(10px);
        }
        
        .login-card {
            background: rgba(91, 91, 91, 0.95);
            backdrop-filter: blur(10px);
        }
    }

    @media (max-width: 520px) {
        .login-card {
            max-width: 100%;
            padding: 1.35rem 1.2rem 1.25rem;
            margin-top: 60px;
        }
        
        .login-title {
            font-size: 1.8rem;
        }
        
        .logo-container img {
            width: 65px;
            height: 65px;
        }
    }
    
    @media (max-width: 360px) {
        .login-card {
            padding: 1.2rem 1rem;
            margin-top: 50px;
        }
        
        .login-title {
            font-size: 1.6rem;
            margin-bottom: 1.2rem;
        }
        
        .form-group input {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .logo-container img {
            width: 55px;
            height: 55px;
        }
    }
</style>
@endsection

@section('content')
<div class="split-container">
    <div class="left-half">
        <img src="{{ asset('assets/login1.jpg') }}" alt="Lavage professionnel">
    </div>

    <div class="right-half">
        <a href="/" class="btn-accueil">Accueil</a>

        <div class="login-card">
            <div class="logo-container">
                <img src="{{ asset('assets/logo.png') }}" alt="Lavagini">
            </div>

            <h2 class="login-title">Connexion</h2>

            <form action="/login" method="POST" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="forgot-password">
                    <a href="/forgot-password">Mot de passe oublie ?</a>
                </div>

                <div class="submit-wrap">
                    <button type="submit" class="btn-submit">Se connecter</button>
                </div>
            </form>

            <div class="register-link">
                Pas encore un compte ? <a href="/register">S'inscrire</a>
            </div>
        </div>
    </div>
</div>
@endsection
