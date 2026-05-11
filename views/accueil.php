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
?>

<?php include "../views/header.php"; ?>

<div class="container mt-4">
    <div class="card shadow p-4">
        <h2>Bienvenue <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p class="text-muted">Espace employé</p>

        <?php include __DIR__ . '/flash.php'; ?>

        <hr>

        <h4>Solde de congé</h4>

        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card text-bg-primary p-3">
                    <h5>Total</h5>
                    <h3><?= htmlspecialchars((string) $result['total'], ENT_QUOTES, 'UTF-8') ?> jours</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-warning p-3">
                    <h5>Pris</h5>
                    <h3><?= htmlspecialchars((string) $result['pris'], ENT_QUOTES, 'UTF-8') ?> jours</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-bg-success p-3">
                    <h5>Restant</h5>
                    <h3><?= htmlspecialchars((string) $result['restant'], ENT_QUOTES, 'UTF-8') ?> jours</h3>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="demande_conge.php" class="btn btn-primary">Demander un congé</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
