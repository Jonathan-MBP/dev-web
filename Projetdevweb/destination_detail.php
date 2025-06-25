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

// Récupérer la ville depuis l'URL
$ville = $_GET['ville'] ?? '';
if (empty($ville)) {
    header('Location: destinations.php');
    exit();
}

// Informations détaillées des destinations d'études
$destinationsInfo = [
    'Tokyo' => [
        'nom' => 'Tokyo',
        'pays' => 'Japon',
        'image' => 'tokyo.png',
        'min_avg' => 14.01,
        'duree' => '1 semestre (5 mois)',
        'cout' => '8 500€',
        'description' => 'Étudiez dans l\'une des capitales technologiques mondiales. Immersion totale dans la culture japonaise avec des universités de renommée internationale spécialisées en ingénierie, technologie et business.',
        'programmes' => [
            'Ingénierie et Technologies',
            'Sciences Informatiques',
            'Business International',
            'Études Culturelles Japonaises',
            'Robotique et IA',
            'Design et Innovation'
        ],
        'universites' => [
            'Université de Tokyo (Todai)',
            'Institut de Technologie de Tokyo',
            'Université Waseda',
            'Université Keio'
        ],
        'logement' => 'Résidence étudiante universitaire',
        'transport' => 'Pass transport étudiant illimité',
        'climat' => 'Tempéré, idéal pour étudier',
        'langue' => 'Japonais + Cours d\'anglais disponibles',
        'monnaie' => 'Yen japonais (¥)',
        'decalage' => '+8h par rapport à la France',
        'avantages' => [
            'Bourses d\'excellence disponibles',
            'Stage en entreprise inclus',
            'Cours de japonais intensifs',
            'Mentorat par étudiants locaux'
        ]
    ],
    'Londres' => [
        'nom' => 'Londres',
        'pays' => 'Royaume-Uni',
        'image' => 'londres.png',
        'min_avg' => 14.01,
        'duree' => '1 année académique',
        'cout' => '12 000€',
        'description' => 'Intégrez l\'un des systèmes éducatifs les plus prestigieux au monde. Londres offre une expérience académique exceptionnelle avec des universités classées parmi les meilleures mondiales.',
        'programmes' => [
            'Business et Finance',
            'Sciences Politiques',
            'Littérature et Arts',
            'Sciences et Médecine',
            'Droit International',
            'Communication et Médias'
        ],
        'universites' => [
            'University College London (UCL)',
            'Imperial College London',
            'King\'s College London',
            'London School of Economics'
        ],
        'logement' => 'Résidence universitaire en centre-ville',
        'transport' => 'Carte étudiant transport londonien',
        'climat' => 'Océanique, idéal pour étudier',
        'langue' => 'Anglais',
        'monnaie' => 'Livre sterling (£)',
        'decalage' => '-1h par rapport à la France',
        'avantages' => [
            'Système universitaire reconnu mondialement',
            'Opportunités de stage en City',
            'Vie étudiante très active',
            'Accès aux bibliothèques historiques'
        ]
    ],
    'Boston' => [
        'nom' => 'Boston',
        'pays' => 'États-Unis',
        'image' => 'boston.png',
        'min_avg' => 14.01,
        'duree' => '1 semestre (4 mois)',
        'cout' => '15 000€',
        'description' => 'Étudiez dans le berceau de l\'enseignement supérieur américain. Boston concentre les universités les plus prestigieuses des États-Unis avec Harvard et MIT.',
        'programmes' => [
            'Sciences et Recherche',
            'Ingénierie et Technologie',
            'Business et Entrepreneuriat',
            'Médecine et Santé',
            'Sciences Humaines',
            'Innovation et Startup'
        ],
        'universites' => [
            'Harvard University',
            'Massachusetts Institute of Technology (MIT)',
            'Boston University',
            'Northeastern University'
        ],
        'logement' => 'Campus universitaire historique',
        'transport' => 'Pass transport étudiant métropolitain',
        'climat' => 'Continental, quatre saisons distinctes',
        'langue' => 'Anglais américain',
        'monnaie' => 'Dollar américain ($)',
        'decalage' => '-6h par rapport à la France',
        'avantages' => [
            'Accès aux laboratoires de recherche',
            'Réseau professionnel exceptionnel',
            'Écosystème startup dynamique',
            'Programmes d\'échange prestigieux'
        ]
    ],
    'Zurich' => [
        'nom' => 'Zurich',
        'pays' => 'Suisse',
        'image' => 'zurich.png',
        'min_avg' => 14.01,
        'duree' => '1 semestre (5 mois)',
        'cout' => '10 000€',
        'description' => 'Découvrez l\'excellence suisse en matière d\'éducation. Zurich offre un environnement d\'études exceptionnel avec des universités techniques de renommée mondiale.',
        'programmes' => [
            'Ingénierie et Sciences',
            'Finance et Banking',
            'Sciences Environnementales',
            'Technologies Durables',
            'Management International',
            'Recherche et Innovation'
        ],
        'universites' => [
            'ETH Zurich',
            'Université de Zurich',
            'Zurich University of Applied Sciences',
            'Business School Lausanne'
        ],
        'logement' => 'Résidence étudiante moderne',
        'transport' => 'Abonnement transport public étudiant',
        'climat' => 'Continental, environnement d\'étude optimal',
        'langue' => 'Allemand/Anglais/Français',
        'monnaie' => 'Franc suisse (CHF)',
        'decalage' => 'Même fuseau que la France',
        'avantages' => [
            'Qualité d\'enseignement exceptionnelle',
            'Proximité avec l\'industrie',
            'Environnement multiculturel',
            'Opportunités de recherche'
        ]
    ],
    'Hongkong' => [
        'nom' => 'Hong Kong',
        'pays' => 'Chine (RAS)',
        'image' => 'hongkong.png',
        'min_avg' => 12,
        'duree' => '1 semestre (4 mois)',
        'cout' => '9 500€',
        'description' => 'Porte d\'entrée vers l\'Asie, Hong Kong offre une expérience d\'études unique entre Orient et Occident. Hub financier et technologique majeur.',
        'programmes' => [
            'Business et Finance Asiatique',
            'Commerce International',
            'Technologies et Innovation',
            'Études Asiatiques',
            'Logistique et Transport',
            'Marketing Digital'
        ],
        'universites' => [
            'University of Hong Kong (HKU)',
            'Hong Kong University of Science and Technology',
            'Chinese University of Hong Kong',
            'City University of Hong Kong'
        ],
        'logement' => 'Résidence universitaire moderne',
        'transport' => 'Carte étudiante transport Hong Kong',
        'climat' => 'Subtropical, adapté aux études',
        'langue' => 'Cantonais/Anglais/Mandarin',
        'monnaie' => 'Dollar de Hong Kong (HK$)',
        'decalage' => '+7h par rapport à la France',
        'avantages' => [
            'Hub économique asiatique',
            'Programmes en anglais',
            'Stages en multinationales',
            'Réseau professionnel international'
        ]
    ],
    'Barcelone' => [
        'nom' => 'Barcelone',
        'pays' => 'Espagne',
        'image' => 'barcelonne.png',
        'min_avg' => 12,
        'duree' => '1 semestre (5 mois)',
        'cout' => '6 500€',
        'description' => 'Vibrez au rythme de la capitale catalane ! Barcelone combine excellence académique, innovation technologique et qualité de vie étudiante exceptionnelle.',
        'programmes' => [
            'Architecture et Design',
            'Business et Management',
            'Ingénierie et Technologie',
            'Arts et Communication',
            'Tourisme et Hospitalité',
            'Développement Durable'
        ],
        'universites' => [
            'Universitat de Barcelona',
            'Universitat Pompeu Fabra',
            'Universitat Politècnica de Catalunya',
            'ESADE Business School'
        ],
        'logement' => 'Résidence étudiante près du campus',
        'transport' => 'Abonnement transport étudiant',
        'climat' => 'Méditerranéen, parfait pour étudier',
        'langue' => 'Espagnol/Catalan/Anglais',
        'monnaie' => 'Euro (€)',
        'decalage' => 'Même fuseau que la France',
        'avantages' => [
            'Coût de la vie abordable',
            'Écosystème startup dynamique',
            'Proximité avec la France',
            'Vie étudiante très riche'
        ]
    ],
    'Lausanne' => [
        'nom' => 'Lausanne',
        'pays' => 'Suisse',
        'image' => 'lausanne.png',
        'min_avg' => 12,
        'duree' => '1 semestre (5 mois)',
        'cout' => '8 000€',
        'description' => 'Étudiez dans l\'une des plus belles villes universitaires d\'Europe. Lausanne combine excellence académique suisse et cadre de vie exceptionnel.',
        'programmes' => [
            'Sciences et Ingénierie',
            'Management et Économie',
            'Sciences Politiques',
            'Médecine et Santé',
            'Sciences du Sport',
            'Criminologie et Droit'
        ],
        'universites' => [
            'École Polytechnique Fédérale de Lausanne (EPFL)',
            'Université de Lausanne (UNIL)',
            'École hôtelière de Lausanne',
            'Business School Lausanne'
        ],
        'logement' => 'Résidence universitaire au bord du lac',
        'transport' => 'Abonnement transport étudiant régional',
        'climat' => 'Tempéré, idéal pour les études',
        'langue' => 'Français',
        'monnaie' => 'Franc suisse (CHF)',
        'decalage' => 'Même fuseau que la France',
        'avantages' => [
            'Enseignement en français',
            'Cadre de vie exceptionnel',
            'Recherche de pointe',
            'Proximité avec la France'
        ]
    ]
];

