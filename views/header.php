<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="accueil.php">
            <img src="../public/logo.jpg"
                 alt="Digital Box"
                 style="width:45px;height:45px;margin-right:10px;border-radius:8px;">
            DIGITAL BOX
        </a>

        <div class="navbar-nav ms-auto d-flex align-items-center">
            <a class="nav-link text-white" href="accueil.php">Accueil</a>
            <a class="nav-link text-white" href="demande_conge.php">Congé</a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                <a class="nav-link text-warning" href="../views/admin.php">Administration</a>
            <?php } ?>

            <a class="nav-link text-danger" href="../controllers/LogoutController.php">Déconnexion</a>
        </div>
    </div>
</nav>
