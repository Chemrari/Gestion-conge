<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - DIGITAL BOX</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Style -->
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body class="auth-bg d-flex align-items-center" style="min-height: 100vh;">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card glass-card border-0 fade-in">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 p-3 mb-3" style="color: var(--primary);">
                            <i class="bi bi-person-plus-fill" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="mb-1">Inscription</h3>
                        <p class="text-muted small">Créez votre compte collaborateur</p>
                    </div>

                    <?php include __DIR__ . '/flash.php'; ?>

                    <form method="POST" action="../controllers/RegisterController.php" onsubmit="return validateForm()">
                        <div class="form-group-custom">
                            <input type="text" name="nom" class="form-control form-control-custom" placeholder="Nom complet" required>
                            <i class="bi bi-person form-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="text" name="cin" class="form-control form-control-custom" placeholder="CIN (Carte d'identité)" required>
                            <i class="bi bi-card-text form-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="email" name="email" class="form-control form-control-custom" placeholder="Adresse e-mail" required>
                            <i class="bi bi-envelope form-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="text" name="num" class="form-control form-control-custom" placeholder="Numéro de téléphone" required>
                            <i class="bi bi-telephone form-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="password" id="password" name="password" class="form-control form-control-custom" placeholder="Mot de passe" required>
                            <i class="bi bi-lock form-icon"></i>
                        </div>

                        <div id="error" class="text-danger mb-3 small fw-semibold"></div>

                        <button class="btn btn-primary-custom w-100">S'inscrire</button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Déjà inscrit ?</span>
                        <a href="login.php" class="text-decoration-none small fw-semibold ms-1" style="color: var(--primary);">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateForm() {
    const password = document.getElementById('password').value;
    const regex = /^(?=.*[A-Za-z]).{8,}$/;

    if (!regex.test(password)) {
        document.getElementById('error').innerText =
            'Le mot de passe doit contenir au moins 8 caractères et une lettre.';
        return false;
    }

    document.getElementById('error').innerText = '';
    return true;
}
</script>

</body>
</html>
