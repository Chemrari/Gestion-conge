<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function dbConnection() {
    $database = new Database();
    $connection = $database->getConnection();

    if (!$connection) {
        setFlash('danger', 'Connexion à la base de données échouée.');
        redirectTo('../views/login.php');
    }

    return $connection;
}

function redirectTo($path) {
    header('Location: ' . $path);
    exit();
}

function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function redirectWithFlash($path, $type, $message) {
    setFlash($type, $message);
    redirectTo($path);
}

function requireMethod($method) {
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        redirectWithFlash('../views/login.php', 'warning', 'Méthode de requête invalide.');
    }
}

function requireAuth() {
    if (empty($_SESSION['user_id'])) {
        redirectWithFlash('../views/login.php', 'warning', 'Veuillez vous connecter pour accéder à cette page.');
    }
}

function requireAdmin() {
    requireAuth();

    if (($_SESSION['role'] ?? 'employe') !== 'admin') {
        redirectWithFlash('../views/accueil.php', 'danger', 'Accès refusé. Vous n\'avez pas les droits nécessaires.');
    }
}

function postValue($key, $default = '') {
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function getValue($key, $default = '') {
    return isset($_GET[$key]) ? trim((string) $_GET[$key]) : $default;
}
