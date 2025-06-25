<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT name, average FROM students WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}

$average = $user['average'];

// Destinations avec seuils spécifiques
$destinations = [
    ['ville' => 'Tokyo',      'image' => 'tokyo.png',      'min_avg' => 14.01],
    ['ville' => 'Londres',    'image' => 'londres.png',    'min_avg' => 14.01],
    ['ville' => 'Boston',     'image' => 'boston.png',     'min_avg' => 14.01],
    ['ville' => 'Zurich',     'image' => 'zurich.png',     'min_avg' => 14.01],
    ['ville' => 'Hongkong',   'image' => 'hongkong.png',   'min_avg' => 12],
    ['ville' => 'Barcelone',  'image' => 'barcelonne.png', 'min_avg' => 12],
    ['ville' => 'Lausanne',   'image' => 'lausanne.png',   'min_avg' => 12],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Destinations - School Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --dark-color: #1e293b;
            --success-color: #22c55e;
            --warning-color: #f97316;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(30, 41, 59, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #10b981 !important;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #10b981 !important;
            transform: translateY(-2px);
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .welcome-header {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            margin: -1rem -1rem 2rem -1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="plane" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse"><text x="25" y="25" text-anchor="middle" fill="rgba(255,255,255,0.1)" font-size="20">✈</text></pattern></defs><rect width="100" height="100" fill="url(%23plane)"/></svg>');
            opacity: 0.3;
        }

        .welcome-header h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .user-stats {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        .average-display {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
        }

        .dest-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            cursor: pointer;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            height: 100%;
        }

        .dest-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            z-index: 2;
        }

        .dest-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.4s ease;
        }

        .dest-card:hover .card-img-top {
            transform: scale(1.1);
        }

        .grayed {
            filter: grayscale(100%) brightness(0.7);
            cursor: not-allowed;
            position: relative;
        }

        .grayed::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 20px;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .grayed::after {
            content: '🔒';
            font-size: 3rem;
            color: white;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8);
        }

        .grayed:hover {
            transform: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dest-name {
            text-align: center;
            margin-top: 8px;
            font-weight: bold;
            padding: 20px;
            font-size: 1.3rem;
            color: var(--dark-color);
            position: relative;
        }

        .dest-name::before {
            content: '✈️';
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 1.2rem;
            animation: float 2s ease-in-out infinite;
        }

        .eligibility-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 3;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
        }

        .badge-accessible {
            background: var(--success-color);
        }

        .badge-locked {
            background: var(--warning-color);
        }

        .alert-warning {
            background: linear-gradient(45deg, #f97316, #ea580c);
            color: white;
            border: none;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.3);
            position: relative;
            overflow: hidden;
        }

        .alert-warning::before {
            content: '⚠️';
            font-size: 2rem;
            margin-right: 10px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
        }

        .text-decoration-none:hover {
            text-decoration: none !important;
        }

        @media (max-width: 768px) {
            .welcome-header h2 {
                font-size: 1.5rem;
            }
            
            .container {
                margin: 1rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="accueil.php">
            <i class="fas fa-plane"></i> School Travel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="accueil.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li class="nav-item"><a class="nav-link active" href="destinations.php"><i class="fas fa-map-marked-alt"></i> Destinations</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="welcome-header">
        <h2>Bonjour <?=htmlspecialchars($user['name'])?>, voici les destinations disponibles pour vous :</h2>
        <div class="user-stats">
            <div class="average-display">
                <i class="fas fa-star"></i> Votre moyenne : <?= number_format($average, 2) ?>/20
            </div>
        </div>
    </div>

    <?php if ($average < 12): ?>
        <div class="alert alert-warning">Vous n'êtes pas éligible à la bourse. Aucune destination n'est accessible.</div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php foreach ($destinations as $dest): 
            $isClickable = $average >= $dest['min_avg'];
            $class = $isClickable ? '' : 'grayed';
            $href = $isClickable ? "destination_detail.php?ville=" . urlencode($dest['ville']) : "#";
        ?>
        <div class="col animate-in">
            <a href="<?= $href ?>" class="text-decoration-none">
                <div class="card dest-card <?= $class ?>">
                    <div class="eligibility-badge <?= $isClickable ? 'badge-accessible' : 'badge-locked' ?>">
                        <?= $isClickable ? '✓ Accessible' : '🔒 Verrouillé' ?>
                    </div>
                    <img src="assets/images/<?= $dest['image'] ?>" class="card-img-top" alt="<?= $dest['ville'] ?>" />
                    <div class="dest-name"><?= $dest['ville'] ?></div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animation des cartes au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.animate-in');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });

    // Effet de parallaxe léger sur les cartes
    document.addEventListener('mousemove', function(e) {
        const cards = document.querySelectorAll('.dest-card:not(.grayed)');
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        
        cards.forEach(card => {
            const rect = card.getBoundingClientRect();
            const cardX = (rect.left + rect.width / 2) / window.innerWidth;
            const cardY = (rect.top + rect.height / 2) / window.innerHeight;
            
            const deltaX = (mouseX - cardX) * 10;
            const deltaY = (mouseY - cardY) * 10;
            
            card.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
        });
    });
</script>
</body>
</html>