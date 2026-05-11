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
?>

<?php include __DIR__ . '/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4">Demande de Congé</h3>

                <?php include __DIR__ . '/flash.php'; ?>

                <form action="../controllers/CongeController.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Date de début</label>
                        <input type="date" name="date_debut" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date de fin</label>
                        <input type="date" name="date_fin" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre de jours</label>
                        <input type="number" name="nb_jours" class="form-control" min="1" required>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="accueil.php" class="btn btn-outline-secondary w-50">Retour</a>
                        <button type="submit" class="btn btn-primary w-50">Envoyer la demande</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
