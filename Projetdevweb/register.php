<?php
require_once 'db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $average = floatval($_POST['average'] ?? 0);

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword) || $average <= 0) {
        $message = "Tous les champs sont obligatoires.";
        $messageType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'email invalide.";
        $messageType = 'danger';
    } elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
        $messageType = 'danger';
    } elseif ($password !== $confirmPassword) {
        $message = "La confirmation du mot de passe ne correspond pas.";
        $messageType = 'danger';
    } elseif ($average < 0 || $average > 20) {
        $message = "La moyenne doit être comprise entre 0 et 20.";
        $messageType = 'danger';
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Un compte avec cet email existe déjà.";
            $messageType = 'danger';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (name, email, password, average) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword, $average]);
            $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            $messageType = 'success';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inscription - School Travel</title>
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
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .registration-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .registration-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .registration-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        .brand-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary-color), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            animation: pulse 2s infinite;
        }

        .brand-title {
            color: var(--dark-color);
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 8px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 500;
        }

        .page-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 30px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e2e8f0;
            padding: 20px 20px 20px 50px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.8);
            height: auto;
        }

        .form-control:focus {
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

        .btn-register {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 15px;
            padding: 15px 0;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(37, 99, 235, 0.4);
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-back {
            background: linear-gradient(45deg, #6b7280, #9ca3af);
            border: none;
            border-radius: 15px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
            margin-top: 15px;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
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

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
            color: #9ca3af;
            font-size: 0.9rem;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e2e8f0;
            z-index: 1;
        }

        .divider span {
            background: rgba(255, 255, 255, 0.95);
            padding: 0 20px;
            position: relative;
            z-index: 2;
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

        .form-text {
            color: #64748b;
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        @media (max-width: 576px) {
            .registration-card {
                padding: 40px 30px;
                margin: 20px;
            }
            
            .brand-icon {
                width: 70px;
                height: 70px;
                font-size: 2rem;
            }
            
            .brand-title {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 18px 18px 18px 45px;
            }
            
            .form-floating > label {
                padding-left: 45px;
            }
            
            .input-icon {
                left: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="registration-container">
        <div class="registration-card">
            
            <!-- En-tête de marque -->
            <div class="brand-header">
                <div class="brand-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h1 class="brand-title">School Travel</h1>
                <p class="brand-subtitle">Découvrez le monde avec nous</p>
            </div>

            <h2 class="page-title">
                <i class="fas fa-user-plus"></i>
                Créer un compte
            </h2>

            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-triangle' ?>"></i>
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="registrationForm">
                
                <!-- Nom complet -->
                <div class="form-floating position-relative">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" name="name" id="name" 
                           placeholder="Votre nom complet" required 
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <label for="name">Nom complet</label>
                </div>

                <!-- Email -->
                <div class="form-floating position-relative">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" name="email" id="email" 
                           placeholder="votre.email@exemple.com" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <label for="email">Adresse email</label>
                </div>

                <!-- Mot de passe -->
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

                <!-- Confirmation mot de passe -->
                <div class="form-floating position-relative">
                    <i class="fas fa-check input-icon"></i>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                           placeholder="Confirmez votre mot de passe" required>
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <!-- Moyenne -->
                <div class="form-floating position-relative">
                    <i class="fas fa-chart-line input-icon"></i>
                    <input type="number" step="0.01" min="0" max="20" class="form-control" 
                           name="average" id="average" placeholder="Moyenne académique" required 
                           value="<?= htmlspecialchars($_POST['average'] ?? '') ?>">
                    <label for="average">Moyenne académique (/20)</label>
                    <div class="form-text">
                        <i class="fas fa-graduation-cap"></i>
                        Entre 0 et 20
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>
                    Créer mon compte
                </button>

                <div class="divider">
                    <span>Déjà inscrit ?</span>
                </div>

                <div class="text-center">
                    <a href="index.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la connexion
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fonction pour afficher/masquer les mots de passe
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.nextElementSibling.querySelector('i');
        
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
    document.getElementById('registrationForm').addEventListener('submit', function() {
        const submitBtn = document.querySelector('.btn-register');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Création en cours...';
        submitBtn.disabled = true;
    });

    // Animation d'entrée
    document.addEventListener('DOMContentLoaded', function() {
        const card = document.querySelector('.registration-card');
        card.style.animationDelay = '0.2s';
    });
</script>

</body>
</html>