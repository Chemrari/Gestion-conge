<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/conge.php';

requireAuth();
requireMethod('POST');

$dateDebut = postValue('date_debut');
$dateFin = postValue('date_fin');
$nbJours = (int) postValue('nb_jours', 0);

if ($dateDebut === '' || $dateFin === '' || $nbJours <= 0) {
    redirectWithFlash('../views/demande_conge.php', 'danger', 'Veuillez remplir tous les champs de la demande de congé.');
}

$startDate = DateTime::createFromFormat('Y-m-d', $dateDebut);
$endDate = DateTime::createFromFormat('Y-m-d', $dateFin);

if (!$startDate || !$endDate) {
    redirectWithFlash('../views/demande_conge.php', 'danger', 'Les dates de congé sont invalides.');
}

if ($endDate < $startDate) {
    redirectWithFlash('../views/demande_conge.php', 'danger', 'La date de fin doit être postérieure à la date de début.');
}

$maxDays = $startDate->diff($endDate)->days + 1;
if ($nbJours > $maxDays) {
    redirectWithFlash('../views/demande_conge.php', 'danger', 'Le nombre de jours demandés dépasse la période sélectionnée.');
}

$conge = new Conge(dbConnection());
$created = $conge->addDemande((int) $_SESSION['user_id'], $dateDebut, $dateFin, $nbJours);

if (!$created) {
    redirectWithFlash('../views/demande_conge.php', 'danger', 'Impossible d\'enregistrer votre demande de congé.');
}

redirectWithFlash('../views/accueil.php', 'success', 'Votre demande de congé a été envoyée avec succès.');
