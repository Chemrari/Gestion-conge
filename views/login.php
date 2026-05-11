<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Connexion</h3>

                    <?php include __DIR__ . '/flash.php'; ?>

                    <form action="../controllers/LoginController.php" method="POST">
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" id="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" onclick="togglePassword()">
                            <label class="form-check-label">Afficher le mot de passe</label>
                        </div>

                        <button class="btn btn-primary w-100">Se connecter</button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted">Pas encore de compte ?</span>
                        <a href="register.php" class="text-decoration-none">Créer un compte</a>
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
