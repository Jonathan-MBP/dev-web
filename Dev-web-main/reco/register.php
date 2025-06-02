<?php
session_start(); // Important : à mettre tout en haut
require_once "_menu.php";
require_once "header.php";
require_once "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'] ?? null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $username = $_POST['username'] ?? null;

    // Vérification si l'email existe déjà
    $sqlCheck = "SELECT COUNT(*) FROM user WHERE email = :email";
    $stmtCheck = $connexion->prepare($sqlCheck);
    $stmtCheck->execute([':email' => $email]);
    if ($stmtCheck->fetchColumn() > 0) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        header("Location: register.php");
        exit();
    }

    try {
        $sql = "INSERT INTO user (email, password, username) VALUES (:email, :password, :username)";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password,
            ':username' => $username,
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

<body>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert success">
        <strong>✅ Succès :</strong> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php elseif (isset($_SESSION['error'])): ?>
    <div class="alert error">
        <strong>❌ Erreur :</strong> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>