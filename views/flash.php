<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if (!is_array($flash) || empty($flash['message'])) {
    return;
}

$type = preg_replace('/[^a-z]/', '', (string) ($flash['type'] ?? 'info'));
$message = (string) $flash['message'];
?>

<div class="alert alert-<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert">
    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
