<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavagini</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; color: #111; background: #fff; }
        a { text-decoration: none; color: inherit; }
        img { display: block; max-width: 100%; }

        .container { width: 92%; max-width: 1250px; margin: 0 auto; }

        /* Navbar */
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 6px 0; }
        .logo img { width: 130px; }
        .menu { display: flex; gap: 20px; font-size: 12px; font-weight: 600; color: #222; }
        .buttons { display: flex; gap: 6px; }
        .btn { padding: 5px 10px; border-radius: 6px; font-size: 11px; color: #fff; font-weight: bold; }
        .btn-login { background: #00B7FF; }
        .btn-signup { background: #2C33FF; }

        /* Hero */
        .hero { display: flex; width: 100%; border-top: 2px solid #2C33FF; }
        .hero-left {
            flex: 1.4;
            background: #000;
            color: #fff;
            padding: 38px 34px;
        }
        .hero-left h1 { margin: 0 0 14px; font-size: 36px; line-height: 1.2; }
        .hero-left h1 span { color: #2C33FF; }
        .hero-left p { margin: 0 0 18px; font-size: 15px; line-height: 1.6; color: #d9d9d9; max-width: 520px; }
        .hero-left .btn-cta {
            display: inline-block;
            background: #2C33FF;
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
        }
        .hero-right { flex: 1; }
        .hero-right img { width: 100%; height: 100%; object-fit: cover; }

        /* Sections */
        .section { padding: 46px 0; text-align: center; }
        .section h2 { margin: 0; font-size: 16px; color: #00B7FF; }
        .section h3 { margin: 6px 0 22px; font-size: 19px; font-weight: 700; }
        .section h3 span { color: #2C33FF; }

        /* Services */
        .services { display: flex; justify-content: center; gap: 18px; flex-wrap: wrap; }
        .service-card {
            width: 220px;
            border: 1.5px solid #888;
            border-radius: 26px;
            padding: 14px 12px 22px;
            text-align: center;
            min-height: 390px;
        }
        .service-card img {
            width: 100%;
            height: 170px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 12px;
        }
        .service-card h4 { margin: 0 0 10px; font-size: 14px; color: #2C33FF; }
        .service-card p { margin: 0; font-size: 12px; line-height: 1.5; color: #444; }

        /* Tarifs */
        .pricing { display: flex; justify-content: center; gap: 18px; flex-wrap: wrap; }
        .price-card {
            width: 260px;
            border: 1.5px solid #111;
            border-radius: 16px;
            padding: 16px 14px 0;
            text-align: center;
        }
        .price-card img { width: 70px; margin: 0 auto 10px; }
        .price-card h4 { margin: 0; font-size: 14px; }
        .price-card .sub { font-size: 12px; margin: 2px 0 10px; }
        .price { font-size: 24px; font-weight: bold; margin: 6px 0; }
        .price-card ul { list-style: none; padding: 0; text-align: left; font-size: 11px; line-height: 1.5; min-height: 150px; }
        .price-card .footer-btn {
            margin-top: 14px;
            padding: 10px 0;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            border-radius: 0 0 10px 10px;
            background: #2C33FF;
        }
        .price-card.blue { border-color: #00B7FF; }
        .price-card.blue .price { color: #00B7FF; }
        .price-card.gold { border-color: #f4b400; }
        .price-card.gold .price { color: #f4b400; }
        .price-card.gold .footer-btn { background: #f4b400; color: #111; }

        /* Footer */
        footer { background: #000; color: #fff; margin-top: 50px; }
        .footer-top {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            flex-wrap: wrap;
            padding: 32px 0;
            font-size: 12px;
        }
        .footer-top h4 { margin: 0 0 12px; font-size: 13px; }
        .footer-item { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .footer-item img { width: 16px; }
        .socials img { width: 22px; margin-right: 8px; }
        .footer-bottom {
            background: #00B7FF;
            color: #003;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .hero { flex-direction: column; }
            .menu { display: none; }
            .service-card { width: 45%; }
            .price-card { width: 46%; }
        }
        @media (max-width: 600px) {
            .service-card, .price-card { width: 90%; }
        }
    </style>
</head>
<body>

<header class="container">
    <nav class="nav">
        <a class="logo" href="#"><img src="{{ asset('assets/logo.png') }}" alt="Lavagini"></a>
        <div class="menu">
            <a href="#accueil">Accueil</a>
            <a href="#services">Services</a>
            <a href="#tarifs">Tarifs</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="buttons">
            <a class="btn btn-login" href="#">Connexion</a>
            <a class="btn btn-signup" href="#">S'inscrire</a>
        </div>
    </nav>
</header>

<section id="accueil" class="hero">
    <div class="hero-left">
        <h1>Votre véhicule lavé <span>chez vous</span>, en un clic</h1>
        <p>LAVAGINI met à votre disposition des laveurs professionnels qui se déplacent chez vous. Réservez en quelques clics, nous faisons le reste.</p>
        <a class="btn-cta" href="#tarifs">Réserver maintenant</a>
    </div>
    <div class="hero-right">
        <img src="{{ asset('assets/image1.jpg') }}" alt="Lavage voiture">
    </div>
</section>

<section id="services" class="section container">
    <h2>Nos Services</h2>
    <h3>Des services adaptés à vos <span>besoins</span></h3>
    <div class="services">
        <div class="service-card">
            <img src="{{ asset('assets/Lavage extérieur.jpg') }}" alt="Lavage extérieur">
            <h4>Lavage extérieur</h4>
            <p>Nettoyage complet de la carrosserie, jantes et vitres.</p>
        </div>
        <div class="service-card">
            <img src="{{ asset('assets/Lavage intérieur.jpg') }}" alt="Lavage intérieur">
            <h4>Lavage intérieur</h4>
            <p>Aspiration, nettoyage des sièges et surfaces intérieures.</p>
        </div>
        <div class="service-card">
            <img src="{{ asset('assets/Disponibilite flexible.png') }}" alt="Disponibilité flexible">
            <h4>Disponibilité flexible</h4>
            <p>Service disponible 7j/7, créneaux matin et soir.</p>
        </div>
        <div class="service-card">
            <img src="{{ asset('assets/Produits écologiques.jpg') }}" alt="Produits écologiques">
            <h4>Produits écologiques</h4>
            <p>Produits biodégradables respectueux de l'environnement.</p>
        </div>
    </div>
</section>

<section id="tarifs" class="section container">
    <h2>Tarifs</h2>
    <h3>Des coûts clairs et <span>détaillés</span></h3>
    <div class="pricing">
        <div class="price-card">
            <img src="{{ asset('assets/logo-tarif-noir.png') }}" alt="Lavage complet">
            <h4>Lavage complet</h4>
            <div class="sub">Intérieur + Extérieur</div>
            <div class="price">100 DH</div>
            <ul>
                <li>- Lavage carrosserie</li>
                <li>- Nettoyage jantes</li>
                <li>- Nettoyage pneus</li>
                <li>- Nettoyage vitres extérieures</li>
                <li>- Dépoussiérage habitacle</li>
                <li>- Nettoyage tapis</li>
                <li>- Aspiration sièges</li>
                <li>- Nettoyage coffre</li>
                <li>- Nettoyage vitres intérieures</li>
            </ul>
            <div class="footer-btn">Réserver</div>
        </div>
        <div class="price-card blue">
            <img src="{{ asset('assets/logo-tarif-bleu.png') }}" alt="Lavage Extra">
            <h4 style="color:#00B7FF;">Lavage Extra</h4>
            <div class="sub">Intérieur + Extérieur</div>
            <div class="price">150 DH</div>
            <ul>
                <li>- Tout du complet</li>
                <li>- Démoustiquage carrosserie</li>
                <li>- Déperlage carrosserie</li>
                <li>- Traitement anti-pluie vitres</li>
                <li>- Nettoyage plafond de toit</li>
                <li>- Traitement cuir & tissu</li>
                <li>- Parfumage habitacle (au choix)</li>
            </ul>
            <div class="footer-btn">Réserver</div>
        </div>
        <div class="price-card gold">
            <img src="{{ asset('assets/logo-tarif-dore.png') }}" alt="Lavage Premium">
            <h4 style="color:#f4b400;">Lavage Premium</h4>
            <div class="sub">Intérieur + Extérieur</div>
            <div class="price">250 DH</div>
            <ul>
                <li>- Tout de l’Extra</li>
                <li>- Protection cire longue durée</li>
                <li>- Brillant pneus premium</li>
                <li>- Shampoing sièges approfondi</li>
                <li>- Désinfection habitacle</li>
                <li>- Lavage moteur</li>
            </ul>
            <div class="footer-btn">Réserver</div>
        </div>
    </div>
</section>

<footer id="contact">
    <div class="container footer-top">
        <div>
            <h4>Contact :</h4>
            <div class="footer-item">
                <img src="{{ asset('assets/email.png') }}" alt="email">
                <span>bdforcleaning@gmail.com</span>
            </div>
            <div class="footer-item">
                <img src="{{ asset('assets/whatsapp.png') }}" alt="phone">
                <span>+212 712-176952</span>
            </div>
        </div>
        <div>
            <h4>Social Media:</h4>
            <div class="socials">
                <img src="{{ asset('assets/facebook (1).png') }}" alt="Facebook">
                <img src="{{ asset('assets/instagram.png') }}" alt="Instagram">
                <img src="{{ asset('assets/youtube.png') }}" alt="YouTube">
                <img src="{{ asset('assets/whatsapp.png') }}" alt="WhatsApp">
            </div>
        </div>
        <div>
            <h4>LIENS UTILES:</h4>
            <div>Comment réserver ?</div>
            <div>Politique de confidentialité</div>
            <div>Conditions générales</div>
        </div>
    </div>
    <div class="footer-bottom">@Lavagini2026</div>
</footer>

</body>
</html>
