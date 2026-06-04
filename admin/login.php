<?php
// ============================================================
// SoulsPets - Login administrador
// ============================================================
session_start();
require_once __DIR__ . '/../config/database.php';

// Si ya es admin, redirigir al panel
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header('Location: /soulspet/admin/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = ? AND rol = 'admin'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre']     = $user['nombre'];
            $_SESSION['rol']        = 'admin';
            header('Location: /soulspet/admin/');
            exit;
        } else {
            $error = 'Credenciales incorrectas o no tienes permisos de administrador.';
        }
    } else {
        $error = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SoulsPets</title>
    <link rel="stylesheet" href="/soulspet/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<div class="form-page">
    <div class="form-card">
        <div class="text-center" style="margin-bottom:1.75rem;">
            <span style="font-size:2.5rem;">🔐</span>
            <h2 style="margin-top:.5rem;">Panel Administrador</h2>
            <p class="subtitle">Acceso exclusivo para administradores.</p>
        </div>

        <?php if ($error): ?>
            <div class="flash flash-error" style="margin-bottom:1.25rem;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Correo administrador</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="admin@soulspet.com" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Acceder al panel</button>
        </form>

        <div class="form-footer">
            <a href="/soulspet/">← Volver al sitio</a>
        </div>
    </div>
</div>
</body>
</html>
