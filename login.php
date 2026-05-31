<?php
// ============================================================
// SoulsPets - Login cliente
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: /soulspet/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login exitoso
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = $user['rol'];

            if ($user['rol'] === 'admin') {
                header('Location: /soulspet/admin/');
            } else {
                header('Location: /soulspet/');
            }
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    } else {
        $error = 'Por favor completa todos los campos.';
    }
}

$pageTitle = 'Iniciar Sesión';
include __DIR__ . '/includes/header.php';
?>

<div class="form-page">
    <div class="form-card">
        <h2>Bienvenido de vuelta</h2>
        <p class="subtitle">Inicia sesión para gestionar tus reservas.</p>

        <?php if ($error): ?>
            <div class="flash flash-error" style="margin-bottom:1.25rem;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" data-validate>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="tu@correo.com" required autocomplete="email">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-full">Iniciar Sesión</button>
        </form>

        <div class="form-footer">
            ¿No tienes cuenta? <a href="/soulspet/registro.php">Regístrate gratis</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

