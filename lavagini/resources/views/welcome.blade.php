<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAVAGINI - Votre véhicule lavé chez vous</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #000; color: #fff; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }

        /* Navbar Sticky */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: transparent;
            transition: background 0.3s ease;
            padding: 1.2rem 5%;
        }
        .navbar.scrolled { background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(10px); }
        .navbar-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo { display: flex; flex-direction: column; align-items: center; gap: 0.3rem; }
        .logo img { width: 60px; height: 60px; filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%); }
        .logo-text { color: #00BFFF; font-weight: 800; font-size: 1.1rem; letter-spacing: 1px; }
        .nav-links { display: flex; gap: 2.5rem; }
        .nav-links a { color: #fff; font-weight: 400; font-size: 1rem; transition: color 0.3s; }
        .nav-links a:hover { color: #00BFFF; }
        .nav-buttons { display: flex; gap: 1rem; }
        .btn { padding: 0.7rem 1.8rem; border-radius: 8px; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border: none; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 191, 255, 0.4); }
        .btn-login { background: #00BFFF; color: #fff; }
        .btn-signup { background: #3333FF; color: #fff; }

        /* Hero Section */
        .hero {
            display: flex;
            min-height: 100vh;
            padding-top: 100px;
        }
        .hero-left {
            flex: 1;
            background: #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 8%;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            color: #fff;
        }
        .hero-title .highlight { color: #00BFFF; }
        .hero-description {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #ccc;
            margin-bottom: 2.5rem;
            max-width: 550px;
        }
        .btn-cta {
            background: #3333FF;
            color: #fff;
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            width: fit-content;
            transition: all 0.3s;
        }
        .btn-cta:hover {
            background: #00BFFF;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(51, 51, 255, 0.5);
        }
        .hero-right {
            flex: 1;
            background: url('{{ asset('assets/acceille.jpg') }}?v=2') center/cover no-repeat;
            position: relative;
        }
        .hero-right::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
        }

        /* Services Section */
        .section {
            padding: 6rem 5%;
            background: #000;
        }
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #00BFFF;
            margin-bottom: 0.5rem;
        }
        .section-subtitle {
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
        }
        .section-subtitle .highlight { color: #3333FF; }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .service-card {
            background: #111;
            border-radius: 30px;
            border: 1px solid #333;
            overflow: hidden;
            transition: all 0.4s ease;
            opacity: 0;
            transform: translateY(50px);
        }
        .service-card.animate {
            animation: fadeInUp 0.6s ease forwards;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .service-card:nth-child(1) { animation-delay: 0.1s; }
        .service-card:nth-child(2) { animation-delay: 0.3s; }
        .service-card:nth-child(3) { animation-delay: 0.5s; }
        .service-card:nth-child(4) { animation-delay: 0.7s; }
        .service-card:hover {
            transform: translateY(-10px);
            border-color: #00BFFF;
            box-shadow: 0 10px 40px rgba(0, 191, 255, 0.3);
        }
        .service-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        .service-content {
            padding: 1.8rem;
        }
        .service-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #00BFFF;
            margin-bottom: 1rem;
        }
        .service-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #aaa;
        }

        /* Tarifs Section */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .price-card {
            background: #111;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .price-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
        }
        .price-card.black::before { background: #000; }
        .price-card.cyan::before { background: #00BFFF; }
        .price-card.gold::before { background: #FFD700; }
        .price-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 50px rgba(0, 191, 255, 0.2);
        }
        .price-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
        }
        .price-name {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        .price-card.black .price-name { color: #fff; }
        .price-card.cyan .price-name { color: #00BFFF; }
        .price-card.gold .price-name { color: #FFD700; }
        .price-amount {
            font-size: 3rem;
            font-weight: 800;
            margin: 1.5rem 0;
        }
        .price-card.black .price-amount { color: #fff; }
        .price-card.cyan .price-amount { color: #00BFFF; }
        .price-card.gold .price-amount { color: #FFD700; }
        .price-features {
            list-style: none;
            text-align: left;
            margin: 2rem 0;
            min-height: 280px;
        }
        .price-features li {
            padding: 0.6rem 0;
            color: #ccc;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .price-features li::before {
            content: '✓';
            color: #00BFFF;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .btn-reserve {
            width: 100%;
            padding: 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }
        .price-card.black .btn-reserve {
            background: #000;
            color: #fff;
        }
        .price-card.cyan .btn-reserve {
            background: #00BFFF;
            color: #fff;
        }
        .price-card.gold .btn-reserve {
            background: #FFD700;
            color: #000;
        }
        .btn-reserve:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0, 191, 255, 0.4);
        }

        /* Footer */
        footer {
            background: #000;
            border-top: 2px solid #00BFFF;
            padding: 3rem 5% 0;
        }
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            padding-bottom: 2rem;
        }
        .footer-section h4 {
            color: #00BFFF;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .footer-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            color: #ccc;
        }
        .footer-icon {
            width: 24px;
            height: 24px;
            filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%);
        }
        .social-icons {
            display: flex;
            gap: 1rem;
        }
        .social-icon {
            width: 40px;
            height: 40px;
            filter: brightness(0) saturate(100%) invert(58%) sepia(94%) saturate(2844%) hue-rotate(169deg) brightness(103%) contrast(101%);
            transition: transform 0.3s;
            cursor: pointer;
        }
        .social-icon:hover {
            transform: scale(1.2);
        }
        .footer-links {
            list-style: none;
        }
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        .footer-links a {
            color: #ccc;
            transition: color 0.3s;
        }
        .footer-links a:hover {
            color: #00BFFF;
        }
        .footer-bottom {
            background: #00BFFF;
            text-align: center;
            padding: 1rem;
            margin: 0 -5%;
        }
        .footer-bottom p {
            color: #000;
            font-weight: 700;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero { flex-direction: column; min-height: auto; }
            .hero-left, .hero-right { flex: none; width: 100%; min-height: 50vh; }
            .hero-title { font-size: 2.5rem; }
            .nav-links { display: none; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 1rem 3%; }
            .hero-left { padding: 3rem 5%; }
            .hero-title { font-size: 2rem; }
            .section { padding: 4rem 3%; }
            .section-title { font-size: 2rem; }
            .section-subtitle { font-size: 1.4rem; }
            .services-grid, .pricing-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Navbar Sticky -->
    <nav class="navbar" id="navbar">
        <div class="navbar-content">
            <div class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="LAVAGINI">
                <span class="logo-text">LAVAGINI</span>
            </div>
            <div class="nav-links">
                <a href="#accueil">Accueil</a>
                <a href="#services">Services</a>
                <a href="#tarifs">Tarifs</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn btn-login">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-signup">S'inscrire</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="hero">
        <div class="hero-left">
            <h1 class="hero-title">Votre véhicule lavé <span class="highlight">chez vous</span>, en un clic</h1>
            <p class="hero-description">LAVAGINI met à votre disposition des laveurs professionnels qui se déplacent chez vous. Réservez en quelques clics, nous faisons le reste.</p>
            <a href="#tarifs" class="btn-cta">Réservez maintenant</a>
        </div>
        <div class="hero-right"></div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section">
        <div class="section-header">
            <h2 class="section-title">Nos Services</h2>
            <h3 class="section-subtitle">Des services adaptés à vos <span class="highlight">besoins</span></h3>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <img src="{{ asset('assets/Lavage extérieur.jpg') }}" alt="Lavage extérieur" class="service-image">
                <div class="service-content">
                    <h4 class="service-title">Lavage extérieur</h4>
                    <p class="service-description">Nettoyage complet de la carrosserie, jantes et vitres avec des produits professionnels.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="{{ asset('assets/Lavage intérieur.jpg') }}" alt="Lavage intérieur" class="service-image">
                <div class="service-content">
                    <h4 class="service-title">Lavage intérieur</h4>
                    <p class="service-description">Aspiration, nettoyage des sièges, tableau de bord et surfaces intérieures.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="{{ asset('assets/Disponibilite flexible.png') }}" alt="Disponibilité flexible" class="service-image">
                <div class="service-content">
                    <h4 class="service-title">Disponibilité flexible</h4>
                    <p class="service-description">Service disponible 7j/7 avec des créneaux matin, après-midi et soir.</p>
                </div>
            </div>
            <div class="service-card">
                <img src="{{ asset('assets/Produits écologiques.jpg') }}" alt="Produits écologiques" class="service-image">
                <div class="service-content">
                    <h4 class="service-title">Produits écologiques</h4>
                    <p class="service-description">Nous utilisons des produits biodégradables respectueux de l'environnement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tarifs Section -->
    <section id="tarifs" class="section">
        <div class="section-header">
            <h2 class="section-title">Nos Tarifs</h2>
            <h3 class="section-subtitle">Des coûts clairs et <span class="highlight">détaillés</span></h3>
        </div>
        <div class="pricing-grid">
            <div class="price-card black">
                <img src="{{ asset('resources/views/assets/logo-tarif-noir.png') }}" alt="Lavage complet" class="price-icon">
                <h3 class="price-name">Lavage complet</h3>
                <div class="price-amount">100 DH</div>
                <ul class="price-features">
                    <li>Lavage Carrosserie</li>
                    <li>Nettoyage Jantes</li>
                    <li>Nettoyage Pneus</li>
                    <li>Nettoyage Vitres Extérieures</li>
                    <li>Dépoussiérage Habitacle</li>
                    <li>Nettoyage Tapis</li>
                    <li>Aspiration Sièges</li>
                    <li>Nettoyage Coffre</li>
                    <li>Nettoyage Vitres Intérieures</li>
                </ul>
                <button class="btn-reserve">Réserver</button>
            </div>
            <div class="price-card cyan">
                <img src="{{ asset('resources/views/assets/logo-tarif-bleu.png') }}" alt="Lavage Extra" class="price-icon">
                <h3 class="price-name">Lavage Extra</h3>
                <div class="price-amount">150 DH</div>
                <ul class="price-features">
                    <li>Tout du Complet</li>
                    <li>Démoustiquage Carrosserie</li>
                    <li>Déperlage Carrosserie</li>
                    <li>Traitement Anti-pluie Vitres</li>
                    <li>Nettoyage Plafond de Toit</li>
                    <li>Traitement Cuir & Tissu</li>
                    <li>Parfumage Habitacle (au choix)</li>
                </ul>
                <button class="btn-reserve">Réserver</button>
            </div>
            <div class="price-card gold">
                <img src="{{ asset('resources/views/assets/logo-tarif-dore.png') }}" alt="Lavage Premium" class="price-icon">
                <h3 class="price-name">Lavage Premium</h3>
                <div class="price-amount">250 DH</div>
                <ul class="price-features">
                    <li>Tout de l'Extra</li>
                    <li>Protection Cire Longue Durée</li>
                    <li>Brillant Pneus Premium</li>
                    <li>Shampoing Sièges Approfondi</li>
                    <li>Désinfection Habitacle</li>
                    <li>Lavage Moteur</li>
                </ul>
                <button class="btn-reserve">Réserver</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact</h4>
                <div class="footer-item">
                    <img src="{{ asset('resources/views/assets/email.png') }}" alt="Email" class="footer-icon">
                    <span>bdforcleaning@gmail.com</span>
                </div>
                <div class="footer-item">
                    <img src="{{ asset('resources/views/assets/pomme.png') }}" alt="Téléphone" class="footer-icon">
                    <span>+212 712-178952</span>
                </div>
            </div>
            <div class="footer-section">
                <h4>Social Media</h4>
                <div class="social-icons">
                    <img src="{{ asset('resources/views/assets/whatsapp.png') }}" alt="WhatsApp" class="social-icon">
                    <img src="{{ asset('resources/views/assets/youtube.png') }}" alt="YouTube" class="social-icon">
                    <img src="{{ asset('resources/views/assets/instagram.png') }}" alt="Instagram" class="social-icon">
                    <img src="{{ asset('resources/views/assets/facebook (1).png') }}" alt="Facebook" class="social-icon">
                </div>
            </div>
            <div class="footer-section">
                <h4>LIENS UTILES</h4>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Conditions générales</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© Lavagini 2026</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Service cards animation on scroll
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.service-card').forEach(card => {
            observer.observe(card);
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>
</html>
