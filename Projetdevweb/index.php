<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

$error = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header("Location: ad_dashboard.php");
        } else {
            header("Location: ad_destinations.php");
        }
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion - School Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #2563eb;
      --secondary-color: #10b981;
      --accent-color: #f59e0b;
      --dark-color: #1e293b;
      --success-color: #059669;
      --warning-color: #d97706;
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

    .main-content {
      padding: 120px 0 80px;
      min-height: calc(100vh - 140px);
    }

    .welcome-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeInUp 0.8s ease-out;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeInUp 0.8s ease-out;
      max-width: 500px;
      margin: 0 auto;
    }

    .page-title {
      color: var(--dark-color);
      font-weight: 800;
      font-size: 2.5rem;
      margin-bottom: 20px;
      text-align: center;
    }

    .page-subtitle {
      color: #64748b;
      font-size: 1.1rem;
      text-align: center;
      margin-bottom: 30px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 12px 20px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1rem;
      margin-bottom: 20px;
    }

    .status-admin {
      background: linear-gradient(45deg, var(--warning-color), #fbbf24);
      color: white;
      box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
    }

    .status-student {
      background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 25px;
    }

    .btn-modern {
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      border: none;
      font-size: 1rem;
    }

    .btn-primary-modern {
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-primary-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
      color: white;
    }

    .btn-warning-modern {
      background: linear-gradient(45deg, var(--warning-color), #fbbf24);
      color: white;
      box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
    }

    .btn-warning-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(217, 119, 6, 0.4);
      color: white;
    }

    .btn-secondary-modern {
      background: linear-gradient(45deg, #6b7280, #9ca3af);
      color: white;
      box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
      color: white;
    }

    .form-control {
      border-radius: 15px;
      border: 2px solid #e2e8f0;
      padding: 15px 20px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }

    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
      background: white;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 8px;
    }

    .alert {
      border-radius: 15px;
      border: none;
      padding: 15px 20px;
      margin-bottom: 25px;
    }

    .alert-danger {
      background: linear-gradient(45deg, #ef4444, #f87171);
      color: white;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .alert-success {
      background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .register-link {
      text-align: center;
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #e2e8f0;
    }

    .register-link a {
      color: var(--secondary-color);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      color: var(--primary-color);
      text-decoration: underline;
    }

    .user-greeting {
      text-align: center;
      margin-bottom: 30px;
    }

    .user-greeting h2 {
      color: var(--dark-color);
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .user-greeting p {
      color: #64748b;
      font-size: 1.1rem;
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

    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
      }
      
      .login-card {
        padding: 30px 20px;
        margin: 0 15px;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .btn-modern {
        justify-content: center;
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
        <li class="nav-item"><a class="nav-link" href="accueil.php"><i class="fas fa-home"></i> Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="destinations.php"><i class="fas fa-map-marked-alt"></i> Destinations</a></li>
        <?php if (!isLoggedIn()): ?>
        <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Inscription</a></li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content">
  <div class="container">
    <?php if (isLoggedIn()): ?>
      <div class="welcome-card">
        <div class="user-greeting">
          <h2><i class="fas fa-user-circle"></i> Bienvenue, <?= htmlspecialchars(getUser()['username']) ?> !</h2>
          <p>Vous êtes connecté avec succès à School Travel</p>
        </div>

        <div class="text-center">
          <?php if (isAdmin()): ?>
            <div class="status-badge status-admin">
              <i class="fas fa-crown"></i> Administrateur
            </div>
            <p class="mb-4">Vous avez accès à toutes les fonctionnalités d'administration de la plateforme.</p>
          <?php else: ?>
            <div class="status-badge status-student">
              <i class="fas fa-graduation-cap"></i> Étudiant
            </div>
            <p class="mb-4">Découvrez les destinations disponibles selon vos performances académiques.</p>
          <?php endif; ?>
        </div>

        <div class="action-buttons">
          <?php if (isAdmin()): ?>
            <a href="ad_dashboard.php" class="btn-modern btn-warning-modern">
              <i class="fas fa-tachometer-alt"></i> Tableau de bord Admin
            </a>
          <?php else: ?>
            <a href="destinations.php" class="btn-modern btn-primary-modern">
              <i class="fas fa-map-marked-alt"></i> Voir les destinations
            </a>
          <?php endif; ?>
          
          <a href="logout.php" class="btn-modern btn-secondary-modern">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
          </a>
        </div>
      </div>

    <?php else: ?>
      <div class="login-card">
        <h1 class="page-title">
          <i class="fas fa-plane"></i> School Travel
        </h1>
        <p class="page-subtitle">
          Connectez-vous pour découvrir vos destinations de voyage selon vos performances académiques
        </p>

        <?php if ($error): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php">
          <div class="mb-3">
            <label for="email" class="form-label">
              <i class="fas fa-envelope"></i> Adresse email
            </label>
            <input type="email" class="form-control" name="email" id="email" required autofocus placeholder="votre@email.com">
          </div>
          
          <div class="mb-4">
            <label for="password" class="form-label">
              <i class="fas fa-lock"></i> Mot de passe
            </label>
            <input type="password" class="form-control" name="password" id="password" required placeholder="Votre mot de passe">
          </div>
          
          <button type="submit" class="btn-modern btn-primary-modern w-100">
            <i class="fas fa-sign-in-alt"></i> Se connecter
          </button>
        </form>

        <div class="register-link">
          <p class="mb-0">Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Animation de la navbar au scroll
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(30, 41, 59, 0.98)';
    } else {
      navbar.style.background = 'rgba(30, 41, 59, 0.95)';
    }
  });

  // Animation d'entrée pour les cartes
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.welcome-card, .login-card');
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.2}s`;
    });
  });
</script>

</body>
</html>