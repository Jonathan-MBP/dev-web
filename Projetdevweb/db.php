<?php
// includes/db.php
$host = 'localhost';
$dbname = 'school_travel';
$user = 'root';
$pass = 'root'; // à adapter

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur DB: " . $e->getMessage());
}
