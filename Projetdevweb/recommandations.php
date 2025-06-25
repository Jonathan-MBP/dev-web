<?php
// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Récupérer la moyenne de l'étudiant
    $stmt = $pdo->prepare("SELECT average FROM students WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    $average = $user ? floatval($user['average']) : 0;
    
    // Définir nombre de destinations selon la moyenne (cohérent avec accueil.php)
    if ($average >= 18) {
        $limit = 1;
        $category = 'excellence';
        $categoryLabel = 'Excellence';
    } elseif ($average >= 16) {
        $limit = 1;
        $category = 'tres_bien';
        $categoryLabel = 'Très bien';
    } elseif ($average >= 14) {
        $limit = 2;
        $category = 'bien';
        $categoryLabel = 'Bien';
    } elseif ($average >= 12) {
        $limit = 2;
        $category = 'assez_bien';
        $categoryLabel = 'Assez bien';
    } else {
        $limit = 1;
        $category = 'rattrapage';
        $categoryLabel = 'À améliorer';
    }
    
    // Récupérer les destinations
    $stmt = $pdo->prepare("SELECT * FROM destinations WHERE category = ? LIMIT ?");
    $stmt->bindParam(1, $category, PDO::PARAM_STR);
    $stmt->bindParam(2, $limit, PDO::PARAM_INT);
    $stmt->execute();
    $destinations = $stmt->fetchAll();
    
} catch (PDOException $e) {
    // Log l'erreur et afficher un message générique
    error_log("Erreur base de données: " . $e->getMessage());
    $destinations = [];
    $average = 0;
}

include 'header.php';
?>

<div class="recommendations-container">
    <h2>Recommandations pour vous</h2>
    
    <?php if ($average === 0): ?>
        <div class="alert alert-info">
            <p>Veuillez mettre à jour votre moyenne dans votre profil pour voir les recommandations.</p>
            <a href="profile.php" class="btn btn-primary">Mettre à jour mon profil</a>
        </div>
    
    <?php elseif (!empty($destinations)): ?>
        <div class="user-stats">
            <p>Votre moyenne : <strong><?= number_format($average, 1) ?>/20</strong></p>
            <p>Niveau : <strong><?= $categoryLabel ?></strong></p>
            <p>Destinations disponibles : <strong><?= count($destinations) ?></strong></p>
        </div>
        
        <ul class="destinations-list">
            <?php foreach ($destinations as $dest): ?>
                <li class="destination-item">
                    <div class="destination-header">
                        <?php if (isset($dest['flag'])): ?>
                            <span class="destination-flag"><?= $dest['flag'] ?></span>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($dest['name']) ?></h3>
                    </div>
                    <p class="destination-description"><?= htmlspecialchars($dest['description']) ?></p>
                    <div class="destination-details">
                        <?php if (isset($dest['price'])): ?>
                            <span class="price"><i class="fas fa-euro-sign"></i> <?= htmlspecialchars($dest['price']) ?>€</span>
                        <?php endif; ?>
                        <?php if (isset($dest['duration'])): ?>
                            <span class="duration"><i class="fas fa-clock"></i> <?= htmlspecialchars($dest['duration']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($dest['highlights'])): ?>
                        <div class="highlights">
                            <strong>Points forts :</strong> <?= htmlspecialchars($dest['highlights']) ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        
    <?php else: ?>
        <div class="alert alert-warning">
            <p>Aucune destination disponible pour votre catégorie actuellement.</p>
            <p>Contactez l'administration pour plus d'informations.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.recommendations-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    padding-top: 100px; /* Pour compenser la navbar fixe */
}

.recommendations-container h2 {
    color: #1e293b;
    font-weight: 700;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2.5rem;
}

.alert {
    padding: 20px;
    margin: 20px 0;
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e40af;
}

.alert-warning {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #92400e;
}

.user-stats {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    padding: 25px;
    border-radius: 15px;
    margin: 30px 0;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.user-stats p {
    margin: 10px 0;
    font-size: 1.1rem;
}

.destinations-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.destination-item {
    background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
    border: 2px solid #e5e7eb;
    border-radius: 20px;
    padding: 30px;
    margin: 25px 0;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.destination-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(45deg, #2563eb, #10b981);
}

.destination-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    border-color: #10b981;
}

.destination-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.destination-flag {
    font-size: 2.5rem;
    margin-right: 15px;
}

.destination-item h3 {
    color: #1e293b;
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.destination-description {
    color: #64748b;
    font-size: 1.1rem;
    line-height: 1.6;
    margin: 15px 0;
}

.destination-details {
    display: flex;
    gap: 20px;
    margin: 20px 0;
    flex-wrap: wrap;
}

.price, .duration {
    background: linear-gradient(45deg, #10b981, #059669);
    color: white;
    padding: 8px 15px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.highlights {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    padding: 15px;
    border-radius: 10px;
    margin-top: 15px;
    color: #0c4a6e;
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(45deg, #2563eb, #1d4ed8);
    color: white;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    color: white;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .recommendations-container {
        padding: 15px;
        padding-top: 90px;
    }
    
    .recommendations-container h2 {
        font-size: 2rem;
    }
    
    .destination-header {
        flex-direction: column;
        text-align: center;
    }
    
    .destination-flag {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .destination-details {
        justify-content: center;
    }
}
</style>

<?php include 'footer.php'; ?>