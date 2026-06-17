<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - DIGITAL BOX</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Style -->
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body class="auth-bg d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card glass-card border-0 fade-in">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-3 p-3 mb-3" style="color: var(--primary);">
                            <i class="bi bi-shield-lock-fill" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="mb-1">Connexion</h3>
                        <p class="text-muted small">Accédez à votre espace Gestion Congé</p>
                    </div>

                    <?php include __DIR__ . '/flash.php'; ?>

                    <form action="../controllers/LoginController.php" method="POST">
                        <div class="form-group-custom">
                            <input type="email" name="email" class="form-control form-control-custom" placeholder="Email" required>
                            <i class="bi bi-envelope form-icon"></i>
                        </div>
                        
                        <div class="form-group-custom">
                            <input type="password" id="password" name="password" class="form-control form-control-custom" placeholder="Mot de passe" required>
                            <i class="bi bi-lock form-icon"></i>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check m-0">
                                <input type="checkbox" class="form-check-input" id="show-pass" onclick="togglePassword()">
                                <label class="form-check-label text-muted small" for="show-pass" style="cursor: pointer; user-select: none;">Afficher le mot de passe</label>
                            </div>
                        </div>

                        <button class="btn btn-primary-custom w-100">Se connecter</button>
                    </form>

                    <div class="text-center mt-4">
                        <span class="text-muted small">Pas encore de compte ?</span>
                        <a href="register.php" class="text-decoration-none small fw-semibold ms-1" style="color: var(--primary);">Créer un compte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>
