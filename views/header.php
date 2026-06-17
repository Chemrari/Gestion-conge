<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Congés - DIGITAL BOX</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Style -->
    <link href="../public/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="accueil.php">
            <img src="../public/logo.jpg"
                 alt="Digital Box"
                 style="width:40px;height:40px;margin-right:12px;border-radius:10px;object-fit:cover;border:2px solid rgba(255,255,255,0.2);">
            <span style="font-weight: 800; letter-spacing: 0.05em;">DIGITAL BOX</span>
        </a>

        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto d-flex align-items-center">
                <a class="nav-link <?= $currentPage === 'accueil.php' ? 'active' : '' ?>" href="accueil.php">
                    <i class="bi bi-house-door me-1"></i> Accueil
                </a>
                <a class="nav-link <?= $currentPage === 'demande_conge.php' ? 'active' : '' ?>" href="demande_conge.php">
                    <i class="bi bi-calendar-plus me-1"></i> Congé
                </a>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                    <a class="nav-link <?= $currentPage === 'admin.php' ? 'active' : '' ?> text-warning-hover" href="../views/admin.php">
                        <i class="bi bi-shield-lock me-1"></i> Administration
                    </a>
                <?php } ?>

                <a class="nav-link btn-logout ms-lg-3 mt-2 mt-lg-0" href="../controllers/LogoutController.php">
                    <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</nav>
