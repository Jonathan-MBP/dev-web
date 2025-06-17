<?php
session_start();
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
            background: url('https://images.unsplash.com/photo-1563291056-e8d7db3f3d7a?auto=format&fit=crop&w=1600&q=80') no-repeat center center;
            background-size: cover;
            padding: 140px 20px;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
            border-radius: 0 0 30px 30px;
        }
        .section-title {
            margin: 60px 0 30px;
        }
        .feature-box {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px 20px;
            margin-bottom: 30px;
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

<!-- Hero Banner -->
<div class="hero text-center">
    <h1 class="display-4">Campus ECE à Genève</h1>
    <p class="lead">Innovation, nature et ouverture internationale au cœur de la Suisse</p>
</div>

<!-- Contenu principal -->
<div class="container">

    <!-- Pourquoi choisir Genève -->
    <div class="section-title text-center">
        <h2 class="text-primary">Pourquoi Genève ?</h2>
        <p>Un campus international dans une ville au rayonnement mondial, au bord du Lac Léman et au pied des Alpes.</p>
    </div>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="feature-box">
                🕊️<h5 class="mt-3">Ville internationale</h5>
                <p>Centre mondial de la diplomatie, accueil d’institutions comme l’ONU et l’OMS.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🌄<h5 class="mt-3">Cadre naturel</h5>
                <p>Un environnement paisible entre lacs, montagnes et forêts.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🎓<h5 class="mt-3">Excellence académique</h5>
                <p>Un environnement rigoureux, axé sur l'innovation et la recherche.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🌐<h5 class="mt-3">Ouverture mondiale</h5>
                <p>Partenariats universitaires, événements internationaux et networking global.</p>
            </div>
        </div>
    </div>

    <!-- Galerie d'images -->
    <div class="section-title text-center">
        <h2 class="text-primary">Aperçu du campus et de la ville</h2>
    </div>

    <div class="row image-grid mb-5">
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1561451210-d8e1e3e2188a?auto=format&fit=crop&w=800&q=80" alt="Lac Léman">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1565691002024-3f5f94fcd5c7?auto=format&fit=crop&w=800&q=80" alt="Université de Genève">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1610382384416-4f6c2ee9f897?auto=format&fit=crop&w=800&q=80" alt="Vie étudiante à Genève">
        </div>
    </div>

    <!-- Bouton de retour -->

    <div class="text-center mb-5">
        <a href="formulaire.php" class="btn btn-primary btn-lg">Postuler</a>
    </div>

    <div class="text-center">
        <a href="destinations.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
    </div>
</div>

<!-- Pied de page -->
<footer class="text-center">
    &copy; <?= date('Y') ?> ECE | Campus de Genève - Tous droits réservés
</footer>

</body>
</html>
