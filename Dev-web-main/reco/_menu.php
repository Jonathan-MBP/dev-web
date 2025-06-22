<?php
session_start();
$role = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-icon {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .admin-label {
            font-size: 0.8rem;
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 5px;
            margin-left: 8px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="image/ECE_LOGO_2021_web.png" alt="Logo du site" style="height: 40px;">
            <a class="navbar-brand" href="accueil.php">TBD Travel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="accueil.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Mon profil</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
                     
                <div class="profile-dropdown">
                    <img src="user-icon.jpg" alt="Profil" class="profile-icon" style="height: 40px;">
                    <ul class="dropdown-menu">
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                    <?php if ($role === 'admin'): ?>
                    <span class="admin-label">ADMIN</span>
                    <?php endif; ?>

                    <!-- Menu déroulant caché par défaut -->

                    <img 
                    src="<?= !empty($_SESSION['user']['profile_picture']) ? htmlspecialchars($_SESSION['user']['profile_picture']) : 'user-icon.jpg' ?>" 
                    class="profile-icon" 
                    alt="Profil" 
                    style="height: 40px;"/>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Mon profil</a></li>
                        <li><a href="index.php">Mes recommendations</a></li>
                        <?php if ($role === 'admin'): ?>
                        <li><a href="crud.php">CRUD</a></li>
                    <?php endif; ?>
                        <li><a href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>

                <div class="profile-dropdown">
                    <!-- Icône de profil (ex. image de l’utilisateur) -->

                    
                </div>
        </div>
    </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
