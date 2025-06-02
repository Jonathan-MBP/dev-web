<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - TBD Travel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .hero { background: #f3f8fe; padding: 2rem 1rem; border-radius: 1rem; margin-bottom: 2rem; text-align: center; }
        .campus-img { width: 100%; height: 180px; object-fit: cover; border-radius: 8px; }
        .campus-card { margin-bottom: 2rem; min-height: 420px; }
        .testimonial { background: #fdf6e3; border-left: 5px solid #ffc107; padding: 1.2rem; margin-bottom: 1.5rem; border-radius: 8px; }
        .faq-section { background: #f2f2f2; border-radius: 1rem; padding: 1.5rem; margin-bottom: 2rem; }
        .contact-section { background: #e7f7ed; border-radius: 1rem; padding: 1.5rem; }
        .footer { margin-top: 3rem; color: #888; }
    </style>
</head>
<body>
    <!-- Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="image/ECE_LOGO_2021_web.png" alt="Logo du site" style="height: 40px;">
            <a class="navbar-brand" href="accueil.php">TBD Travel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="accueil.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Mon profil</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
                     
                <div class="profile-dropdown">
                    <img src="user-icon.jpg" alt="Profil" class="profile-icon" style="height: 40px;">
                    <ul class="dropdown-menu">
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                    <?php if ($role === 'admin'): ?>
                    <span class="admin-label">ADMIN</span>
                    <?php endif; ?>

                    <!-- Menu déroulant caché par défaut -->

                    <img 
                    src="<?= !empty($_SESSION['user']['profile_picture']) ? htmlspecialchars($_SESSION['user']['profile_picture']) : 'user-icon.jpg' ?>" 
                    class="profile-icon" 
                    alt="Profil" 
                    style="height: 40px;"/>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Mon profil</a></li>
                        <li><a href="index.php">Mes recommendations</a></li>
                        <?php if ($role === 'admin'): ?>
                        <li><a href="crud.php">CRUD</a></li>
                    <?php endif; ?>
                        <li><a href="logout.php">Déconnexion</a></li>
                    </ul>
                </div>

                <div class="profile-dropdown">
                    <!-- Icône de profil (ex. image de l’utilisateur) -->

                    
                </div>
        </div>
    </nav>
    <!-- Fin menu -->

    <div class="container mt-4">
        <!-- Présentation -->
        <div class="hero shadow">
            <h1>BIEVENUE sur la page</h1>
            <p>
                Notre programme vise à encourager nos étudiants excellents tout au long de leur parcours académiques en leur proposant des voyages vers d'autres pays/villes afin de leur donner la chance d'étudier dans nos différents campus tout en vivant des expériences uniques et inouïes !
            </p>
        </div>

        <!-- Aperçu des campus -->
        <h2 class="mb-4 text-primary">Aperçu de nos campus</h2>
        <div class="row">
            <!-- Abidjan -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Abidjan">
                    <div class="card-body">
                        <h5 class="card-title">Abidjan</h5>
                        <p class="card-text">Campus moderne en Côte d'Ivoire, au cœur de l'Afrique de l'Ouest. Vie étudiante chaleureuse, coût de vie abordable et accueil exceptionnel.</p>
                    </div>
                </div>
            </div>
            <!-- Barcelone -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Barcelone">
                    <div class="card-body">
                        <h5 class="card-title">Barcelone</h5>
                        <p class="card-text">Ville festive et cosmopolite. Soleil, plage, architecture unique et vie nocturne inoubliable. Opportunités de stages internationales.</p>
                    </div>
                </div>
            </div>
            <!-- Genève -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1506084868230-bb9d95c24759?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Genève">
                    <div class="card-body">
                        <h5 class="card-title">Genève</h5>
                        <p class="card-text">Ville internationale par excellence, au bord du lac Léman. Cadre de vie exceptionnel et excellence académique reconnue mondialement.</p>
                    </div>
                </div>
            </div>
            <!-- Lausanne -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Lausanne">
                    <div class="card-body">
                        <h5 class="card-title">Lausanne</h5>
                        <p class="card-text">Ville universitaire suisse très dynamique, réputée pour sa qualité de vie, ses événements étudiants et ses paysages naturels.</p>
                    </div>
                </div>
            </div>
            <!-- Londres -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1465101162946-4377e57745c3?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Londres">
                    <div class="card-body">
                        <h5 class="card-title">Londres</h5>
                        <p class="card-text">Capitale vibrante, multiculturelle et pleine d’opportunités. Vie étudiante intense et accès à des universités de renommée mondiale.</p>
                    </div>
                </div>
            </div>
            <!-- Monaco -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1509228468518-180dd4864904?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Monaco">
                    <div class="card-body">
                        <h5 class="card-title">Monaco</h5>
                        <p class="card-text">Luxueux, ensoleillé, cadre privilégié entre mer et montagnes. Vie étudiante exclusive et nombreux évènements internationaux.</p>
                    </div>
                </div>
            </div>
            <!-- Munich -->
            <div class="col-md-4 campus-card">
                <div class="card h-100 shadow">
                    <img src="https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=600&q=80" class="campus-img" alt="Munich">
                    <div class="card-body">
                        <h5 class="card-title">Munich</h5>
                        <p class="card-text">Ville allemande dynamique, réputée pour sa qualité de vie, ses universités prestigieuses et ses nombreux festivals.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Témoignages -->
        <h2 class="mt-5 mb-3 text-primary">Témoignages</h2>
        <div>
            <div class="testimonial">
                <p>
                    <em>« Grâce à la bourse TBD Travel, j’ai pu étudier un semestre à Barcelone et vivre une expérience incroyable. J’ai découvert une nouvelle culture et me suis fait des amis du monde entier ! »</em>
                    <br><strong>— Awa, lauréate 2023</strong>
                </p>
            </div>
            <div class="testimonial">
                <p>
                    <em>« Le campus de Genève est exceptionnel ! Les professeurs sont très compétents et l’environnement international m’a beaucoup apporté pour ma carrière. »</em>
                    <br><strong>— Moussa, lauréat 2022</strong>
                </p>
            </div>
        </div>

        <!-- Questions fréquentes -->
        <div class="faq-section mt-5">
            <h3>Questions fréquentes</h3>
            <ul>
                <li><strong>Qui peut postuler ?</strong> Tous les étudiants excellents inscrits dans notre école.</li>
                <li><strong>Comment obtenir une bourse ?</strong> Il faut avoir d’excellents résultats académiques et déposer un dossier de motivation.</li>
                <li><strong>Quels sont les avantages ?</strong> Étudier à l’étranger, immersion culturelle, réseau international.</li>
            </ul>
        </div>

        <!-- Contact -->
        <div class="contact-section mt-4">
            <h3>Contact & Adresse de l’école</h3>
            <p>
                TBD Travel<br>
                123, Avenue de l’Excellence<br>
                Abidjan, Côte d’Ivoire<br>
                Téléphone : +225 01 23 45 67 89<br>
                Email : contact@tbdtravel.com
            </p>
        </div>

        <footer class="footer text-center mt-5">
            Propriété © <?=date('Y')?> TBD Travel - Tous droits réservés.
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>