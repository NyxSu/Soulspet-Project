<?php
// ============================================================
// SoulsPets - Setup inicial (EJECUTAR UNA SOLA VEZ)
// URL: http://localhost/soulspet/setup.php
//
// Este script regenera el hash de contraseña del admin con
// PHP nativo para garantizar compatibilidad total.
//
// ⚠️ ELIMINA ESTE ARCHIVO después de ejecutarlo.
// ============================================================
require_once __DIR__ . '/config/database.php';

$db   = getDB();
$hash = password_hash('Admin123', PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE usuarios SET password_hash = ? WHERE email = 'admin@soulspet.com'");
$stmt->execute([$hash]);

$ok = $stmt->rowCount() > 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup | SoulsPets</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f0f0f; color: #e8e4de; font-family: 'DM Sans', sans-serif;
               display: flex; align-items: center; justify-content: center;
               min-height: 100vh; padding: 1rem; }
        .card { background: #1e1e1e; border: 1px solid #2e2e2e; border-radius: 16px;
                padding: 2.5rem; max-width: 480px; width: 100%; }
        .icon { font-size: 3rem; margin-bottom: 1rem; }
        h2 { font-size: 1.5rem; color: #f5f0e8; margin-bottom: .75rem; }
        p  { color: #888880; font-size: .95rem; line-height: 1.6; }
        .info-box { background: #222; border: 1px solid #3a3a3a; border-radius: 8px;
                    padding: 1rem 1.25rem; margin: 1.25rem 0; }
        .info-box strong { color: #e8e4de; }
        .warn { background: rgba(192,57,43,.1); border-color: rgba(192,57,43,.3);
                color: #e74c3c; border-radius: 8px; border: 1px solid; padding: 1rem 1.25rem;
                margin-top: 1.25rem; font-size: .88rem; line-height: 1.5; }
        a.btn { display: inline-block; margin-top: 1.5rem; background: #c8a96e; color: #0f0f0f;
                font-weight: 600; padding: .75rem 1.5rem; border-radius: 6px;
                text-decoration: none; font-size: .95rem; }
        a.btn:hover { background: #e2c98a; }
        .err h2 { color: #e74c3c; }
    </style>
</head>
<body>
<div class="card <?= $ok ? '' : 'err' ?>">
    <?php if ($ok): ?>
        <div class="icon">✅</div>
        <h2>Configuración completada</h2>
        <p>El hash de contraseña del administrador fue regenerado correctamente con PHP nativo.</p>
        <div class="info-box">
            <p><strong>Email:</strong> admin@soulspet.com</p>
            <p><strong>Contraseña:</strong> Admin123</p>
        </div>
        <div class="warn">
            ⚠️ <strong>Elimina este archivo ahora.</strong><br>
            Borra <code>setup.php</code> de la carpeta del proyecto antes de compartirlo o subirlo.
        </div>
        <a class="btn" href="/soulspet/admin/login.php">Ir al panel admin →</a>
    <?php else: ?>
        <div class="icon">❌</div>
        <h2>No se encontró el admin</h2>
        <p>Asegúrate de haber importado primero el archivo <strong>database/soulspet.sql</strong> en phpMyAdmin, y que la base de datos se llame <strong>soulspet</strong>.</p>
        <a class="btn" style="background:#444;color:#e8e4de;" href="/soulspet/setup.php">Reintentar</a>
    <?php endif; ?>
</div>
</body>
</html>
