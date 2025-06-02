<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>ECE - Campus de Monaco</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #fdfdfd;
      font-family: 'Segoe UI', sans-serif;
    }
    .hero {
      background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://www.ece.fr/wp-content/uploads/sites/2/2023/06/paris-ville-campus-ece-paris-1480x800-1.jpg?w=1480&h=800&crop=1') no-repeat center center;
      background-size: cover;
      color: white;
      padding: 120px 20px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
      border-radius: 0 0 30px 30px;
    }
    .info-box {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .info-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    }
    .image-grid img {
      border-radius: 20px;
      width: 100%;
      height: 220px;
      object-fit: cover;
    }
    footer {
      background-color: #f1f1f1;
      padding: 20px 0;
      color: #555;
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <header class="hero text-center animate__animated animate__fadeIn">
    <h1 class="display-4">Campus ECE à Monaco</h1>
    <p class="lead">Un lieu d'innovation, de luxe et d'excellence méditerranéenne</p>
  </header>

  <main class="container mt-5">
    <section class="text-center mb-5">
      <h2 class="text-primary mb-3">Pourquoi choisir Monaco ?</h2>
      <p class="text-muted">Située sur la Côte d'Azur, Monaco combine une qualité de vie exceptionnelle, un climat ensoleillé, et un environnement sûr propice aux études et aux affaires.</p>
    </section>

    <div class="row text-center mb-5">
      <div class="col-md-3">
        <div class="p-4 bg-light rounded info-box">
          🌊 <h5>Vue méditerranéenne</h5>
          <p>Une ville ensoleillée toute l'année au bord de la mer.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-light rounded info-box">
          🏢 <h5>Centre des affaires</h5>
          <p>Un carrefour pour les industries de luxe, finance et tech.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-light rounded info-box">
          💼 <h5>Stages & opportunités</h5>
          <p>Accès direct à des entreprises internationales de renom.</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="p-4 bg-light rounded info-box">
          🎓 <h5>Cadre sécurisé</h5>
          <p>Un des pays les plus sûrs au monde pour les étudiants.</p>
        </div>
      </div>
    </div>

    <section class="text-center mb-4">
      <h2 class="text-primary">Monaco en images</h2>
    </section>

    <div class="row image-grid mb-5">
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1580316463040-df0d68aeb4bb?auto=format&fit=crop&w=800&q=80" alt="Port de Monaco">
      </div>
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1525976811842-d785b0f9cd2c?auto=format&fit=crop&w=800&q=80" alt="Centre-ville de Monaco">
      </div>
      <div class="col-md-4 mb-4">
        <img src="https://images.unsplash.com/photo-1580734075387-9c8c9b04a29b?auto=format&fit=crop&w=800&q=80" alt="Ambiance étudiante à Monaco">
      </div>
    </div>

    <div class="text-center">
      <a href="accueil.php" class="btn btn-outline-primary btn-lg">⬅ Retour à l'accueil</a>
    </div>
  </main>

  <footer class="text-center">
    &copy; <?= date('Y') ?> ECE | Campus de Monaco - Tous droits réservés
  </footer>

</body>
</html>
