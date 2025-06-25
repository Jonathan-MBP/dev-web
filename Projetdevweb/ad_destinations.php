<?php
session_start();
require_once 'db.php';


// Vérifier si admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$message = '';
$messageType = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $average = floatval($_POST['average']);
        $role = $_POST['role'];
        
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
        $checkStmt->execute([$email]);
        
        if ($checkStmt->fetchColumn() == 0) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (name, email, password, average, role) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $hashedPassword, $average, $role])) {
                $message = "Utilisateur ajouté avec succès !";
                $messageType = "success";
            }
        } else {
            $message = "Cet email existe déjà !";
            $messageType = "danger";
        }
    }
    
    if ($action === 'update') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $average = floatval($_POST['average']);
        $role = $_POST['role'];
        
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, average = ?, role = ? WHERE id = ?");
        if ($stmt->execute([$name, $email, $average, $role, $id])) {
            $message = "Utilisateur modifié avec succès !";
            $messageType = "success";
        }
    }
    
    if ($action === 'delete') {
        $id = intval($_POST['id']);
        $deleteRecoStmt = $pdo->prepare("DELETE FROM recommandations WHERE student_id = ?");
        $deleteRecoStmt->execute([$id]);
        
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        if ($stmt->execute([$id])) {
            $message = "Utilisateur supprimé avec succès !";
            $messageType = "success";
        }
    }
    
    if ($action === 'toggle_admin') {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("SELECT role FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $currentRole = $stmt->fetchColumn();
        
        $newRole = ($currentRole === 'admin') ? 'user' : 'admin';
        $updateStmt = $pdo->prepare("UPDATE students SET role = ? WHERE id = ?");
        if ($updateStmt->execute([$newRole, $id])) {
            $message = "Rôle modifié avec succès !";
            $messageType = "success";
        }
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM students ORDER BY name ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - School Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0"><i class="bi bi-people-fill"></i> Administration des Utilisateurs</h2>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($message): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Formulaire d'ajout -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4><i class="bi bi-person-plus"></i> Ajouter un utilisateur</h4>
                                <form method="POST" class="row g-3">
                                    <input type="hidden" name="action" value="add">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="name" placeholder="Nom complet" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="average" placeholder="Moyenne" step="0.01" min="0" max="20" value="0">
                                    </div>
                                    <div class="col-md-1">
                                        <select class="form-select" name="role">
                                            <option value="user">User</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Liste des utilisateurs -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
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
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo $user['average']; ?>/20</td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['role'] === 'admin' ? 'danger' : 'success'; ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- Bouton modifier -->
                                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"
                                                        onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                
                                                <!-- Toggle admin -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="toggle_admin">
                                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-<?php echo $user['role'] === 'admin' ? 'secondary' : 'primary'; ?>"
                                                            onclick="return confirm('Changer le rôle ?')">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                                
                                                <!-- Supprimer -->
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Supprimer cet utilisateur ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Moyenne</label>
                            <input type="number" class="form-control" name="average" id="edit_average" step="0.01" min="0" max="20">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle</label>
                            <select class="form-select" name="role" id="edit_role">
                                <option value="user">Utilisateur</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editUser(user) {
            document.getElementById('edit_id').value = user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_email').value = user.email;
            document.getElementById('edit_average').value = user.average;
            document.getElementById('edit_role').value = user.role;
        }
    </script>
</body>
</html>