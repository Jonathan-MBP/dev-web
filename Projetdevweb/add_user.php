<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

// Vérifier que l'utilisateur est connecté et admin
if (!isAdmin()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Traitement du formulaire
if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'student';
    $average = floatval($_POST['average'] ?? 0);
    
    // Validation
    if (empty($name)) {
        $error = "Le nom est requis.";
    } elseif (empty($email)) {
        $error = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'email n'est pas valide.";
    } elseif (empty($password)) {
        $error = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirmPassword) {
        $error = "La confirmation du mot de passe ne correspond pas.";
    } elseif ($average < 0 || $average > 20) {
        $error = "La moyenne doit être comprise entre 0 et 20.";
    } else {
        // Vérifier si l'email existe déjà
        $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $checkStmt->execute([$email]);
        if ($checkStmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Insérer le nouvel utilisateur
            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $insertStmt = $pdo->prepare("INSERT INTO students (name, email, password, role, average) VALUES (?, ?, ?, ?, ?)");
                $insertStmt->execute([$name, $email, $hashedPassword, $role, $average]);
                
                $success = "Utilisateur ajouté avec succès.";
                // Réinitialiser les champs
                $name = $email = $password = $confirmPassword = '';
                $role = 'student';
                $average = 0;
            } catch (PDOException $e) {
                $error = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ajouter un utilisateur - School Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --dark-color: #1e293b;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #ef4444;
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
            transition: all 0.3s ease;
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
            position: relative;
        }

        .nav-link:hover {
            color: #10b981 !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #10b981;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .main-content {
            padding: 120px 0 80px;
            min-height: calc(100vh - 140px);
        }

        .page-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.8s ease-out;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--warning-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
            animation: pulse 2s infinite;
        }

        .page-title {
            color: var(--dark-color);
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .section-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-floating {
            margin-bottom: 20px;
            position: relative;
        }

        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e2e8f0;
            padding: 20px 20px 20px 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.8);
            height: auto;
        }

        .form-select {
            padding-left: 50px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
            background: white;
        }

        .form-floating > label {
            padding-left: 50px;
            color: #64748b;
            font-weight: 500;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .form-floating:focus-within .input-icon {
            color: var(--secondary-color);
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.1rem;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--secondary-color);
        }

        .strength-meter {
            height: 4px;
            border-radius: 2px;
            background: #e2e8f0;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .form-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .role-badges {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .role-badge {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .role-student {
            background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .role-admin {
            background: linear-gradient(45deg, var(--warning-color), #fbbf24);
            color: white;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        }

        .btn-modern {
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            margin: 5px;
        }

        .btn-primary-modern {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: white;
        }

        .btn-secondary-modern {
            background: linear-gradient(45px, #6b7280, #9ca3af);
            color: white;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-warning-modern {
            background: linear-gradient(45deg, var(--warning-color), var(--accent-color));
            color: white;
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.3);
        }

        .btn-warning-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(217, 119, 6, 0.4);
            color: white;
            text-decoration: none;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(45deg, var(--success-color), var(--secondary-color));
            color: white;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .alert-danger {
            background: linear-gradient(45deg, #ef4444, #f87171);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .breadcrumb-modern {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 10px 20px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-modern a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .breadcrumb-modern a:hover {
            color: var(--secondary-color);
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

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }
            
            .page-header, .form-card {
                padding: 30px 20px;
                margin: 0 15px 20px;
            }
            
            .page-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }
            
            .form-control, .form-select {
                padding: 18px 18px 18px 45px;
            }
            
            .form-floating > label {
                padding-left: 45px;
            }
            
            .input-icon {
                left: 15px;
            }

            .btn-modern {
                width: 100%;
                justify-content: center;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="accueil.php">
            <i class="fas fa-plane"></i> School Travel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="accueil.php"><i class="fas fa-home"></i> Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="destinations.php"><i class="fas fa-map-marked-alt"></i> Destinations</a></li>
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Administration</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <div class="page-container">
            
            <!-- Fil d'Ariane -->
            <nav class="breadcrumb-modern">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Administration</a>
                <span class="mx-2">/</span>
                <span>Ajouter un utilisateur</span>
            </nav>

            <!-- En-tête de la page -->
            <div class="page-header">
                <div class="page-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="page-title">Ajouter un nouvel utilisateur</h1>
                <p class="page-subtitle">Créez un nouveau compte étudiant ou administrateur</p>
            </div>

            <!-- Alertes -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="form-card">
                <h2 class="section-title">
                    <i class="fas fa-user-cog"></i> Informations du nouvel utilisateur
                </h2>

                <form method="POST" id="addUserForm">
                    <div class="row">
                        <!-- Nom complet -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" class="form-control" name="name" id="name" 
                                       placeholder="Nom complet" required 
                                       value="<?= htmlspecialchars($name ?? '') ?>">
                                <label for="name">Nom complet</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" class="form-control" name="email" id="email" 
                                       placeholder="Adresse email" required 
                                       value="<?= htmlspecialchars($email ?? '') ?>">
                                <label for="email">Adresse email</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Mot de passe -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" class="form-control" name="password" id="password" 
                                       placeholder="Mot de passe" required minlength="6">
                                <label for="password">Mot de passe</label>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="strength-meter">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    Minimum 6 caractères
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-check input-icon"></i>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                                       placeholder="Confirmer le mot de passe" required>
                                <label for="confirm_password">Confirmer le mot de passe</label>
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Rôle -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-user-tag input-icon"></i>
                                <select class="form-select" name="role" id="role">
                                    <option value="student" <?= ($role ?? 'student') === 'student' ? 'selected' : '' ?>>Étudiant</option>
                                    <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                                </select>
                                <label for="role">Rôle</label>
                            </div>
                            <div class="role-badges">
                                <div class="role-badge role-student">
                                    <i class="fas fa-graduation-cap"></i>
                                    Étudiant
                                </div>
                                <div class="role-badge role-admin">
                                    <i class="fas fa-crown"></i>
                                    Administrateur
                                </div>
                            </div>
                        </div>

                        <!-- Moyenne -->
                        <div class="col-md-6">
                            <div class="form-floating position-relative">
                                <i class="fas fa-chart-line input-icon"></i>
                                <input type="number" class="form-control" name="average" id="average" 
                                       step="0.01" min="0" max="20" placeholder="Moyenne" 
                                       value="<?= htmlspecialchars($average ?? 0) ?>">
                                <label for="average">Moyenne académique (/20)</label>
                                <div class="form-text">
                                    <i class="fas fa-calculator"></i>
                                    Entre 0 et 20
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="text-center mt-4">
                        <button type="submit" class="btn-modern btn-primary-modern">
                            <i class="fas fa-user-plus"></i>
                            Ajouter l'utilisateur
                        </button>
                        <a href="dashboard.php" class="btn-modern btn-secondary-modern">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                        <a href="dashboard.php" class="btn-modern btn-warning-modern">
                            <i class="fas fa-arrow-left"></i>
                            Retour au tableau de bord
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Animation de la navbar au scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(30, 41, 59, 0.98)';
        } else {
            navbar.style.background = 'rgba(30, 41, 59, 0.95)';
        }
    });

    // Fonction pour afficher/masquer les mots de passe
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Validation en temps réel de la confirmation du mot de passe
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password && confirmPassword) {
            if (password === confirmPassword) {
                this.style.borderColor = '#10b981';
                this.style.boxShadow = '0 0 0 0.2rem rgba(16, 185, 129, 0.25)';
            } else {
                this.style.borderColor = '#ef4444';
                this.style.boxShadow = '0 0 0 0.2rem rgba(239, 68, 68, 0.25)';
            }
        } else {
            this.style.borderColor = '#e2e8f0';
            this.style.boxShadow = 'none';
        }
    });

    // Indicateur de force du mot de passe
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('strengthBar');
        let strength = 0;
        
        if (password.length >= 6) strength += 25;
        if (password.match(/[a-z]/)) strength += 25;
        if (password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/) || password.match(/[^a-zA-Z0-9]/)) strength += 25;
        
        strengthBar.style.width = strength + '%';
        
        if (strength < 50) {
            strengthBar.style.background = '#ef4444';
        } else if (strength < 75) {
            strengthBar.style.background = '#f59e0b';
        } else {
            strengthBar.style.background = '#10b981';
        }
    });

    // Validation de la moyenne en temps réel
    document.getElementById('average').addEventListener('input', function() {
        const value = parseFloat(this.value);
        
        if (value < 0 || value > 20) {
            this.style.borderColor = '#ef4444';
            this.style.boxShadow = '0 0 0 0.2rem rgba(239, 68, 68, 0.25)';
        } else {
            this.style.borderColor = '#10b981';
            this.style.boxShadow = '0 0 0 0.2rem rgba(16, 185, 129, 0.25)';
        }
    });

    // Animation de soumission du formulaire
    document.getElementById('addUserForm').addEventListener('submit', function() {
        const submitBtn = document.querySelector('.btn-primary-modern');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout en cours...';
        submitBtn.disabled = true;
    });

    // Animation d'entrée
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.page-header, .form-card');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.2}s`;
        });
    });

    // Mise à jour visuelle du sélecteur de rôle
    document.getElementById('role').addEventListener('change', function() {
        const badges = document.querySelectorAll('.role-badge');
        badges.forEach(badge => {
            badge.style.opacity = '0.3';
            badge.style.transform = 'scale(0.95)';
        });
        
        const selectedRole = this.value;
        const selectedBadge = document.querySelector(`.role-${selectedRole}`);
        if (selectedBadge) {
            selectedBadge.style.opacity = '1';
            selectedBadge.style.transform = 'scale(1.05)';
        }
    });
</script>

</body>
</html>