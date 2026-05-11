<?php

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/AdminConge.php';

requireAdmin();
requireMethod('POST');

$action = postValue('action');
$demandeId = (int) postValue('demande_id', 0);

if ($demandeId <= 0 || !in_array($action, ['accept', 'refuse'], true)) {
    redirectWithFlash('../views/admin.php', 'danger', 'Action invalide sur la demande de congé.');
}

$admin = new AdminConge(dbConnection());

try {
    $result = $action === 'accept'
        ? $admin->accept($demandeId)
        : $admin->refuse($demandeId);
} catch (Throwable $exception) {
    redirectWithFlash('../views/admin.php', 'danger', 'Une erreur est survenue lors de la mise à jour de la demande.');
}

if (empty($result['updated'])) {
    redirectWithFlash('../views/admin.php', 'danger', 'Demande de congé introuvable.');
}

$message = $action === 'accept'
    ? 'Demande de congé acceptée avec succès.'
    : 'Demande de congé refusée avec succès.';

if (empty($result['email_sent'])) {
    $message .= ' Le statut a été enregistré, mais l\'e-mail n\'a pas pu être envoyé.';
}

redirectWithFlash('../views/admin.php', 'success', $message);
