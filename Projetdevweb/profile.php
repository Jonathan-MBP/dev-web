<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Récupérer les informations actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$currentUser = $stmt->fetch();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($name) || empty($email)) {
        $error = "Le nom et l'email sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    } else {
        // Vérifier si l'email existe déjà (sauf pour l'utilisateur actuel)
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé par un autre compte.";
        } else {
            // Si changement de mot de passe demandé
            if (!empty($newPassword)) {
                if (empty($currentPassword)) {
                    $error = "Veuillez saisir votre mot de passe actuel pour le modifier.";
                } elseif (!password_verify($currentPassword, $currentUser['password'])) {
                    $error = "Mot de passe actuel incorrect.";
                } elseif (strlen($newPassword) < 6) {
                    $error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
                } elseif ($newPassword !== $confirmPassword) {
                    $error = "La confirmation du nouveau mot de passe ne correspond pas.";
                } else {
                    // Mise à jour avec nouveau mot de passe
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $hashedPassword, $_SESSION['user_id']]);
                    $_SESSION['username'] = $name; // Mettre à jour la session
                    $success = "Profil et mot de passe mis à jour avec succès !";
                }
            } else {
                // Mise à jour sans changement de mot de passe
                $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $_SESSION['user_id']]);
                $_SESSION['username'] = $name; // Mettre à jour la session
                $success = "Profil mis à jour avec succès !";
            }

            // Recharger les données utilisateur après mise à jour
            if ($success) {
                $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $currentUser = $stmt->fetch();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mon Profil - School Travel</title>
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
      --danger-color: #ef4444;
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

    .profile-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .profile-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeInUp 0.8s ease-out;
      margin-bottom: 30px;
    }

    .profile-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      color: white;
      margin: 0 auto 20px;
      box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    .page-title {
      color: var(--dark-color);
      font-weight: 800;
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .page-subtitle {
      color: #64748b;
      font-size: 1.1rem;
      margin-bottom: 20px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 8px 16px;
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.9rem;
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

    .info-section {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeInUp 0.8s ease-out;
      margin-bottom: 20px;
    }

    .section-title {
      color: var(--dark-color);
      font-weight: 700;
      font-size: 1.5rem;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .info-item {
      background: rgba(248, 250, 252, 0.8);
      padding: 20px;
      border-radius: 15px;
      border-left: 4px solid var(--secondary-color);
    }

    .info-label {
      font-weight: 600;
      color: #64748b;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }

    .info-value {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-color);
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
      display: flex;
      align-items: center;
      gap: 8px;
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

    .alert {
      border-radius: 15px;
      border: none;
      padding: 15px 20px;
      margin-bottom: 25px;
    }

    .alert-success {
      background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .alert-danger {
      background: linear-gradient(45deg, #ef4444, #f87171);
      color: white;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .password-section {
      background: rgba(255, 248, 220, 0.3);
      border-radius: 15px;
      padding: 20px;
      margin-top: 30px;
      border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .toggle-password {
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .toggle-password:hover {
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

    @media (max-width: 768px) {
      .page-title {
        font-size: 2rem;
      }
      
      .profile-card, .info-section {
        padding: 30px 20px;
        margin: 0 15px 20px;
      }
      
      .info-grid {
        grid-template-columns: 1fr;
      }
      
      .profile-avatar {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
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
        <?php if (isAdmin()): ?>
        <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Administration</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content">
  <div class="container">
    <div class="profile-container">
      
      <!-- En-tête du profil -->
      <div class="profile-card">
        <div class="profile-header">
          <div class="profile-avatar">
            <i class="fas fa-user"></i>
          </div>
          <h1 class="page-title"><?= htmlspecialchars($currentUser['name']) ?></h1>
          <p class="page-subtitle">Membre depuis le <?= date('d/m/Y', strtotime($currentUser['created_at'] ?? 'now')) ?></p>
          <div class="status-badge <?= $currentUser['role'] === 'admin' ? 'status-admin' : 'status-student' ?>">
            <i class="fas fa-<?= $currentUser['role'] === 'admin' ? 'crown' : 'graduation-cap' ?>"></i>
            <?= $currentUser['role'] === 'admin' ? 'Administrateur' : 'Étudiant' ?>
          </div>
        </div>
      </div>

      <!-- Informations actuelles -->
      <div class="info-section">
        <h2 class="section-title">
          <i class="fas fa-info-circle"></i> Informations actuelles
        </h2>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Nom complet</div>
            <div class="info-value"><?= htmlspecialchars($currentUser['name']) ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Adresse email</div>
            <div class="info-value"><?= htmlspecialchars($currentUser['email']) ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Rôle</div>
            <div class="info-value"><?= $currentUser['role'] === 'admin' ? 'Administrateur' : 'Étudiant' ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Moyenne académique</div>
            <div class="info-value"><?= htmlspecialchars($currentUser['average']) ?>/20</div>
          </div>
        </div>
      </div>

      <!-- Formulaire de modification -->
      <div class="info-section">
        <h2 class="section-title">
          <i class="fas fa-edit"></i> Modifier mes informations
        </h2>

        <?php if ($success): ?>
          <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="profile.php">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="name" class="form-label">
                <i class="fas fa-user"></i> Nom complet
              </label>
              <input type="text" class="form-control" name="name" id="name" 
                     value="<?= htmlspecialchars($currentUser['name']) ?>" required>
            </div>
            
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">
                <i class="fas fa-envelope"></i> Adresse email
              </label>
              <input type="email" class="form-control" name="email" id="email" 
                     value="<?= htmlspecialchars($currentUser['email']) ?>" required>
            </div>
          </div>

          <div class="password-section">
            <h5 class="mb-3">
              <i class="fas fa-lock"></i> Modifier le mot de passe (optionnel)
            </h5>
            
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="current_password" class="form-label">
                  <i class="fas fa-key"></i> Mot de passe actuel
                </label>
                <div class="position-relative">
                  <input type="password" class="form-control" name="current_password" id="current_password" 
                         placeholder="Mot de passe actuel">
                  <button type="button" class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" 
                          onclick="togglePassword('current_password')">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="new_password" class="form-label">
                  <i class="fas fa-lock"></i> Nouveau mot de passe
                </label>
                <div class="position-relative">
                  <input type="password" class="form-control" name="new_password" id="new_password" 
                         placeholder="Nouveau mot de passe">
                  <button type="button" class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" 
                          onclick="togglePassword('new_password')">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="confirm_password" class="form-label">
                  <i class="fas fa-check"></i> Confirmer le mot de passe
                </label>
                <div class="position-relative">
                  <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                         placeholder="Confirmer le mot de passe">
                  <button type="button" class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3" 
                          onclick="togglePassword('confirm_password')">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn-modern btn-primary-modern me-3">
              <i class="fas fa-save"></i> Sauvegarder les modifications
            </button>
            <a href="accueil.php" class="btn-modern btn-secondary-modern">
              <i class="fas fa-times"></i> Annuler
            </a>
          </div>
        </form>
      </div>

    </div>
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
    const cards = document.querySelectorAll('.profile-card, .info-section');
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.2}s`;
    });
  });

  // Fonction pour afficher/masquer les mots de passe
  function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    
    if (field.type === 'password') {
      field.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      field.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }

  // Validation en temps réel des mots de passe
  document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword && confirmPassword && newPassword !== confirmPassword) {
      this.style.borderColor = '#ef4444';
    } else {
      this.style.borderColor = '#e2e8f0';
    }
  });
</script>

</body>
</html>