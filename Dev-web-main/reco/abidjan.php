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
    <title>ECE - Campus de Abidjan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background: url('https://images.unsplash.com/photo-1603420919983-3dc5c432f09b?auto=format&fit=crop&w=1600&q=80') no-repeat center center;
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
    <h1 class="display-4">Campus ECE à Abidjan</h1>
    <p class="lead">Entre innovation africaine, dynamisme économique et culture vibrante</p>
</div>

<!-- Contenu principal -->
<div class="container">

    <!-- Pourquoi choisir Abidjan -->
    <div class="section-title text-center">
        <h2 class="text-primary">Pourquoi Abidjan ?</h2>
        <p>Abidjan, capitale économique de la Côte d'Ivoire, est un pôle technologique et universitaire en pleine croissance, au cœur de l'Afrique de l'Ouest.</p>
    </div>

    <div class="row text-center">
        <div class="col-md-3">
            <div class="feature-box">
                🌍<h5 class="mt-3">Croissance africaine</h5>
                <p>Un centre d’innovation et de développement économique sur le continent africain.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🎓<h5 class="mt-3">Dynamisme académique</h5>
                <p>Des partenariats avec des universités locales et un corps étudiant multiculturel.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🏙️<h5 class="mt-3">Capitale économique</h5>
                <p>Un tissu économique riche offrant de nombreuses opportunités de stage et d’emploi.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="feature-box">
                🎶<h5 class="mt-3">Culture et énergie</h5>
                <p>Une ville vivante, rythmée par la musique, l’art et les innovations sociales.</p>
            </div>
        </div>
    </div>

    <!-- Galerie d'images -->
    <div class="section-title text-center">
        <h2 class="text-primary">Aperçu du campus et de la ville</h2>
    </div>

    <div class="row image-grid mb-5">
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1594572160964-fdc8a4be7b52?auto=format&fit=crop&w=800&q=80" alt="Vue d'Abidjan">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1614642264761-efdf18efdc0b?auto=format&fit=crop&w=800&q=80" alt="Campus ou université à Abidjan">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1632066913372-e1d28e3051d2?auto=format&fit=crop&w=800&q=80" alt="Vie étudiante en Côte d'Ivoire">
        </div>
    </div>

    <!-- Bouton retour -->

    <div class="text-center mb-5">
        <a href="formulaire.php" class="btn btn-primary btn-lg">Postuler</a>
    </div>

    <div class="text-center">
        <a href="accueil.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
    </div>
</div>

<!-- Pied de page -->
<footer class="text-center">
    &copy; <?= date('Y') ?> ECE | Campus de Abidjan - Tous droits réservés
</footer>

</body>
</html>
