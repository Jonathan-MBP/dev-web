<?php 
require_once "_menu.php";
?>

<!-- Page : Campus de Londres -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE - Campus de Londres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .hero {
            background: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=80') no-repeat center center;
            background-size: cover;
            padding: 120px 20px;
            color: white;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.5);
            border-radius: 0 0 30px 30px;
        }
        .section-title { margin: 60px 0 30px; }
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
    <div class="hero text-center">
        <h1 class="display-4">Campus ECE à Londres</h1>
        <p class="lead">Étudiez dans l'une des capitales les plus influentes du monde</p>
    </div>
    <div class="container">
        <div class="section-title text-center">
            <h2 class="text-primary">Pourquoi Londres ?</h2>
            <p>Ville multiculturelle, connectée au monde et pleine d’opportunités académiques et professionnelles.</p>
        </div>
        <div class="row text-center mb-5">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">🎓 <h5>Universités de renom</h5><p>Accès à des institutions prestigieuses mondialement reconnues.</p></div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">🌍 <h5>Ville internationale</h5><p>Un carrefour global de cultures, de langues et de carrières.</p></div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">🚇 <h5>Vie urbaine dynamique</h5><p>Transports efficaces, musées gratuits, et vie nocturne animée.</p></div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded shadow-sm">💼 <h5>Opportunités professionnelles</h5><p>Un écosystème riche pour les stages et l’emploi.</p></div>
            </div>
        </div>
        <div class="section-title text-center">
            <h2 class="text-primary">Aperçu en images</h2>
        </div>
        <div class="row image-grid mb-5">
            <div class="col-md-4 mb-4"><img src="https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=800&q=80" alt="Big Ben"></div>
            <div class="col-md-4 mb-4"><img src="https://images.unsplash.com/photo-1565373677869-4de3f39c2e4d?auto=format&fit=crop&w=800&q=80" alt="Université de Londres"></div>
            <div class="col-md-4 mb-4"><img src="https://images.unsplash.com/photo-1535392432937-a27c7f3dd1dd?auto=format&fit=crop&w=800&q=80" alt="Étudiants à Londres"></div>
        </div>

        <div class="text-center mb-5">
            <a href="formulaire.php" class="btn btn-primary btn-lg">Postuler</a>
        </div>  

        <div class="text-center">
            <a href="destinations.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
        </div>
    </div>
    <footer class="text-center">&copy; <?= date('Y') ?> ECE | Campus de Londres - Tous droits réservés</footer>
</body>
</html>
