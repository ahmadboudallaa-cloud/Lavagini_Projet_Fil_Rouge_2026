@extends('layouts.app')

@section('title', 'Accueil')

@section('styles')
<style>
    .hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5rem 2rem;
        text-align: center;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
    }

    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }

    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
    }

    .feature-card h3 {
        color: #3498db;
        margin-bottom: 1rem;
    }

    .services {
        background-color: #f8f9fa;
        padding: 3rem 0;
    }

    .services h2 {
        text-align: center;
        margin-bottom: 2rem;
        color: #2c3e50;
    }

    .cta {
        text-align: center;
        padding: 3rem 0;
    }

    .cta h2 {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="hero">
    <h1>Bienvenue chez LAVAGINI</h1>
    <p>Votre service de lavage de véhicules à domicile</p>
    <a href="/register" class="btn btn-success">Commencer maintenant</a>
</div>

<div class="container">
    <div class="features">
        <div class="feature-card">
            <h3>🚗 Service à domicile</h3>
            <p>Nous venons directement chez vous pour laver votre véhicule</p>
        </div>
        <div class="feature-card">
            <h3>⏰ Disponibilité flexible</h3>
            <p>Réservez quand vous voulez, nous nous adaptons à votre emploi du temps</p>
        </div>
        <div class="feature-card">
            <h3>✨ Qualité professionnelle</h3>
            <p>Des laveurs expérimentés et des produits de qualité</p>
        </div>
    </div>
</div>

<div class="services">
    <div class="container">
        <h2>Nos Services</h2>
        <div class="features">
            <div class="feature-card">
                <h3>Lavage Extérieur</h3>
                <p>Nettoyage complet de la carrosserie, vitres et jantes</p>
            </div>
            <div class="feature-card">
                <h3>Lavage Intérieur</h3>
                <p>Aspiration, nettoyage des sièges et tableau de bord</p>
            </div>
            <div class="feature-card">
                <h3>Lavage Complet</h3>
                <p>Intérieur + Extérieur pour un véhicule impeccable</p>
            </div>
        </div>
    </div>
</div>

<div class="cta">
    <div class="container">
        <h2>Prêt à commencer ?</h2>
        <p>Inscrivez-vous maintenant et réservez votre premier lavage</p>
        <a href="/register" class="btn btn-primary">S'inscrire</a>
        <a href="/login" class="btn btn-success">Se connecter</a>
    </div>
</div>
@endsection
