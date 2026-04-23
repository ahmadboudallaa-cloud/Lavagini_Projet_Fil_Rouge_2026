<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAVAGINI - Votre véhicule lavé chez vous</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --cyan: #00bfff;
            --gold: #ffc107;
            --gray-100: #7a7a7a;
            --bg-dark: #000000;
            --footer-bg: #404040;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        /* ================= NAVBAR ================= */
        .navbar-wrapper {
            position: fixed;
            top: 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            z-index: 1000;
        }

        .navbar {
            background-color: rgba(50, 50, 50, 0.85);
            backdrop-filter: blur(5px);
            width: 90%;
            max-width: 1000px;
            border-radius: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 15px 8px 30px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-brand img { height: 45px; }

        .nav-links {
            display: flex;
            gap: 25px;
            font-size: 15px;
            font-weight: 500;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-black { background-color: #000; color: #fff; border: 1px solid rgba(255,255,255,0.2); }
        .btn-cyan { background-color: var(--cyan); color: #fff; }

        /* ================= HERO ================= */
        .hero {
            height: 100vh;
            min-height: 600px;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.7)), url('{{ asset('assets/image1.jpg') }}') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
        }

        .hero h1 {
            font-size: 65px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
        }
        .hero h1 span { color: var(--cyan); }
        
        .hero p {
            max-width: 650px;
            font-size: 18px;
            color: #e0e0e0;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .btn-hero {
            background-color: var(--cyan);
            color: #fff;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
        }

        /* ================= TITRES SECTIONS ================= */
        .section-header {
            text-align: center;
            margin: 80px 0 50px;
        }
        .section-header h3 {
            color: var(--cyan);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .section-header h2 {
            font-size: 36px;
            font-weight: 800;
        }

        /* ================= TIMELINE (PARTIE SERVICES) ================= */
        .timeline {
            position: relative;
            max-width: 960px;
            margin: 0 auto 100px;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            gap: 70px;
        }

        /* Ligne bleue centrale continue */
        .timeline-line {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 3px;
            background-color: var(--cyan);
            transform: translateX(-50%);
            z-index: 1;
        }

        .timeline-item {
            display: flex;
            width: 100%;
            justify-content: flex-start;
            position: relative;
            align-items: center;
        }

        .timeline-item.right {
            justify-content: flex-end;
        }

        /* La carte blanche de contenu */
        .timeline-card {
            width: calc(50% - 40px); /* 40px d'espace par rapport au centre */
            height: 150px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            flex-direction: row; /* Image à gauche par défaut */
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 2;
        }

        /* Inversion pour les cartes à droite : Image passe à droite */
        .timeline-item.right .timeline-card {
            flex-direction: row-reverse;
        }
        
        .timeline-card img {
            width: 45%;
            height: 100%;
            object-fit: cover;
        }
        
        .timeline-content {
            width: 55%;
            padding: 15px 25px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .timeline-content h4 {
            color: var(--cyan);
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        
        .timeline-content p {
            font-size: 12px;
            font-weight: 500;
            color: #333;
            line-height: 1.4;
        }

        /* Le point bleu sur la ligne */
        .timeline-dot {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 16px;
            height: 16px;
            background-color: var(--cyan);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
        }

        /* ================= TABLEAU TARIFS ================= */
        .pricing-section {
            padding-bottom: 80px;
        }

        .pricing-table-wrapper {
            max-width: 1000px;
            margin: 120px auto 40px;
            display: flex;
            border: 1px solid rgba(255,255,255,0.4);
            border-top: none;
            background-color: rgba(30, 30, 30, 0.8);
        }

        .col {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .col-services { flex: 1.6; }

        .pill-header {
            position: absolute;
            top: -90px;
            left: 0;
            right: 0;
            height: 130px;
            border-radius: 60px 60px 0 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.4);
            border-bottom: none;
            padding-top: 10px;
        }

        .header-services { 
            position: absolute; top: -60px; left: 0; right: 0; height: 60px;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 700; background: transparent;
        }

        .pill-header h4 { font-size: 32px; font-weight: 900; margin-bottom: 2px; }
        .pill-header p { font-size: 12px; font-weight: 600; text-transform: uppercase; line-height: 1.2; }

        /* Couleurs de fond des colonnes avec écriture blanche pour la version Gold */
        .bg-gray { background-color: var(--gray-100); color: #fff; }
        .bg-cyan { background-color: var(--cyan); color: #fff; }
        .bg-gold { background-color: var(--gold); color: #fff; } /* <- MODIFIÉ ICI */
        .bg-services { background: linear-gradient(180deg, #999999 0%, #4a4a4a 100%); }

        .row-list {
            padding-top: 50px;
            padding-bottom: 20px;
            width: 100%;
        }

        .row-item {
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .col-services .row-item {
            justify-content: flex-start;
            padding-left: 20px;
            font-size: 13.5px;
            font-weight: 500;
        }

        .col:not(:last-child) { border-right: 1px solid rgba(255,255,255,0.4); }
        .x-mark { font-weight: 400; opacity: 0.8; }
        
        .btn-reserve-container { text-align: center; margin-top: 40px; }
        .btn-reserve-table {
            background-color: #fff;
            color: #000;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 800;
            display: inline-block;
        }

        /* ================= FOOTER ================= */
        .footer {
            background-color: var(--footer-bg);
            padding: 50px 10%;
            display: flex;
            justify-content: space-between;
        }

        .footer-col h4 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .footer-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: 600;
        }

        .social-icons {
            display: flex;
            gap: 12px;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            background-color: #fff;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .icon-box svg {
            width: 24px;
            height: 24px;
            fill: var(--footer-bg);
        }

        .contact-icon {
            width: 35px;
            height: 35px;
            background: #fff;
            border-radius: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .contact-icon svg { width: 20px; height: 20px; fill: var(--footer-bg); }

    </style>
</head>
<body>

    <div class="navbar-wrapper">
        <nav class="navbar">
            <a href="/" class="nav-brand">
                <img src="{{ asset('assets/logo.png') }}" alt="Lavagini">
            </a>
            <div class="nav-links">
                <a href="#accueil">Accueil</a>
                <a href="#services">Services</a>
                <a href="#tarifs">Tarifs</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="nav-buttons">
                <a href="/login" class="btn btn-black">Connexion</a>
                <a href="/register" class="btn btn-cyan">S'inscrire</a>
            </div>
        </nav>
    </div>

    <section class="hero" id="accueil">
        <h1>Votre véhicule lavé <br> <span>chez vous</span>, en un clic</h1>
        <p>LAVAGINI met à votre disposition des laveurs professionnels qui se déplacent chez vous. Réservez en quelques clics, nous faisons le reste.</p>
        <a href="/register" class="btn-hero">Réserver maintenant</a>
    </section>

    <section id="services">
        <div class="section-header">
            <h3>Nos Services</h3>
            <h2>Des services adaptés à vos besoins</h2>
        </div>

        <div class="timeline">
            <div class="timeline-line"></div>

            <div class="timeline-item">
                <div class="timeline-card">
                    <img src="{{ asset('assets/Lavage extérieur.jpg') }}" alt="Lavage extérieur">
                    <div class="timeline-content">
                        <h4>Lavage extérieur</h4>
                        <p>Nettoyage complet de la carrosserie, jantes et vitres avec des produits professionnels.</p>
                    </div>
                </div>
                <div class="timeline-dot"></div>
            </div>

            <div class="timeline-item right">
                <div class="timeline-card">
                    <img src="{{ asset('assets/Lavage intérieur.jpg') }}" alt="Lavage intérieur">
                    <div class="timeline-content">
                        <h4>Lavage intérieur</h4>
                        <p>Aspiration, nettoyage des sièges, tableau de bord et surfaces intérieures.</p>
                    </div>
                </div>
                <div class="timeline-dot"></div>
            </div>

            <div class="timeline-item">
                <div class="timeline-card">
                    <img src="{{ asset('assets/Produits écologiques.jpg') }}" alt="Produits écologiques">
                    <div class="timeline-content">
                        <h4>Produits écologiques</h4>
                        <p>Nous utilisons des produits biodégradables respectueux de l'environnement.</p>
                    </div>
                </div>
                <div class="timeline-dot"></div>
            </div>

            <div class="timeline-item right">
                <div class="timeline-card">
                    <img src="{{ asset('assets/Disponibilite flexible.png') }}" alt="Disponibilité flexible">
                    <div class="timeline-content">
                        <h4>Disponibilité flexible</h4>
                        <p>Service disponible 7j/7 avec des créneaux matin, après-midi et soir.</p>
                    </div>
                </div>
                <div class="timeline-dot"></div>
            </div>
        </div>
    </section>

    <section id="tarifs" class="pricing-section">
        <div class="section-header">
            <h3>Nos Tarifs</h3>
            <h2>Des coûts clairs et détaillés</h2>
        </div>

        <div class="pricing-table-wrapper">
            
            <div class="col col-services bg-services">
                <div class="header-services">Services</div>
                <ul class="row-list">
                    <li class="row-item">- Lavage Carrosserie</li>
                    <li class="row-item">- Nettoyage Jantes</li>
                    <li class="row-item">- Nettoyage Pneus</li>
                    <li class="row-item">- Nettoyage Vitres extérieures</li>
                    <li class="row-item">- Dépoussiérage Habitacle</li>
                    <li class="row-item">- Nettoyage Tapis</li>
                    <li class="row-item">- Aspiration Sièges</li>
                    <li class="row-item">- Nettoyage Coffre</li>
                    <li class="row-item">- Nettoyage Vitres intérieures</li>
                    <li class="row-item">- Démoustiquage Carrosserie</li>
                    <li class="row-item">- Déperlage Carrosserie</li>
                    <li class="row-item">- Traitement anti-pluie vitres</li>
                    <li class="row-item">- Nettoyage Plafond de toit</li>
                    <li class="row-item">- Traitement Cuir & Tissu</li>
                    <li class="row-item">- Parfumage Habitacle (au choix)</li>
                    <li class="row-item">- Protection Cire Longue Durée</li>
                    <li class="row-item">- Brillant pneus Premium</li>
                    <li class="row-item">- Shampoing sièges approfondi</li>
                    <li class="row-item">- Désinfection Habitacle</li>
                    <li class="row-item">- Lavage moteur</li>
                </ul>
            </div>

            <div class="col bg-gray">
                <div class="pill-header bg-gray">
                    <h4>100 DH</h4>
                    <p>Lavage complet<br>Intérieur + Extérieur</p>
                </div>
                <ul class="row-list">
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                </ul>
            </div>

            <div class="col bg-cyan">
                <div class="pill-header bg-cyan">
                    <h4>150 DH</h4>
                    <p>Lavage Extra<br>Intérieur + Extérieur</p>
                </div>
                <ul class="row-list">
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                    <li class="row-item x-mark">X</li><li class="row-item x-mark">X</li>
                </ul>
            </div>

            <div class="col bg-gold">
                <div class="pill-header bg-gold">
                    <h4>250 DH</h4>
                    <p>Lavage Premium<br>Intérieur + Extérieur</p>
                </div>
                <ul class="row-list">
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                    <li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li><li class="row-item">✓</li>
                </ul>
            </div>

        </div>

        <div class="btn-reserve-container">
            <a href="/register" class="btn-reserve-table">Réserver maintenant</a>
        </div>
    </section>

    <footer class="footer" id="contact">
        <div class="footer-col">
            <h4>Contact :</h4>
            <ul class="footer-list">
                <li>
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    </span>
                    bdforcleaning@gmail.com
                </li>
                <li>
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                    </span>
                    +212 712-176952
                </li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>Social Media:</h4>
            <div class="social-icons">
                <a href="#" class="icon-box">
                    <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <a href="#" class="icon-box">
                    <svg viewBox="0 0 24 24"><path d="M21.582 6.186a2.665 2.665 0 00-1.874-1.888C17.963 3.846 12 3.846 12 3.846s-5.963 0-7.708.452a2.665 2.665 0 00-1.874 1.888C1.966 7.948 1.966 12 1.966 12s0 4.052.452 5.814a2.665 2.665 0 001.874 1.888c1.745.452 7.708.452 7.708.452s5.963 0 7.708-.452a2.665 2.665 0 001.874-1.888C22.034 16.052 22.034 12 22.034 12s0-4.052-.452-5.814zM9.98 15.485V8.515L16.02 12l-6.04 3.485z"/></svg>
                </a>
                <a href="#" class="icon-box">
                    <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                <a href="#" class="icon-box">
                    <svg viewBox="0 0 24 24"><path d="M22.675 0H1.325C.593 0 0 .593 0 1.325v21.351C0 23.407.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.593 1.323-1.325V1.325C24 .593 23.407 0 22.675 0z"/></svg>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Liens Utiles:</h4>
            <ul class="footer-list">
                <li><a href="#">Comment réserver ?</a></li>
                <li><a href="#">politique de confidentialité</a></li>
                <li><a href="#">Conditions générales</a></li>
            </ul>
        </div>
    </footer>

</body>
</html>