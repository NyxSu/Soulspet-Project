<?php
// ============================================================
// SoulsPets - Perfil del cliente
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: /soulspet/login.php');
    exit;
}

$userId = $_SESSION['usuario_id'];
$db     = getDB();
$error  = '';
$ok     = '';

// Obtener datos actuales
$stmt = $db->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Actualizar nombre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_nombre'])) {
    $nuevoNombre = trim($_POST['nombre'] ?? '');
    if ($nuevoNombre) {
        $upd = $db->prepare('UPDATE usuarios SET nombre = ? WHERE id = ?');
        $upd->execute([$nuevoNombre, $userId]);
        $_SESSION['nombre'] = $nuevoNombre;
        $ok = 'Nombre actualizado correctamente.';
        $user['nombre'] = $nuevoNombre;
    } else {
        $error = 'El nombre no puede estar vacío.';
    }
}

// Cambiar contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_password'])) {
    $actual   = $_POST['password_actual']   ?? '';
    $nueva    = $_POST['password_nueva']    ?? '';
    $confirmar = $_POST['password_confirmar'] ?? '';

    if (!$actual || !$nueva || !$confirmar) {
        $error = 'Completa todos los campos de contraseña.';
    } elseif (!password_verify($actual, $user['password_hash'])) {
        $error = 'La contraseña actual es incorrecta.';
    } elseif (strlen($nueva) < 6) {
        $error = 'La nueva contraseña debe tener al menos 6 caracteres.';
    } elseif ($nueva !== $confirmar) {
        $error = 'Las contraseñas nuevas no coinciden.';
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $upd  = $db->prepare('UPDATE usuarios SET password_hash = ? WHERE id = ?');
        $upd->execute([$hash, $userId]);
        $ok = 'Contraseña actualizada correctamente.';
    }
}

$pageTitle = 'Mi Perfil';
include __DIR__ . '/includes/header.php';
?>

<div class="page-hero">
    <span class="section-tag">Tu cuenta</span>
    <h1>Mi Perfil</h1>
    <p>Administra tu información personal.</p>
</div>

<div class="content-section">
    <?php if ($error): ?><div class="flash flash-error" style="margin-bottom:1.5rem;"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($ok):    ?><div class="flash flash-success" style="margin-bottom:1.5rem;"><?= htmlspecialchars($ok) ?></div><?php endif; ?>

    <div class="profile-grid">

        <!-- Editar nombre -->
        <div class="form-card" style="max-width:100%;">
            <h3 style="margin-bottom:.5rem;">Datos personales</h3>
            <p class="text-muted" style="font-size:.88rem;margin-bottom:1.5rem;">Actualiza tu nombre.</p>
            <form method="POST">
                <input type="hidden" name="actualizar_nombre" value="1">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                           style="opacity:.5;cursor:not-allowed;">
                </div>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>

        <!-- Cambiar contraseña -->
        <div class="form-card" style="max-width:100%;">
            <h3 style="margin-bottom:.5rem;">Cambiar contraseña</h3>
            <p class="text-muted" style="font-size:.88rem;margin-bottom:1.5rem;">Elige una contraseña segura.</p>
            <form method="POST">
                <input type="hidden" name="cambiar_password" value="1">
                <div class="form-group">
                    <label>Contraseña actual</label>
                    <input type="password" name="password_actual" placeholder="Tu contraseña actual" required>
                </div>
                <div class="form-group">
                    <label>Nueva contraseña</label>
                    <input type="password" name="password_nueva" placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label>Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmar" placeholder="Repite la nueva contraseña" required>
                </div>
                <button type="submit" class="btn btn-outline">Actualizar contraseña</button>
            </form>
        </div>

    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
