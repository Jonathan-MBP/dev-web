<!-- Page d'accueil : barcelone.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ECE - Campus de Barcelone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .hero {
            background: url('https://images.unsplash.com/photo-1533106418989-88406c7c1688?auto=format&fit=crop&w=1600&q=80') no-repeat center center;
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

<div class="hero text-center mt-4">
    <h1 class="display-4">Campus ECE à Barcelone</h1>
    <p class="lead">Une expérience vibrante entre culture, soleil et innovation</p>
</div>

<div class="container">
    <div class="section-title text-center">
        <h2 class="text-primary">Pourquoi Barcelone ?</h2>
        <p>Barcelone allie dynamisme académique, cadre de vie ensoleillé et une riche diversité culturelle pour offrir une expérience inoubliable.</p>
    </div>

    <div class="row text-center mb-5">
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                🌞 <h5>Climat Méditerranéen</h5>
                <p>Soleil presque toute l’année, idéal pour étudier dans la bonne humeur.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                🏖️ <h5>Plages en ville</h5>
                <p>Profitez de la mer après les cours dans un cadre exceptionnel.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                🏛️ <h5>Richesse culturelle</h5>
                <p>Architecture de Gaudí, musées, festivals et traditions vivantes.</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light rounded shadow-sm">
                🌍 <h5>Vie étudiante internationale</h5>
                <p>Une ville cosmopolite avec des étudiants venus du monde entier.</p>
            </div>
        </div>
    </div>

    <div class="section-title text-center">
        <h2 class="text-primary">Aperçu en images</h2>
    </div>

    <div class="row image-grid mb-5">
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=800&q=80" alt="Vue de Barcelone">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?auto=format&fit=crop&w=800&q=80" alt="Plage de Barcelone">
        </div>
        <div class="col-md-4 mb-4">
            <img src="https://images.unsplash.com/photo-1558021212-51b6ecfa0db9?auto=format&fit=crop&w=800&q=80" alt="Étudiants à Barcelone">
        </div>
    </div>

    <div class="text-center mb-5">
        <a href="formulaire.php" class="btn btn-primary btn-lg">Postuler</a>
    </div>
    
    <div class="text-center">
        <a href="accueil.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
    </div>
</div>

<footer class="text-center mt-5">
    &copy; <?php echo date('Y'); ?> ECE | Campus de Barcelone - Tous droits réservés
</footer>

</body>
</html>
