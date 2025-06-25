<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Vérifier que l'utilisateur est connecté et admin
if (!isAdmin()) {
    header('Location: index.php');
    exit();
}

// Suppression d'un utilisateur (via GET ?delete_id=)
if (isset($_GET['delete_id'])) {
    $deleteId = (int)$_GET['delete_id'];
    // Empêcher la suppression de soi-même
    if ($deleteId !== $_SESSION['user_id']) {
        $stmtDel = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmtDel->execute([$deleteId]);
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Vous ne pouvez pas supprimer votre propre compte.";
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, name, email, role, average FROM students ORDER BY id ASC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Administration - School Travel</title>
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

    .dashboard-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeInUp 0.8s ease-out;
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

    .btn-success-modern {
      background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .btn-success-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
      color: white;
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

    .btn-danger-modern {
      background: linear-gradient(45deg, var(--danger-color), #f87171);
      color: white;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-danger-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
      color: white;
    }

    .btn-danger-modern:disabled {
      opacity: 0.5;
      transform: none;
      box-shadow: none;
      cursor: not-allowed;
    }

    .btn-sm {
      padding: 8px 20px;
      font-size: 0.9rem;
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

    .modern-table {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      border: none;
    }

    .modern-table thead {
      background: linear-gradient(45deg, var(--dark-color), #334155);
      color: white;
    }

    .modern-table thead th {
      border: none;
      padding: 20px 15px;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
      transition: all 0.3s ease;
      border: none;
    }

    .modern-table tbody tr:hover {
      background: rgba(16, 185, 129, 0.1);
      transform: scale(1.01);
    }

    .modern-table tbody td {
      border: none;
      padding: 15px;
      vertical-align: middle;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .role-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .role-admin {
      background: linear-gradient(45deg, var(--warning-color), #fbbf24);
      color: white;
    }

    .role-student {
      background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
      color: white;
    }

    .average-score {
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--dark-color);
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
      
      .dashboard-card {
        padding: 30px 20px;
        margin: 0 15px;
      }
      
      .modern-table {
        font-size: 0.9rem;
      }
      
      .btn-modern {
        padding: 8px 15px;
        font-size: 0.9rem;
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
        <li class="nav-item"><a class="nav-link active" href="recommendations.php"><i class="fas fa-tachometer-alt"></i> Administration</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content">
  <div class="container">
    <div class="dashboard-card">
      <h1 class="page-title">
        <i class="fas fa-users-cog"></i> Administration
      </h1>
      <p class="page-subtitle">
        Gestion des utilisateurs - School Travel
      </p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <div class="text-center mb-4">
        <a href="add_user.php" class="btn-modern btn-success-modern">
          <i class="fas fa-user-plus"></i> Ajouter un nouvel utilisateur
        </a>
      </div>

      <div class="table-responsive">
        <table class="table modern-table">
          <thead>
            <tr>
              <th><i class="fas fa-hashtag"></i> ID</th>
              <th><i class="fas fa-user"></i> Nom</th>
              <th><i class="fas fa-envelope"></i> Email</th>
              <th><i class="fas fa-user-tag"></i> Rôle</th>
              <th><i class="fas fa-chart-line"></i> Moyenne</th>
              <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><strong><?= htmlspecialchars($user['name']) ?></strong></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                  <span class="role-badge <?= $user['role'] === 'admin' ? 'role-admin' : 'role-student' ?>">
                    <?= $user['role'] === 'admin' ? 'Administrateur' : 'Étudiant' ?>
                  </span>
                </td>
                <td><span class="average-score"><?= htmlspecialchars($user['average']) ?></span></td>
                <td>
                  <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-modern btn-primary-modern btn-sm me-2">
                    <i class="fas fa-edit"></i> Modifier
                  </a>
                  <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                    <a href="dashboard.php?delete_id=<?= $user['id'] ?>" 
                       onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');" 
                       class="btn-modern btn-danger-modern btn-sm">
                      <i class="fas fa-trash"></i> Supprimer
                    </a>
                  <?php else: ?>
                    <button class="btn-modern btn-danger-modern btn-sm" disabled>
                      <i class="fas fa-trash"></i> Supprimer
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.2}s`;
    });
  });
</script>

</body>
</html>