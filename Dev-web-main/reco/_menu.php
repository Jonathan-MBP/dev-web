<?php
session_start();
$role = $_SESSION['role'] ?? 'user';
?>
<nav class="navbar">
  <img src="image/ECE_LOGO_2021_web.png" alt="Logo du site" style="height: 40px;">
  <a href="index.php" class="site-title">Recommendation de destination B2</a>


  <ul>
    <?php if (!isset($_SESSION['user'])) { ?>
      
      <div class="profile-dropdown">
      <img src="user-icon.jpg" alt="Profil" class="profile-icon"></li>
      <ul class="dropdown-menu">
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
    <?php } else { ?>
      <!-- Conteneur principal du profil avec menu déroulant -->
<div class="profile-dropdown">
    <!-- Icône de profil (ex. image de l’utilisateur) -->

    <img 
    src="<?= !empty($_SESSION['user']['profile_picture']) ? htmlspecialchars($_SESSION['user']['profile_picture']) : 'user-icon.jpg' ?>" 
    class="profile-icon" 
    alt="Profil" />

    
    <?php if ($role === 'admin'): ?>
      <span class="admin-label">ADMIN</span>
    <?php endif; ?>

    <!-- Menu déroulant caché par défaut -->
    <ul class="dropdown-menu">
        <li><a href="profile.php">Mon profil</a></li>
        <li><a href="index.php">Mes recommendations</a></li>
        <?php if ($role === 'admin'): ?>
        <li><a href="crud.php">CRUD</a></li>
      <?php endif; ?>
        <li><a href="logout.php">Déconnexion</a></li>
    </ul>
</div>

    <?php } ?>
  </ul>
</nav>


