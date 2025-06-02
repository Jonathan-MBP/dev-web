<?php
session_start();
require_once "_menu.php";
require_once "header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupérer la moyenne depuis la session (ou la base si tu préfères)
$moyenne = $_SESSION['moyenne'] ?? null;

// Exemple de logique pour donner des recommandations
$recommandations = [];
if ($moyenne >= 15) {
    $recommandations = ["Londres", "New York", "Tokyo"];
} elseif ($moyenne >= 12) {
    $recommandations = ["Berlin", "Madrid", "Rome"];
} else {
    $recommandations = ["Lisbonne", "Prague", "Athènes"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes recommandations de voyage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Destinations recommandées pour vous</h2>
    <p>Voici les destinations en fonction de votre moyenne : <strong><?= htmlspecialchars($moyenne) ?></strong></p>
    <ul class="list-group">
        <?php foreach ($recommandations as $ville): ?>
            <li class="list-group-item"><?= htmlspecialchars($ville) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>