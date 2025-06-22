<?php
session_start();
?>
<?php 
require_once "_menu.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE - Campus de Genève</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background: url('https://images.unsplash.com/photo-1611312448521-0f1d383efb3d?auto=format&fit=crop&w=1600&q=80') no-repeat center center;
            background-size: cover;
            padding: 120px 20px;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
            border-radius: 0 0 30px 30px;
        }
        .section-title {
            margin: 60px 0 30px;
        }
        .image-grid img {
            border-radius: 15px;
            width: 100%;
            height: 220px;
            object-fit: cover;
        }
        footer {
            margin-top: 60px;
            padding: 20px 0;
            background: #f1f1f1;
            color: #555;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="hero text-center">
        <h1 class="display-4">Campus ECE à Genève</h1>
        <p class="lead">Un cadre d'exception entre nature, innovation et diplomatie</p>
    </div>

    <!-- Main Content -->
    <div class="container">

        <div class="section-title text-center">
            <h2 class="text-primary">Pourquoi Genève ?</h2>
            <p>Située au cœur de l’Europe, Genève est une ville cosmopolite reconnue pour son excellence académique, sa qualité de vie et son dynamisme international.</p>
        </div>

        <div class="row text-center mb-5">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">
                    🕊️ <h5>Ville internationale</h5>
                    <p>Siège de nombreuses institutions mondiales : ONU, OMS, etc.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">
                    🌄 <h5>Cadre naturel exceptionnel</h5>
                    <p>Lac Léman, montagnes, et air pur pour un quotidien inspirant.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">
                    🎓 <h5>Excellence académique</h5>
                    <p>Un environnement propice aux études exigeantes et rigoureuses.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">
                    🌐 <h5>Networking global</h5>
                    <p>Rencontres avec des étudiants, chercheurs et professionnels du monde entier.</p>
                </div>
            </div>
        </div>

        <div class="section-title text-center">
            <h2 class="text-primary">Aperçu en images</h2>
        </div>

        <div class="row image-grid mb-5">
            <div class="col-md-4 mb-4">
                <img src="https://images.unsplash.com/photo-1504457046788-241d4f3c5a84?auto=format&fit=crop&w=800&q=80" alt="Vue sur le lac Léman">
            </div>
            <div class="col-md-4 mb-4">
                <img src="https://images.unsplash.com/photo-1580657071685-63d013a2c6b2?auto=format&fit=crop&w=800&q=80" alt="Université à Genève">
            </div>
            <div class="col-md-4 mb-4">
                <img src="https://images.unsplash.com/photo-1555088460-20f0dd7d2576?auto=format&fit=crop&w=800&q=80" alt="Vie étudiante à Genève">
            </div>
        </div>

        <div class="text-center mb-5">
            <a href="formulaire.php" class="btn btn-primary btn-lg">Postuler</a>
        </div>

        <div class="text-center">
            <a href="destinations.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
        </div>
    </div>

    <footer class="text-center">
        &copy; <?= date('Y') ?> ECE | Campus de Genève - Tous droits réservés
    </footer>

</body>
</html>
