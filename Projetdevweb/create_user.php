<?php
require_once 'db.php';

$name = "Admin Test";
$email = "admin@test.com";
$password = "admin123";
$role = "admin";
$average = 15;

// Hash du mot de passe
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO students (name, email, password, average, role) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $hashedPassword, $average, $role]);

echo "Utilisateur admin ajouté avec succès.";
