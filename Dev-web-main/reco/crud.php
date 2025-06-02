<?php
// crud.php — CRUD complet en un seul fichier avec PDO pour la table users
session_start();
require_once "connection.php";
require_once "_menu.php";
require_once "header.php";
include "connection.php";
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé. Réservé aux administrateurs.");
}

$msg = "";

// Suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $connexion->prepare("DELETE FROM user WHERE id = ?");
    $stmt->execute([$id]);
    $msg = "✅ Utilisateur supprimé.";
}

// Modification ou ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if (!empty($_POST['id'])) {
        // Modification
        $id = $_POST['id'];
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $connexion->prepare("UPDATE user SET nom= ?, email = ?, password = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $hashedPassword, $role, $id]);
        } else {
            $stmt = $connexion->prepare("UPDATE usersSET nom = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$nom, $email, $role, $id]);
        }
        $msg = "✅ Utilisateur mis à jour.";
    } else {
        // Création
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $connexion->prepare("INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $hashedPassword, $role]);
        $msg = "✅ Nouvel utilisateur ajouté.";
    }
}

// Si on modifie un utilisateur
$userToEdit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $connexion->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->execute([$id]);
    $userToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération des utilisateurs
$stmt = $connexion->query("SELECT * FROM user ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRUD Utilisateurs</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
<div class="crud-container">
    <div style="height: 400px;"></div>

    <h1>Gestion des utilisateurs</h1>

    <?php if (!empty($msg)) echo "<p class='alert success'>$msg</p>"; ?>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $userToEdit['id'] ?? '' ?>">

        <label for="nom">Nom d'utilisateur</label>
        <input type="text" name="username" required value="<?= htmlspecialchars($userToEdit['username'] ?? '') ?>">

        <label for="email">Adresse email</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($userToEdit['email'] ?? '') ?>">

        <label for="password">Mot de passe <?= isset($userToEdit) ? '(laisser vide pour ne pas changer)' : '' ?></label>
        <input type="password" name="password">

        <label for="role">Rôle</label>
        <select name="role">
            <option value="user" <?= (isset($userToEdit) && $userToEdit['role'] === 'user') ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= (isset($userToEdit) && $userToEdit['role'] === 'admin') ? 'selected' : '' ?>>Administrateur</option>
        </select>

        <button type="submit">Enregistrer</button>
    </form>

    <hr>
    <h2>Liste des utilisateurs</h2>
    <div class="user-list-scroll">
    <?php foreach ($users as $user): ?>
        <div class="user-card">
            <h3><?= htmlspecialchars($user['nom']) ?> (<?= htmlspecialchars($user['role']) ?>)</h3>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <a href="crud.php?edit=<?= $user['id'] ?>">✏️ Modifier</a> |
            <a href="crud.php?delete=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">🗑 Supprimer</a>
        </div>
    <?php endforeach; ?>
</div>

</div>
</body>
</html>

