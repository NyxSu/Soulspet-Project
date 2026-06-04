<?php
// ============================================================
// SoulsPets - Header del panel administrador
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

// Verificar acceso admin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: /soulspet/admin/login.php');
    exit;
}

$adminPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Admin SoulsPets' : 'Panel Admin | SoulsPets' ?></title>
    <link rel="stylesheet" href="/soulspet/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="admin-bar">
    <a href="/soulspet/admin/" class="logo">
        <span class="logo-icon">🐾</span>
        <span class="logo-text">Souls<em>Pets</em> <small style="font-size:.65rem;color:var(--color-gold);font-family:var(--font-body);">ADMIN</small></span>
    </a>
    <nav class="admin-nav">
        <a href="/soulspet/admin/" class="<?= $adminPage === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="/soulspet/admin/reservas.php" class="<?= $adminPage === 'reservas.php' ? 'active' : '' ?>">Reservas</a>
        <a href="/soulspet/admin/usuarios.php" class="<?= $adminPage === 'usuarios.php' ? 'active' : '' ?>">Usuarios</a>
        <a href="/soulspet/" style="color:var(--color-muted);font-size:.85rem;">Ver sitio</a>
        <a href="/soulspet/logout.php" style="color:var(--color-danger);font-size:.85rem;">Salir</a>
    </nav>
</div>

<!-- Flash -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash flash-<?= htmlspecialchars($_SESSION['flash']['tipo']) ?>" style="margin:1rem 1.5rem 0;">
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<main class="main-content" style="padding:2rem 1.5rem;max-width:1200px;margin:0 auto;">
