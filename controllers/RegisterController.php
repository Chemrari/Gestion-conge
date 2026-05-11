<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Register.php';

requireMethod('POST');

$nom = postValue('nom');
$cin = strtoupper(postValue('cin'));
$email = postValue('email');
$num = postValue('num');
$password = postValue('password');

if ($nom === '' || $cin === '' || $email === '' || $num === '' || $password === '') {
    redirectWithFlash('../views/register.php', 'danger', 'Tous les champs sont obligatoires.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithFlash('../views/register.php', 'danger', 'Veuillez saisir une adresse e-mail valide.');
}

if (strlen($password) < 8) {
    redirectWithFlash('../views/register.php', 'danger', 'Le mot de passe doit contenir au moins 8 caractères.');
}

$register = new Register(dbConnection());
$result = $register->create($nom, $cin, $email, $num, $password);

if ($result === true) {
    redirectWithFlash('../views/login.php', 'success', 'Compte créé avec succès. Vous pouvez maintenant vous connecter.');
}

redirectWithFlash('../views/register.php', 'danger', (string) $result);
