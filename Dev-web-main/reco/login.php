<?php
session_start();
require_once "_menu.php"; 
require_once "header.php";
require_once "connection.php";
?>
<body>
<div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
            
        </form>
        <?php if (isset($_SESSION['success'])): ?>
               <div class="alert success">
                    <strong>✅ Succès :</strong> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
        <?php endif; ?>
        <?php



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //Connexion à la BDD
    require_once "connection.php";

    //Recupérer valeurs depuis le form
    $password = $_POST['password'];
    $email = $_POST['email'];


    //Recherche si user existe
    $verification = "SELECT * FROM user WHERE email = :email";
    $stmtVerif = $connexion->prepare($verification);
    $stmtVerif->execute([
        ':email' => $email
    ]);
    $user = $stmtVerif->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur non trouvé";
        exit(0);
    }
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $user['role'];

        header("Location: index.php");
        ?>
        <div class="alert-box success">
            <strong>✅ Succès :</strong>
            <ul>Vous êtes connecté</ul>
        </div>
        <?php
    } else {
        ?>
        <div class="alert-box error">
            <strong>❌ Erreur :</strong>
            <ul><li>Mot de passe ou utilisateur incorrect</li></ul>
        </div>
        <?php
        exit();
    }
    

   

}
$msg = isset($_GET['msg']) ? $_GET['msg'] : false;

?>
    </div>

    