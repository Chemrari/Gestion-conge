<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/login.php';

requireMethod('POST');

$email = postValue('email');
$password = postValue('password');

if ($email === '' || $password === '') {
    redirectWithFlash('../views/login.php', 'danger', 'L\'e-mail et le mot de passe sont obligatoires.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithFlash('../views/login.php', 'danger', 'Veuillez saisir une adresse e-mail valide.');
}

$login = new Login(dbConnection());
$user = $login->authenticate($email, $password);

if (!$user) {
    redirectWithFlash('../views/login.php', 'danger', 'E-mail ou mot de passe incorrect.');
}

session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['nom'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = $user['role'] ?? 'employe';

if (($_SESSION['role'] ?? 'employe') === 'admin') {
    redirectTo('../views/admin.php');
}

redirectTo('../views/accueil.php');
