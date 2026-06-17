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

<div class="container py-5 fade-in">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card glass-card border-0 p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 p-3 mb-3" style="color: var(--primary);">
                        <i class="bi bi-calendar2-plus" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="mb-1">Nouvelle Demande</h3>
                    <p class="text-muted small">Remplissez le formulaire ci-dessous pour soumettre votre demande de congé</p>
                </div>

                <?php include __DIR__ . '/flash.php'; ?>

                <form action="../controllers/CongeController.php" method="POST" id="conge-form">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-calendar-range me-1 text-primary"></i> Date de début
                        </label>
                        <input type="date" name="date_debut" class="form-control form-control-custom px-3" style="padding-left: 1rem !important;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-calendar-range-fill me-1 text-primary"></i> Date de fin
                        </label>
                        <input type="date" name="date_fin" class="form-control form-control-custom px-3" style="padding-left: 1rem !important;" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-hash me-1 text-primary"></i> Nombre de jours
                        </label>
                        <input type="number" name="nb_jours" class="form-control form-control-custom px-3" style="padding-left: 1rem !important;" min="1" placeholder="Calculé automatiquement..." required>
                        <div class="form-text text-muted small mt-1">Calculé automatiquement, modifiable si nécessaire (ex: demi-journées).</div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <a href="accueil.php" class="btn btn-secondary-custom w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left"></i> Annuler
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary-custom w-100 d-inline-flex align-items-center justify-content-center gap-2">
                                Envoyer <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputDebut = document.querySelector('input[name="date_debut"]');
    const inputFin = document.querySelector('input[name="date_fin"]');
    const inputJours = document.querySelector('input[name="nb_jours"]');

    function calculateDays() {
        const valDebut = inputDebut.value;
        const valFin = inputFin.value;

        if (valDebut && valFin) {
            const date1 = new Date(valDebut);
            const date2 = new Date(valFin);

            if (date2 >= date1) {
                // Calculation includes both start and end days
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                inputJours.value = diffDays;
            } else {
                inputJours.value = '';
            }
        }
    }

    inputDebut.addEventListener('change', calculateDays);
    inputFin.addEventListener('change', calculateDays);
});
</script>
</body>
</html>