// Vérifier si la ville existe
if (!isset($destinationsInfo[$ville])) {
    header('Location: destinations.php');
    exit();
}

$destination = $destinationsInfo[$ville];
$average = $user['average'];
$isEligible = $average >= $destination['min_avg'];

// Si l'utilisateur n'est pas éligible, rediriger
if (!$isEligible) {
    header('Location: destinations.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($destination['nom']) ?> - StudyAbroad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #059669;
            --accent-color: #dc2626;
            --dark-color: #1e293b;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
        }

        body {
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(30, 41, 59, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #059669 !important;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #059669 !important;
            transform: translateY(-2px);
        }

        .hero-section {
            position: relative;
            height: 60vh;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(30, 64, 175, 0.8), rgba(5, 150, 105, 0.8));
            backdrop-filter: blur(2px);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 2rem;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .cost-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-size: 1.8rem;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            padding: 2rem;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .info-card h3 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card .icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .program-list, .university-list, .advantage-list {
            list-style: none;
            padding: 0;
        }

        .program-list li, .university-list li, .advantage-list li {
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: relative;
            padding-left: 2rem;
            transition: all 0.3s ease;
        }

        .program-list li:last-child, .university-list li:last-child, .advantage-list li:last-child {
            border-bottom: none;
        }

        .program-list li::before {
            content: '📚';
            position: absolute;
            left: 0;
            top: 0.8rem;
            font-size: 1rem;
        }

        .university-list li::before {
            content: '🏛️';
            position: absolute;
            left: 0;
            top: 0.8rem;
            font-size: 1rem;
        }

        .advantage-list li::before {
            content: '✅';
            position: absolute;
            left: 0;
            top: 0.8rem;
            font-size: 1rem;
        }

        .program-list li:hover, .university-list li:hover, .advantage-list li:hover {
            background: rgba(5, 150, 105, 0.1);
            border-radius: 10px;
            padding-left: 2.5rem;
            margin-left: -0.5rem;
            margin-right: -0.5rem;
        }

        .highlight-info {
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin: 2rem 0;
            box-shadow: 0 10px 30px rgba(5, 150, 105, 0.3);
        }

        .highlight-info h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .back-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(30, 64, 175, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
            color: white;
            text-decoration: none;
        }

        .quick-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .quick-info-item {
            background: rgba(30, 64, 175, 0.1);
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            border-left: 4px solid var(--primary-color);
        }

        .quick-info-item .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .quick-info-item h5 {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .quick-info-item p {
            margin: 0;
            color: #6b7280;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .cost-badge {
                font-size: 1.4rem;
                padding: 0.8rem 1.5rem;
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
                <li class="nav-item"><a class="nav-link" href="destinations.php"><i class="fas fa-university"></i> Destinations</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                <li class="nav-item"><a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero-section" style="background-image: url('assets/images/<?= $destination['image'] ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title"><?= htmlspecialchars($destination['nom']) ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($destination['pays']) ?> • <?= $destination['duree'] ?></p>
        <div class="cost-badge">
            <i class="fas fa-euro-sign"></i> Coût total : <?= $destination['cout'] ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="destinations.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Retour aux destinations
        </a>
        <div class="text-end">
            <small class="text-muted">Programme éligible avec votre moyenne de <?= number_format($average, 2) ?>/20</small>
        </div>
    </div>

    <div class="highlight-info">
        <h4><?= htmlspecialchars($destination['description']) ?></h4>
    </div>

    <div class="quick-info">
        <div class="quick-info-item">
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <h5>Durée</h5>
            <p><?= $destination['duree'] ?></p>
        </div>
        <div class="quick-info-item">
            <div class="icon"><i class="fas fa-euro-sign"></i></div>
            <h5>Coût total</h5>
            <p><?= $destination['cout'] ?></p>
        </div>
        <div class="quick-info-item">
            <div class="icon"><i class="fas fa-thermometer-half"></i></div>
            <h5>Climat</h5>
            <p><?= $destination['climat'] ?></p>
        </div>
        <div class="quick-info-item">
            <div class="icon"><i class="fas fa-language"></i></div>
            <h5>Langue</h5>
            <p><?= $destination['langue'] ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-graduation-cap"></i></div> Programmes d'études</h3>
                <ul class="program-list">
                    <?php foreach ($destination['programmes'] as $programme): ?>
                    <li><?= htmlspecialchars($programme) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-university"></i></div> Universités partenaires</h3>
                <ul class="university-list">
                    <?php foreach ($destination['universites'] as $universite): ?>
                    <li><?= htmlspecialchars($universite) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-home"></i></div> Logement étudiant</h3>
                <p><strong><?= htmlspecialchars($destination['logement']) ?></strong></p>
                
                <h3 class="mt-4"><div class="icon"><i class="fas fa-subway"></i></div> Transport</h3>
                <p><?= htmlspecialchars($destination['transport']) ?></p>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-star"></i></div> Avantages du programme</h3>
                <ul class="advantage-list">
                    <?php foreach ($destination['avantages'] as $avantage): ?>
                    <li><?= htmlspecialchars($avantage) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-coins"></i></div> Monnaie locale</h3>
                <p><?= htmlspecialchars($destination['monnaie']) ?></p>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-globe"></i></div> Décalage horaire</h3>
                <p><?= htmlspecialchars($destination['decalage']) ?></p>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="info-card">
                <h3><div class="icon"><i class="fas fa-chart-line"></i></div> Moyenne requise</h3>
                <p><?= number_format($destination['min_avg'], 2) ?>/20</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <div class="highlight-info">
            <h4><i class="fas fa-check-circle"></i> Félicitations ! Vous êtes éligible pour ce programme d'études</h4>
            <p class="mb-0">Contactez notre service des relations internationales pour démarrer votre candidature</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animation des cartes d'information au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.info-card, .quick-info-item').forEach(card => {
        observer.observe(card);
    });

    // Effet de parallaxe sur l'image hero
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        const rate = scrolled * -0.5;
        
        if (heroSection) {
            heroSection.style.backgroundPosition = `center ${rate}px`;
        }
    });

    // Animation des listes au survol
    document.querySelectorAll('.program-list li, .university-list li, .advantage-list li').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Effet de compteur pour les statistiques
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            element.textContent = Math.round(current);
            if (current >= target) {
                clearInterval(timer);
                element.textContent = target;
            }
        }, 20);
    }

    // Animation des badges d'information
    document.querySelectorAll('.quick-info-item').forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
        item.classList.add('animate-fade-in');
    });
</script>

<style>
    .animate-fade-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .program-list li, .university-list li, .advantage-list li {
        transition: all 0.3s ease;
    }
    
    .info-card:hover .icon {
        transform: rotate(360deg);
        transition: transform 0.5s ease;
    }
    
    .quick-info-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(30, 64, 175, 0.2);
    }
    
    .hero-content {
        animation: slideInFromTop 1.2s ease-out;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive design amélioré */
    @media (max-width: 992px) {
        .quick-info {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .quick-info {
            grid-template-columns: 1fr;
        }
        
        .hero-content {
            padding: 1rem;
        }
        
        .info-card {
            padding: 1.5rem;
        }
    }
</style>

</body>
</html>