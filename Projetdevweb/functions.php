<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? 'Utilisateur',
        'role' => $_SESSION['role'] ?? 'user',
    ];
}

function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] === 'admin');
}
