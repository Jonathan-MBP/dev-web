<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Vérifier que l'utilisateur est connecté et admin
if (!isAdmin()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';
$userId = (int)($_GET['id'] ?? 0);

// Vérifier que l'ID est valide
if ($userId <= 0) {
    header('Location: ad_dashboard.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: ad_dashboard.php');
    exit();
}

// Traitement du formulaire
if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'student';
    $average = floatval($_POST['average'] ?? 0);
    
    // Validation
    if (empty($name)) {
        $error = "Le nom est requis.";
    } elseif (empty($email)) {
        $error = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'email n'est pas valide.";
    } else {
        // Vérifier si l'email existe déjà (pour un autre utilisateur)
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $userId]);
        if ($checkStmt->fetch()) {
            $error = "Cet email est déjà utilisé par un autre utilisateur.";
        } else {
            // Mettre à jour l'utilisateur
            try {
                if (!empty($password)) {
                    // Avec nouveau mot de passe
                    if (strlen($password) < 6) {
                        $error = "Le mot de passe doit contenir au moins 6 caractères.";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $updateStmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, password = ?, role = ?, average = ? WHERE id = ?");
                        $updateStmt->execute([$name, $email, $hashedPassword, $role, $average, $userId]);
                        $success = "Utilisateur modifié avec succès (mot de passe mis à jour).";
                    }
                } else {
                    // Sans changer le mot de passe
                    $updateStmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, role = ?, average = ? WHERE id = ?");
                    $updateStmt->execute([$name, $email, $role, $average, $userId]);
                    $success = "Utilisateur modifié avec succès.";
                }
                
                if (empty($error)) {
                    // Recharger les données utilisateur
                    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
                    $stmt->execute([$userId]);
                    $user = $stmt->fetch();
                }
            } catch (PDOException $e) {
                $error = "Erreur lors de la modification de l'utilisateur : " . $e->getMessage();
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
  <title>Modifier l'utilisateur - School Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    :root {
      --primary-color: #2563eb;
      --secondary-color: #10b981;
      --accent-color: #f59e0b;
      --dark-color: #1e293b;
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding-top: 80px;
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

    .main-container {
      padding: 40px 0;
    }

    .page-header {
      text-align: center;
      margin-bottom: 40px;
      color: white;
    }

    .page-header h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
      animation: fadeInUp 0.8s ease-out;
    }

    .page-header p {
      font-size: 1.2rem;
      opacity: 0.9;
      animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      border: 1px solid var(--glass-border);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      padding: 40px;
      transition: all 0.3s ease;
      animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .glass-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
    }

    .form-label {
      color: white;
      font-weight: 600;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-control, .form-select {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 12px;
      color: white;
      padding: 12px 16px;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .form-control:focus, .form-select:focus {
      background: rgba(255, 255, 255, 0.15);
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
      color: white;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .form-select option {
      background: var(--dark-color);
      color: white;
    }

    .form-text {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.875rem;
    }

    .btn-primary {
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 12px;
      transition: all 0.3s ease;
      box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(37, 99, 235, 0.4);
      background: linear-gradient(45deg, #1d4ed8, #059669);
    }

    .btn-secondary {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 12px;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.5);
      color: white;
      transform: translateY(-2px);
    }

    .alert {
      border-radius: 15px;
      border: none;
      padding: 15px 20px;
      font-weight: 500;
      backdrop-filter: blur(10px);
      animation: slideIn 0.5s ease-out;
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.2);
      color: #fecaca;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.2);
      color: #a7f3d0;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .alert-info {
      background: rgba(59, 130, 246, 0.2);
      color: #bfdbfe;
      border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .info-card {
      background: var(--glass-bg);
      backdrop-filter: blur(15px);
      border-radius: 15px;
      border: 1px solid var(--glass-border);
      animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .info-card .card-header {
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 15px 15px 0 0 !important;
      color: white;
      font-weight: 700;
      padding: 20px 25px;
    }

    .info-card .card-body {
      color: white;
      padding: 25px;
    }

    .info-card .card-body p {
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .info-card .card-body strong {
      color: var(--secondary-color);
      min-width: 80px;
    }

    .back-btn {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      margin-bottom: 30px;
    }

    .back-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      transform: translateX(-5px);
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

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2.2rem;
      }
      
      .glass-card {
        padding: 25px;
        margin: 15px;
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
        <li class="nav-item"><a class="nav-link" href="ad_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-container">
  <div class="container">
    <div class="page-header">
      <h1><i class="fas fa-user-edit"></i> Modifier l'utilisateur</h1>
      <p>Gestion des informations de l'utilisateur</p>
    </div>
    
    <a href="ad_dashboard.php" class="back-btn">
      <i class="fas fa-arrow-left"></i> Retour au tableau de bord
    </a>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
      <div class="col-lg-7">
        <div class="glass-card">
          <form method="POST">
            <div class="mb-4">
              <label for="name" class="form-label">
                <i class="fas fa-user"></i> Nom complet *
              </label>
              <input type="text" class="form-control" id="name" name="name" 
                     value="<?= htmlspecialchars($user['name']) ?>" required
                     placeholder="Nom complet de l'utilisateur">
            </div>
            
            <div class="mb-4">
              <label for="email" class="form-label">
                <i class="fas fa-envelope"></i> Email *
              </label>
              <input type="email" class="form-control" id="email" name="email" 
                     value="<?= htmlspecialchars($user['email']) ?>" required
                     placeholder="adresse@email.com">
            </div>
            
            <div class="mb-4">
              <label for="password" class="form-label">
                <i class="fas fa-lock"></i> Nouveau mot de passe
              </label>
              <input type="password" class="form-control" id="password" name="password" 
                     minlength="6" placeholder="Nouveau mot de passe (optionnel)">
              <div class="form-text">
                <i class="fas fa-info-circle"></i> Laissez vide pour ne pas changer le mot de passe. Minimum 6 caractères si renseigné.
              </div>
            </div>
            
            <div class="mb-4">
              <label for="role" class="form-label">
                <i class="fas fa-user-tag"></i> Rôle
              </label>
              <select class="form-select" id="role" name="role">
                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
              </select>
            </div>
            
            <div class="mb-4">
              <label for="average" class="form-label">
                <i class="fas fa-chart-line"></i> Moyenne
              </label>
              <input type="number" class="form-control" id="average" name="average" 
                     step="0.01" min="0" max="20" value="<?= htmlspecialchars($user['average']) ?>"
                     placeholder="Note sur 20">
              <div class="form-text">
                <i class="fas fa-info-circle"></i> Entre 0 et 20
              </div>
            </div>
            
            <?php if ($userId === $_SESSION['user_id']): ?>
                <div class="alert alert-info">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Note :</strong> Vous modifiez votre propre compte. Soyez prudent avec les changements de rôle.
                </div>
            <?php endif; ?>
            
            <div class="d-flex gap-3 justify-content-end">
              <a href="ad_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Annuler
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Sauvegarder les modifications
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <div class="col-lg-5">
        <div class="info-card card">
          <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations actuelles</h5>
          </div>
          <div class="card-body">
            <p><strong><i class="fas fa-hashtag"></i> ID :</strong> <?= htmlspecialchars($user['id']) ?></p>
            <p><strong><i class="fas fa-user"></i> Nom :</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong><i class="fas fa-envelope"></i> Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong><i class="fas fa-user-tag"></i> Rôle :</strong> 
              <span class="badge <?= $user['role'] === 'admin' ? 'bg-warning' : 'bg-info' ?>">
                <?= $user['role'] === 'admin' ? 'Administrateur' : 'Utilisateur' ?>
              </span>
            </p>
            <p><strong><i class="fas fa-chart-line"></i> Moyenne :</strong> 
              <span class="badge bg-success"><?= htmlspecialchars($user['average']) ?>/20</span>
            </p>
          </div>
        </div>
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

  // Animation des éléments au focus
  document.querySelectorAll('.form-control, .form-select').forEach(element => {
    element.addEventListener('focus', function() {
      this.parentElement.style.transform = 'scale(1.02)';
      this.parentElement.style.transition = 'transform 0.2s ease';
    });
    
    element.addEventListener('blur', function() {
      this.parentElement.style.transform = 'scale(1)';
    });
  });

  // Validation en temps réel
  document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const helpText = this.nextElementSibling;
    
    if (password.length > 0 && password.length < 6) {
      this.style.borderColor = '#ef4444';
      helpText.style.color = '#fca5a5';
      helpText.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Le mot de passe doit contenir au moins 6 caractères.';
    } else if (password.length >= 6) {
      this.style.borderColor = '#10b981';
      helpText.style.color = '#86efac';
      helpText.innerHTML = '<i class="fas fa-check-circle"></i> Mot de passe valide.';
    } else {
      this.style.borderColor = 'rgba(255, 255, 255, 0.3)';
      helpText.style.color = 'rgba(255, 255, 255, 0.8)';
      helpText.innerHTML = '<i class="fas fa-info-circle"></i> Laissez vide pour ne pas changer le mot de passe. Minimum 6 caractères si renseigné.';
    }
  });

  // Animation de confirmation
  document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
    submitBtn.disabled = true;
  });
</script>
</body>
</html>