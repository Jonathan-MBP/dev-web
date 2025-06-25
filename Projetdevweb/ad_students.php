<?php
session_start();
require_once 'db.php';

// Vérification admin (à adapter)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Ajouter un étudiant
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $average = $_POST['average'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $stmt = $pdo->prepare("INSERT INTO students (name, email, password, average, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $average, $role]);
    header('Location: students.php');
    exit;
}

// Supprimer étudiant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ad_students.php');
    exit;
}

// Modifier étudiant (formulaire affiché via GET)
$editStudent = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $editStudent = $stmt->fetch();
}

// Mise à jour étudiant
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $average = $_POST['average'];
    
    $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, average = ? WHERE id = ?");
    $stmt->execute([$name, $email, $average, $id]);
    header('Location: ad_students.php');
    exit;
}

// Récupérer tous les étudiants
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();

?>

<?php include 'header.php'; ?>

<div class="container mt-4">
    <h2>Gestion des étudiants</h2>

    <!-- Formulaire ajout / modification -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title"><?= $editStudent ? 'Modifier étudiant' : 'Ajouter un étudiant' ?></h5>
            <form method="post" action="ad_students.php">
                <?php if ($editStudent): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editStudent['id']) ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label>Nom</label>
                    <input type="text" name="name" class="form-control" required value="<?= $editStudent ? htmlspecialchars($editStudent['name']) : '' ?>">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="<?= $editStudent ? htmlspecialchars($editStudent['email']) : '' ?>">
                </div>
                <div class="mb-3">
                    <label>Moyenne</label>
                    <input type="number" step="0.01" name="average" class="form-control" required value="<?= $editStudent ? htmlspecialchars($editStudent['average']) : '' ?>">
                </div>
                <?php if (!$editStudent): ?>
                    <div class="mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                <?php endif; ?>

                <?php if ($editStudent): ?>
                    <button type="submit" name="update" class="btn btn-primary">Mettre à jour</button>
                    <a href="students.php" class="btn btn-secondary">Annuler</a>
                <?php else: ?>
                    <button type="submit" name="add" class="btn btn-success">Ajouter</button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Moyenne</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $student): ?>
                <tr>
                    <td><?= $student['id'] ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['email']) ?></td>
                    <td><?= $student['average'] ?></td>
                    <td><?= $student['role'] ?></td>
                    <td>
                        <a href="ad_students.php?edit=<?= $student['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="ad_students.php?delete=<?= $student['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?')" class="btn btn-sm btn-danger">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
