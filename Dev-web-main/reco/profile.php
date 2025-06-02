<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['user'])) {
    header("Location:login.php?msg=Vous n'êtes pas connecté");
    exit();
}

$id = $_SESSION['user']['id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $profilePicturePath = $_SESSION['user']['profile_picture'] ?? null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $uploadDir = 'image/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $newFilename = uniqid('pp_') . '.' . $ext;
        $destination = $uploadDir . $newFilename;

        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
        $profilePicturePath = $destination;
    }

    try {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $connexion->prepare("UPDATE userSET username = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?");
            $stmt->execute([$username, $email, $hashedPassword, $profilePicturePath, $id]);
        } else {
            $stmt = $connexion->prepare("UPDATE user SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
            $stmt->execute([$username, $email, $profilePicturePath, $id]);
        }

        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['profile_picture'] = $profilePicturePath;
        $msg = "✅ Profil mis à jour avec succès.";
    } catch (PDOException $e) {
        $msg = "❌ Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>

<?php require_once "header.php"; ?>
<?php require_once "_menu.php"; ?>

<div class="crud-container">

    <div style="height: 200px;"></div>
    <h1>Mon profil</h1>
    <div style="display: flex; justify-content: center">
    <?php if (!empty($msg)) : ?>
        <p class="alert success"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['user']['profile_picture'])): ?>
        <img src="<?= htmlspecialchars($_SESSION['user']['profile_picture']) ?>" alt="Photo de profil" style="width: 100px; height: 100px; border-radius: 50%;">
    <?php endif; ?>
    </div
    <form method="POST" enctype="multipart/form-data">
        <label for="username">Nom d'utilisateur</label>
        <input type="text" name="username" id="username" required value="<?= htmlspecialchars($_SESSION['user']['username']) ?>">

        <label for="email">Adresse email</label>
        <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">

        <label for="password">Nouveau mot de passe (laisser vide si inchangé)</label>
        <input type="password" name="password" id="password">

        <label for="profile_picture">Photo de profil</label>
        <input type="file" name="profile_picture" accept="image/*">
        <div class="profile-pic" style="background-image: url('user.jpg');"></div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
</body>
</html>
