<?php
session_start();
require_once "_menu.php";
require_once "connection.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Récupérer la moyenne enregistrée dans la base de données
try {
    $stmt = $connexion->prepare("SELECT moyenne FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $moyenne_utilisateur = $result['moyenne'] ?? 0;
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la récupération de la moyenne : " . $e->getMessage();
    $moyenne_utilisateur = 0;
}

// Liste des villes disponibles avec leur moyenne minimale
$villes = [
    [
        'nom' => 'Abidjan',
        'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',
        'description' => "Campus moderne en Côte d'Ivoire...",
        'page' => 'abidjan.php',
        'moyenne_min' => 10
    ],
    [
        'nom' => 'Barcelone',
        'image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=600&q=80',
        'description' => "Ville festive et cosmopolite...",
        'page' => 'barcelone.php',
        'moyenne_min' => 12
    ],
    [
        'nom' => 'Genève',
        'image' => 'https://images.unsplash.com/photo-1523531294919-4bcd7c65e216?auto=format&fit=crop&w=600&q=80',
        'description' => "Ville internationale par excellence...",
        'page' => 'geneve.php',
        'moyenne_min' => 13
    ],
    [
        'nom' => 'Lausanne',
        'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80',
        'description' => "Ville universitaire suisse dynamique...",
        'page' => 'lausanne.php',
        'moyenne_min' => 11
    ],
    [
        'nom' => 'Londre',
        'image' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=600&q=80',
        'description' => "Capitale vibrante et multiculturelle...",
        'page' => 'londres.php',
        'moyenne_min' => 14
    ],
    [
        'nom' => 'Monaco',
        'image' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?auto=format&fit=crop&w=600&q=80',
        'description' => "Luxueux, ensoleillé, cadre privilégié...",
        'page' => 'monaco.php',
        'moyenne_min' => 15
    ],
    [
        'nom' => 'Munich',
        'image' => 'https://images.unsplash.com/photo-1595867818082-083862f3d630?auto=format&fit=crop&w=600&q=80',
        'description' => "Ville allemande dynamique et universitaire...",
        'page' => 'munich.php',
        'moyenne_min' => 12
    ]
];

// Filtrer les villes accessibles selon la moyenne de l'utilisateur
$villes_visibles = array_filter($villes, function($ville) use ($moyenne_utilisateur) {
    return $moyenne_utilisateur >= $ville['moyenne_min'];
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations accessibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ville-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-success">
        🎓 Campus accessibles avec votre moyenne (<?= htmlspecialchars($moyenne_utilisateur) ?>/20)
    </h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($villes_visibles)): ?>
            <?php foreach ($villes_visibles as $ville): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($ville['image']) ?>" 
                             alt="<?= htmlspecialchars($ville['nom']) ?>" 
                             class="ville-img card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-center">
                                <?= htmlspecialchars($ville['nom']) ?>
                            </h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars($ville['description']) ?></p>
                            <div class="text-center mt-auto">
                                <small class="text-muted">Moyenne requise: <?= $ville['moyenne_min'] ?>/20</small><br>
                                <a href="<?= htmlspecialchars($ville['page']) ?>" class="btn btn-primary mt-2">
                                    Visiter le campus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">
                    <h4>Aucun campus accessible</h4>
                    <p>Votre moyenne actuelle (<?= htmlspecialchars($moyenne_utilisateur) ?>/20) ne permet pas d'accéder aux campus disponibles.</p>
                    <p>Continuez vos efforts pour améliorer vos résultats !</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <a href="accueil.php" class="btn btn-primary">⬅ Retour à l'accueil</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>