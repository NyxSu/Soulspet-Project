<?php
// ============================================================
// SoulsPets - Header común para páginas de cliente
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

$loggedIn   = isset($_SESSION['usuario_id']);
$esAdmin    = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$paginaActual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | SoulsPets' : 'SoulsPets - Centro de Cuidado' ?></title>
    <link rel="stylesheet" href="/soulspet/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="/soulspet/" class="logo">
            <span class="logo-icon">🐾</span>
            <span class="logo-text">Souls<em>Pets</em></span>
        </a>

        <!-- Hamburguesa móvil -->
        <button class="nav-toggle" id="navToggle" aria-label="Menú">
            <span></span><span></span><span></span>
        </button>

        <!-- Navegación -->
        <nav class="main-nav" id="mainNav">
            <a href="/soulspet/" class="<?= $paginaActual === 'index.php' ? 'active' : '' ?>">Inicio</a>
            <a href="/soulspet/servicios.php" class="<?= $paginaActual === 'servicios.php' ? 'active' : '' ?>">Servicios</a>

            <?php if ($loggedIn && !$esAdmin): ?>
                <a href="/soulspet/reservar.php" class="<?= $paginaActual === 'reservar.php' ? 'active' : '' ?>">Reservar</a>
                <a href="/soulspet/mis_reservas.php" class="<?= $paginaActual === 'mis_reservas.php' ? 'active' : '' ?>">Mis Reservas</a>
                <a href="/soulspet/perfil.php" class="<?= $paginaActual === 'perfil.php' ? 'active' : '' ?>">Perfil</a>
                <a href="/soulspet/logout.php" class="btn-nav-logout">Cerrar Sesión</a>
            <?php elseif ($esAdmin): ?>
                <a href="/soulspet/admin/">Panel Admin</a>
                <a href="/soulspet/logout.php" class="btn-nav-logout">Cerrar Sesión</a>
            <?php else: ?>
                <a href="/soulspet/login.php" class="<?= $paginaActual === 'login.php' ? 'active' : '' ?>">Iniciar Sesión</a>
                <a href="/soulspet/registro.php" class="btn-nav-cta <?= $paginaActual === 'registro.php' ? 'active' : '' ?>">Registrarse</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Mensajes flash -->
<?php if (isset($_SESSION['flash'])): ?>
    <div class="flash flash-<?= htmlspecialchars($_SESSION['flash']['tipo']) ?>">
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<main class="main-content">
