<?php
// ============================================================
// SoulsPets - Registro de cliente
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: /soulspet/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre']   ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (!$nombre || !$email || !$password || !$confirm) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $db   = getDB();
        // Verificar email único
        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Ya existe una cuenta con ese correo.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins  = $db->prepare('INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, "cliente")');
            $ins->execute([$nombre, $email, $hash]);

            // Auto-login tras registro
            $_SESSION['usuario_id'] = $db->lastInsertId();
            $_SESSION['nombre']     = $nombre;
            $_SESSION['rol']        = 'cliente';

            $_SESSION['flash'] = ['tipo' => 'success', 'msg' => '¡Bienvenido a SoulsPets, ' . $nombre . '!'];
            header('Location: /soulspet/');
            exit;
        }
    }
}

$pageTitle = 'Registro';
include __DIR__ . '/includes/header.php';
?>

<div class="form-page">
    <div class="form-card">
        <h2>Crea tu cuenta</h2>
        <p class="subtitle">Regístrate gratis y empieza a cuidar a tu mascota.</p>

        <?php if ($error): ?>
            <div class="flash flash-error" style="margin-bottom:1.25rem;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" data-validate>
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                       placeholder="Juan Pérez" required>
            </div>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="tu@correo.com" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password"
                       placeholder="Mínimo 6 caracteres" required>
            </div>
            <div class="form-group">
                <label for="confirm">Confirmar contraseña</label>
                <input type="password" id="confirm" name="confirm"
                       placeholder="Repite tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Crear cuenta</button>
        </form>

        <div class="form-footer">
            ¿Ya tienes cuenta? <a href="/soulspet/login.php">Iniciar sesión</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
