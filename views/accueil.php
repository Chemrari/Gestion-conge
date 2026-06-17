<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Veuillez vous connecter pour accéder à cette page.',
    ];
    header('Location: login.php');
    exit();
}

require_once "../config/database.php";
require_once "../models/conge.php";

$db = new Database();
$conn = $db->getConnection();

$conge = new Conge($conn);
$result = $conge->calculSolde($_SESSION['user_id']);
$demandes = $conge->getDemandes($_SESSION['user_id']);
?>

<?php include "../views/header.php"; ?>

<div class="container py-5 fade-in">
    <!-- Welcome Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="glass-card p-4 p-md-5 border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; color: var(--primary);">
                            <i class="bi bi-person-workspace" style="font-size: 1.75rem;"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">Bonjour, <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?> 👋</h2>
                            <p class="text-muted mb-0">Espace Collaborateur &bull; <?= htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="demande_conge.php" class="btn btn-primary-custom px-4 d-inline-flex align-items-center gap-2">
                        <i class="bi bi-calendar-plus"></i> Demander un congé
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/flash.php'; ?>

    <!-- Solde Section -->
    <h4 class="mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-pie-chart text-primary"></i> Solde de vos congés
    </h4>
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="balance-card balance-card-primary">
                <p class="text-white text-opacity-75 small text-uppercase fw-semibold mb-1">Droit Total</p>
                <h3 class="text-white mb-0 display-6 fw-bold"><?= htmlspecialchars((string) number_format($result['total'], 1), ENT_QUOTES, 'UTF-8') ?> <span style="font-size: 1.25rem; font-weight: 500;">jours</span></h3>
                <div class="icon-wrapper">
                    <i class="bi bi-calendar-month"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="balance-card balance-card-warning">
                <p class="text-white text-opacity-75 small text-uppercase fw-semibold mb-1">Congés Pris</p>
                <h3 class="text-white mb-0 display-6 fw-bold"><?= htmlspecialchars((string) number_format($result['pris'], 1), ENT_QUOTES, 'UTF-8') ?> <span style="font-size: 1.25rem; font-weight: 500;">jours</span></h3>
                <div class="icon-wrapper">
                    <i class="bi bi-calendar-x"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="balance-card balance-card-success">
                <p class="text-white text-opacity-75 small text-uppercase fw-semibold mb-1">Solde Restant</p>
                <h3 class="text-white mb-0 display-6 fw-bold"><?= htmlspecialchars((string) number_format($result['restant'], 1), ENT_QUOTES, 'UTF-8') ?> <span style="font-size: 1.25rem; font-weight: 500;">jours</span></h3>
                <div class="icon-wrapper">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- History Section -->
    <h4 class="mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-clock-history text-primary"></i> Historique de vos demandes
    </h4>
    <div class="glass-card border-0 p-4">
        <?php if (empty($demandes)): ?>
            <div class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">Aucune demande de congé enregistrée</h5>
                <p class="text-muted small">Vos demandes apparaîtront ici dès que vous en soumettrez une.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive-custom">
                <table class="table table-custom text-center align-middle">
                    <thead>
                        <tr>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Jours demandés</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($demandes as $d): ?>
                            <tr>
                                <td class="fw-semibold text-secondary">
                                    <i class="bi bi-calendar-event me-2 text-primary text-opacity-75"></i>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($d['date_debut'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="fw-semibold text-secondary">
                                    <i class="bi bi-calendar-event me-2 text-primary text-opacity-75"></i>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($d['date_fin'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="fw-bold text-dark">
                                    <?= (int) $d['nb_jours'] ?> <?= $d['nb_jours'] > 1 ? 'jours' : 'jour' ?>
                                </td>
                                <td>
                                    <?php if ($d['statut'] === 'accepte'): ?>
                                        <span class="badge-custom badge-custom-success">
                                            <i class="bi bi-check-circle-fill"></i> Accepté
                                        </span>
                                    <?php elseif ($d['statut'] === 'refuse'): ?>
                                        <span class="badge-custom badge-custom-danger">
                                            <i class="bi bi-x-circle-fill"></i> Refusé
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-custom badge-custom-warning">
                                            <i class="bi bi-hourglass-split animate-spin"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
