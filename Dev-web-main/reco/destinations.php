<?php
session_start();

// Exemple : moyenne simulée
$_SESSION['moyenne'] = 12.5;
$moyenne_utilisateur = $_SESSION['moyenne'];

// Liste des villes avec moyenne minimale requise
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
        'image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=600&q=80',
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
        'nom' => 'Londres',
        'image' => 'https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=600&q=80',
        'description' => "Capitale vibrante et multiculturelle...",
        'page' => 'londre.php',
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
        'image' => 'https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=600&q=80',
        'description' => "Ville allemande dynamique et universitaire...",
        'page' => 'munich.php',
        'moyenne_min' => 12
    ]
];

// Filtrer selon la moyenne
$villes_visibles = array_filter($villes, function($ville) use ($moyenne_utilisateur) {
    return $moyenne_utilisateur >= $ville['moyenne_min'];
});
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Destinations accessibles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .ville-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-success">🎓 Campus accessibles avec votre moyenne (<?= $moyenne_utilisateur ?>/20)</h2>

    <div class="row">
        <?php if (count($villes_visibles) > 0): ?>
            <?php foreach ($villes_visibles as $ville): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="<?= htmlspecialchars($ville['image']) ?>" alt="<?= htmlspecialchars($ville['nom']) ?>" class="ville-img">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?= htmlspecialchars($ville['page']) ?>" class="nav-link"><?= htmlspecialchars($ville['nom']) ?></a>
                            </h5>
                            <p class="card-text"><?= htmlspecialchars($ville['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Aucun campus n’est accessible avec votre moyenne actuelle.</div>
            </div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-4">
        <a href="accueil.php" class="btn btn-primary">⬅ Retour à l'accueil</a>
    </div>
</div>

</body>
</html>
