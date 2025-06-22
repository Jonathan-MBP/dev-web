<?php
session_start(); // Important : à mettre tout en haut
require_once "_menu.php";
require_once "header.php";
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'] ?? null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $username = $_POST['username'] ?? null;
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $sexe = $_POST['sexe'] ?? null;
    $moyenne = floatval($_POST['moyenne'] ?? 0);

    // Validation des données
    if (empty($email) || empty($username) || empty($first_name) || empty($last_name)) {
        $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
        header("Location: register.php");
        exit();
    }

    // Vérification si l'email existe déjà
    $sqlCheck = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmtCheck = $connexion->prepare($sqlCheck);
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetchColumn() > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header("Location: register.php");
        exit();
    }

    try {
        // D'abord, modifier la structure de la table pour ajouter la colonne moyenne
        $sqlAlter = "ALTER TABLE users ADD COLUMN IF NOT EXISTS moyenne DECIMAL(4,2) DEFAULT 0.00";
        $connexion->exec($sqlAlter);

        $sql = "INSERT INTO users (email, password, username, first_name, last_name, phone, sexe, moyenne, role) 
                VALUES (:email, :password, :username, :first_name, :last_name, :phone, :sexe, :moyenne, 'user')";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password,
            ':username' => $username,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':phone' => $phone,
            ':sexe' => $sexe,
            ':moyenne' => $moyenne,
        ]);

        $_SESSION['success'] = "Utilisateur bien créé !";
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="form-container">
<div class="container">

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ <strong>Succès :</strong> <?= htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ <strong>Erreur :</strong> <?= htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4 text-primary">🎓 Créer un compte</h2>
            <form action="register.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">Prénom *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Nom *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur *</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe *</label>
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <div class="form-text">Le mot de passe doit contenir au moins 6 caractères.</div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="0123456789">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sexe" class="form-label">Genre</label>
                        <select class="form-select" id="sexe" name="sexe">
                            <option value="">Choisir...</option>
                            <option value="homme">Homme</option>
                            <option value="femme">Femme</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="moyenne" class="form-label">Moyenne générale (/20) *</label>
                    <input type="number" step="0.01" min="0" max="20" class="form-control" id="moyenne" name="moyenne" required>
                    <div class="form-text">Votre moyenne déterminera les campus accessibles.</div>
                </div>
                
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                </div>
                
                <div class="text-center">
                    <p class="mb-0">Déjà un compte ? <a href="login.php" class="text-decoration-none">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>