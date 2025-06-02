<?php
header("Location: accueil.php");
exit();
?>
<?php
session_start();
?>

<?php
require_once "_menu.php";
require_once "header.php";
include "connection.php";
error_reporting(E_ALL);
ini_set('display_* errors', 1);

$link_mysql = new PDO('mysql:host=localhost;dbname=base struct', 'root', 'root');
include "connection.php";

$sql ='SELECT * FROM reco_item';


$stmt_sql = $link_mysql->query($sql);

$rows = $stmt_sql->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once "header.php"; ?>

<body>
     <h2 class="section-title">🔮 Recommandations pour vous</h2>
        <div class="recommendation-grid">
            <?php
    if (empty($rows)) {
        echo "<p style='color: red; text-align: center;'>⚠️ Aucun item à recommander. Vérifie ta table <code>reco_item</code>.</p>";
    }
    ?>

            <?php foreach ($rows as $item): ?>
                <div class="film-card">
                    <h3><?= htmlspecialchars($item['titre']) ?></h3>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                    <p class="note">Note moyenne : <?= htmlspecialchars($item['note']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
</body>
</html>
<?php
    $stmt_sql->closeCursor();

