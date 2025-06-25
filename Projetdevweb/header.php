<?php
// includes/header.php
require_once 'functions.php';

// Démarre la session seulement si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>School Travel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #e0f7fa; /* Bleu clair */
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background-color: #0277bd; /* Bleu océan */
    }
    .navbar a {
      color: white !important;
    }
    main {
      flex: 1;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand text-white" href="index.php">School Travel</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navBarContent"
        aria-controls="navBarContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navBarContent">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
          <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Tableau de bord</a></li>
            <?php if (isAdmin()): ?>
              <li class="nav-item"><a class="nav-link" href="ad_dashboard.php">Admin</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="container mt-4">




