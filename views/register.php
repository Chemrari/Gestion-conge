<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Inscription</h3>

                    <?php include __DIR__ . '/flash.php'; ?>

                    <form method="POST" action="../controllers/RegisterController.php" onsubmit="return validateForm()">
                        <input type="text" name="nom" class="form-control mb-3" placeholder="Nom" required>
                        <input type="text" name="cin" class="form-control mb-3" placeholder="CIN" required>
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="text" name="num" class="form-control mb-3" placeholder="Numéro de téléphone" required>
                        <input type="password" id="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>

                        <div id="error" class="text-danger mb-3"></div>

                        <button class="btn btn-primary w-100">S'inscrire</button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted">Déjà inscrit ?</span>
                        <a href="login.php" class="text-decoration-none">Se connecter</a>
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
