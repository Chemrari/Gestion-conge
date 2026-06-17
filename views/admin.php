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

// Calculate stats for admin dashboard
$totalRequests = count($demandes);
$pendingRequests = 0;
$acceptedRequests = 0;
$refusedRequests = 0;

foreach ($demandes as $d) {
    if ($d['statut'] === 'accepte') {
        $acceptedRequests++;
    } elseif ($d['statut'] === 'refuse') {
        $refusedRequests++;
    } else {
        $pendingRequests++;
    }
}
?>

<?php include __DIR__ . '/header.php'; ?>

<div class="container py-5 fade-in">
    <!-- Admin Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="glass-card p-4 border-0 d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="mb-1">Administration des congés</h2>
                    <p class="text-muted mb-0">Espace Responsable &bull; Validation des demandes collaborateurs</p>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; color: var(--warning-text);">
                    <i class="bi bi-shield-check" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/flash.php'; ?>

    <!-- Stat cards row -->
    <div class="row g-4 mb-5">
        <div class="col-6 col-lg-3">
            <div class="stat-card-mini">
                <div class="stat-icon primary">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Total Demandes</p>
                    <h4 class="mb-0 fw-bold"><?= $totalRequests ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-mini">
                <div class="stat-icon warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">En attente</p>
                    <h4 class="mb-0 fw-bold text-warning"><?= $pendingRequests ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-mini">
                <div class="stat-icon success">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Acceptées</p>
                    <h4 class="mb-0 fw-bold text-success"><?= $acceptedRequests ?></h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-mini">
                <div class="stat-icon danger">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div>
                    <p class="text-muted small mb-0">Refusées</p>
                    <h4 class="mb-0 fw-bold text-danger"><?= $refusedRequests ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <h4 class="mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-list-task text-primary"></i> Liste des demandes
    </h4>
    <div class="glass-card border-0 p-4">
        <?php if (empty($demandes)): ?>
            <div class="text-center py-5">
                <div class="text-muted mb-3">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-muted">Aucune demande de congé pour le moment</h5>
                <p class="text-muted small">Les demandes soumises par les collaborateurs apparaîtront ici.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive-custom">
                <table class="table table-custom text-center align-middle">
                    <thead>
                        <tr>
                            <th>Collaborateur</th>
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
                                <td class="fw-bold text-dark text-start ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 700; color: var(--text-main);">
                                            <?= strtoupper(substr(trim($d['nom']), 0, 2)) ?>
                                        </div>
                                        <div>
                                            <span class="d-block"><?= htmlspecialchars($d['nom'], ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="text-muted small fw-normal" style="font-size: 0.75rem;"><?= htmlspecialchars($d['email'], ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-semibold text-secondary">
                                    <i class="bi bi-calendar-event me-1 text-primary text-opacity-50"></i>
                                    <?= htmlspecialchars(date('d/m/Y', strtotime($d['date_debut'])), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="fw-semibold text-secondary">
                                    <i class="bi bi-calendar-event me-1 text-primary text-opacity-50"></i>
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
                                            <i class="bi bi-hourglass-split"></i> En attente
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['statut'] === 'en_attente'): ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="../controllers/AdminCongeController.php" method="POST" class="m-0">
                                                <input type="hidden" name="action" value="accept">
                                                <input type="hidden" name="demande_id" value="<?= (int) $d['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success px-3 d-inline-flex align-items-center gap-1" style="border-radius: var(--radius-sm);">
                                                    <i class="bi bi-check-lg"></i> Accepter
                                                </button>
                                            </form>
 
                                            <form action="../controllers/AdminCongeController.php" method="POST" class="m-0">
                                                <input type="hidden" name="action" value="refuse">
                                                <input type="hidden" name="demande_id" value="<?= (int) $d['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger px-3 d-inline-flex align-items-center gap-1" style="border-radius: var(--radius-sm);">
                                                    <i class="bi bi-x-lg"></i> Refuser
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fw-medium">
                                            <i class="bi bi-lock-fill me-1"></i> Traité
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
