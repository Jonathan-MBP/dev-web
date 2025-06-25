<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bienvenue sur School Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #2563eb;
      --secondary-color: #10b981;
      --accent-color: #f59e0b;
      --dark-color: #1e293b;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background: rgba(30, 41, 59, 0.95) !important;
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: #10b981 !important;
    }

    .nav-link {
      color: white !important;
      font-weight: 500;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-link:hover {
      color: #10b981 !important;
      transform: translateY(-2px);
    }

    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 50%;
      background-color: #10b981;
      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .nav-link:hover::after {
      width: 100%;
    }

    .hero {
      padding: 120px 0;
      text-align: center;
      background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)),
                  url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, rgba(37, 99, 235, 0.3), rgba(16, 185, 129, 0.3));
      z-index: 1;
    }

    .hero .container {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-size: 4rem;
      font-weight: 800;
      margin-bottom: 2rem;
      animation: fadeInUp 1s ease-out;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
    }

    .hero p {
      font-size: 1.4rem;
      margin-bottom: 2rem;
      animation: fadeInUp 1s ease-out 0.3s both;
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    .btn-hero {
      background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
      border: none;
      padding: 15px 40px;
      font-size: 1.2rem;
      font-weight: 600;
      border-radius: 50px;
      color: white;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      animation: fadeInUp 1s ease-out 0.6s both;
      box-shadow: 0 8px 30px rgba(16, 185, 129, 0.3);
    }

    .btn-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 40px rgba(16, 185, 129, 0.4);
      color: white;
    }

    .features-section {
      padding: 100px 0;
      background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .feature-card {
      background: white;
      border-radius: 20px;
      padding: 40px 30px;
      text-align: center;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      border: none;
      height: 100%;
      position: relative;
      overflow: hidden;
    }

    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
      font-size: 3rem;
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 20px;
    }

    .feature-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 15px;
    }

    .feature-text {
      color: #64748b;
      line-height: 1.6;
    }

    .destinations-preview {
      padding: 100px 0;
      background: linear-gradient(135deg, var(--dark-color) 0%, #334155 100%);
      color: white;
    }

    .destination-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.2);
      height: 100%;
    }

    .destination-card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.15);
    }

    .destination-flag {
      font-size: 3rem;
      margin-bottom: 15px;
    }

    .cta-section {
      padding: 80px 0;
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      color: white;
      text-align: center;
    }

    footer {
      background: var(--dark-color);
      color: white;
      padding: 40px 0;
      text-align: center;
    }

    .footer-links {
      margin-bottom: 20px;
    }

    .footer-links a {
      color: #94a3b8;
      text-decoration: none;
      margin: 0 15px;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--secondary-color);
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    .floating {
      animation: float 3s ease-in-out infinite;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero p {
        font-size: 1.1rem;
      }
      
      .btn-hero {
        padding: 12px 30px;
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="accueil.php">
      <i class="fas fa-plane"></i> School Travel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#accueil"><i class="fas fa-home"></i> Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="destinations.php"><i class="fas fa-map-marked-alt"></i> Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
        <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero" id="accueil">
  <div class="container">
    <div class="floating">
      <i class="fas fa-globe-americas" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.9;"></i>
    </div>
    <h1 class="display-4 fw-bold">Voyagez avec School Travel !</h1>
    <p class="lead">Découvrez les destinations de rêve offertes selon votre mérite scolaire</p>
    <a href="register.php" class="btn-hero">
      <i class="fas fa-rocket"></i> Commencer l'aventure
    </a>
  </div>
</section>

<section class="features-section">
  <div class="container">
    <div class="row text-center mb-5">
      <div class="col-12">
        <h2 class="display-5 fw-bold mb-3" style="color: var(--dark-color);">Pourquoi School Travel ?</h2>
        <p class="lead text-muted">Nous récompensons l'excellence scolaire avec des voyages inoubliables</p>
      </div>
    </div>
    
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-star"></i>
          </div>
          <h3 class="feature-title">Récompenses Méritées</h3>
          <p class="feature-text">Les meilleurs étudiants ont droit aux destinations les plus prestigieuses. Votre travail acharné est enfin récompensé !</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <h3 class="feature-title">7 Destinations de Rêve</h3>
          <p class="feature-text">Tokyo, Londres, Barcelone, New York... Explorez les plus belles villes du monde selon vos résultats !</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-user-check"></i>
          </div>
          <h3 class="feature-title">Facile à utiliser</h3>
          <p class="feature-text">Inscrivez-vous, entrez votre moyenne, et découvrez instantanément où vous pouvez voyager !</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="destinations-preview" id="destinations">
  <div class="container">
    <div class="row text-center mb-5">
      <div class="col-12">
        <h2 class="display-5 fw-bold mb-3">Nos Destinations</h2>
        <p class="lead">Chaque destination correspond à un niveau d'excellence</p>
      </div>
    </div>
    
    <div class="row g-4">
      <div class="col-lg-3 col-md-6">
        <div class="destination-card">
          <div class="destination-flag">🇯🇵</div>
          <h4>Tokyo</h4>
          <p class="mb-0">Excellence : 18-20/20</p>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="destination-card">
          <div class="destination-flag">🇬🇧</div>
          <h4>Londres</h4>
          <p class="mb-0">Très bien : 16-17/20</p>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="destination-card">
          <div class="destination-flag">🇪🇸</div>
          <h4>Barcelone</h4>
          <p class="mb-0">Bien : 14-15/20</p>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-6">
        <div class="destination-card">
          <div class="destination-flag">🇫🇷</div>
          <h4>Paris</h4>
          <p class="mb-0">Assez bien : 12-13/20</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto text-center">
        <h2 class="display-5 fw-bold mb-4">Prêt à découvrir votre destination ?</h2>
        <p class="lead mb-4">Rejoignez des milliers d'étudiants qui ont déjà vécu l'aventure School Travel</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="register.php" class="btn btn-light btn-lg px-4">
            <i class="fas fa-user-plus"></i> S'inscrire maintenant
          </a>
          <a href="index.php" class="btn btn-outline-light btn-lg px-4">
            <i class="fas fa-sign-in-alt"></i> Se connecter
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <div class="footer-links">
      <a href="#"><i class="fas fa-info-circle"></i> À propos</a>
      <a href="#"><i class="fas fa-envelope"></i> Contact</a>
      <a href="#"><i class="fas fa-shield-alt"></i> Confidentialité</a>
      <a href="#"><i class="fas fa-file-contract"></i> Conditions</a>
    </div>
    <div class="d-flex justify-content-center gap-3 mb-3">
      <a href="#" class="text-secondary"><i class="fab fa-facebook-f fa-lg"></i></a>
      <a href="#" class="text-secondary"><i class="fab fa-twitter fa-lg"></i></a>
      <a href="#" class="text-secondary"><i class="fab fa-instagram fa-lg"></i></a>
      <a href="#" class="text-secondary"><i class="fab fa-linkedin-in fa-lg"></i></a>
    </div>
    <p class="mb-0">&copy; 2024 School Travel. Tous droits réservés.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Animation au scroll
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(30, 41, 59, 0.98)';
    } else {
      navbar.style.background = 'rgba(30, 41, 59, 0.95)';
    }
  });

  // Animation des cartes au scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
      }
    });
  }, observerOptions);

  document.querySelectorAll('.feature-card, .destination-card').forEach(card => {
    card.style.opacity = '0';
    observer.observe(card);
  });
</script>
</body>
</html>