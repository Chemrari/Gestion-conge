<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Veuillez vous connecter pour accéder à cette page.',
    ];
    header('Location: ../views/login.php');
    exit();
}

if (($_SESSION['role'] ?? 'employe') !== 'admin') {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'Accès refusé.',
    ];
    header('Location: ../views/accueil.php');
    exit();
}

require_once "../config/database.php";
require_once "../models/AdminConge.php";

$db = new Database();
$conn = $db->getConnection();

$admin = new AdminConge($conn);
$demandes = $admin->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration des congés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Gestion des congés</span>
        <a href="../controllers/LogoutController.php" class="btn btn-danger">Déconnexion</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Demandes de congé</h3>

    <?php include __DIR__ . '/flash.php'; ?>

    <table class="table table-bordered text-center bg-white">
        <thead class="table-dark">
            <tr>
                <th>Employé</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Jours</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $d): ?>
                <tr>
                    <td><?= htmlspecialchars($d['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($d['date_debut'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($d['date_fin'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $d['nb_jours'] ?></td>
                    <td>
                        <?php if ($d['statut'] === 'accepte'): ?>
                            <span class="badge bg-success">Accepté</span>
                        <?php elseif ($d['statut'] === 'refuse'): ?>
                            <span class="badge bg-danger">Refusé</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($d['statut'] === 'en_attente'): ?>
                            <div class="d-flex justify-content-center gap-2">
                                <form action="../controllers/AdminCongeController.php" method="POST" class="m-0">
                                    <input type="hidden" name="action" value="accept">
                                    <input type="hidden" name="demande_id" value="<?= (int) $d['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Accepter</button>
                                </form>

                                <form action="../controllers/AdminCongeController.php" method="POST" class="m-0">
                                    <input type="hidden" name="action" value="refuse">
                                    <input type="hidden" name="demande_id" value="<?= (int) $d['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">Terminé</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
