<?php
// ============================================================
// SoulsPets - Lista de usuarios (admin) — con DELETE
// ============================================================
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Usuarios';
include __DIR__ . '/includes_admin_header.php';

$db = getDB();

// ── ELIMINAR USUARIO ────────────────────────────────────────
if (isset($_GET['eliminar'])) {
    $uid = intval($_GET['eliminar']);

    // No permitir que el admin se elimine a sí mismo
    if ($uid === intval($_SESSION['usuario_id'])) {
        $_SESSION['flash'] = ['tipo' => 'error', 'msg' => 'No puedes eliminar tu propia cuenta de administrador.'];
        header('Location: /admin/usuarios.php');
        exit;
    }

    // Verificar que es un cliente (no otro admin)
    $chk = $db->prepare("SELECT id, nombre FROM usuarios WHERE id = ? AND rol = 'cliente'");
    $chk->execute([$uid]);
    $usr = $chk->fetch();

    if ($usr) {
        // CASCADE elimina automáticamente sus mascotas y reservas
        $del = $db->prepare('DELETE FROM usuarios WHERE id = ?');
        $del->execute([$uid]);
        $_SESSION['flash'] = ['tipo' => 'success', 'msg' => "Usuario \"{$usr['nombre']}\" eliminado junto con sus mascotas y reservas."];
    } else {
        $_SESSION['flash'] = ['tipo' => 'error', 'msg' => 'Usuario no encontrado o no se puede eliminar.'];
    }
    header('Location: /admin/usuarios.php');
    exit;
}

// ── LISTAR CLIENTES ─────────────────────────────────────────
$usuarios = $db->query(
    "SELECT u.id, u.nombre, u.email, u.created_at,
            COUNT(DISTINCT r.id)  AS total_reservas,
            COUNT(DISTINCT m.id)  AS total_mascotas,
            SUM(CASE WHEN r.estado = 'pendiente'  THEN 1 ELSE 0 END) AS pend,
            SUM(CASE WHEN r.estado = 'completada' THEN 1 ELSE 0 END) AS comp
     FROM usuarios u
     LEFT JOIN reservas r ON r.usuario_id = u.id
     LEFT JOIN mascotas m ON m.usuario_id = u.id
     WHERE u.rol = 'cliente'
     GROUP BY u.id
     ORDER BY u.created_at DESC"
)->fetchAll();
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.8rem;">Usuarios</h1>
        <p class="text-muted"><?= count($usuarios) ?> cliente(s) registrado(s).</p>
    </div>
</div>

<!-- Aviso delete -->
<div style="background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.22);border-radius:var(--radius-sm);padding:.75rem 1.1rem;margin-bottom:1.5rem;font-size:.88rem;color:#e8a0a0;">
    ⚠️ <strong>Eliminar un usuario</strong> borrará también todas sus mascotas y reservas de forma permanente (CASCADE). Úsalo solo si el cliente ya no debe tener acceso.
</div>

<?php if (empty($usuarios)): ?>
    <div class="empty-state"><div class="icon">👥</div><p>No hay clientes registrados todavía.</p></div>
<?php else: ?>
<div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-md);overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Mascotas</th>
                    <th>Reservas</th>
                    <th>Pendientes</th>
                    <th>Completadas</th>
                    <th>Registrado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td class="text-muted" style="font-size:.82rem;"><?= $u['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($u['nombre']) ?></strong>
                    </td>
                    <td class="text-muted" style="font-size:.88rem;"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span style="background:var(--color-bg3);border:1px solid var(--color-border);border-radius:99px;padding:.2rem .7rem;font-size:.82rem;">
                            🐾 <?= $u['total_mascotas'] ?>
                        </span>
                    </td>
                    <td>
                        <span style="background:var(--color-bg3);border:1px solid var(--color-border);border-radius:99px;padding:.2rem .7rem;font-size:.82rem;">
                            📅 <?= $u['total_reservas'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($u['pend'] > 0): ?>
                        <span class="badge badge-pendiente"><?= $u['pend'] ?></span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:.82rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['comp'] > 0): ?>
                        <span class="badge badge-completada"><?= $u['comp'] ?></span>
                        <?php else: ?>
                        <span class="text-muted" style="font-size:.82rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted" style="font-size:.85rem;">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td>
                        <a href="/admin/usuarios.php?eliminar=<?= $u['id'] ?>"
                           class="btn btn-danger btn-sm admin-confirm"
                           data-msg="¿Eliminar al usuario «<?= htmlspecialchars($u['nombre']) ?>» y todos sus datos (mascotas y reservas)? Esta acción es permanente.">
                            🗑️ Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

</main>
<script src="/assets/js/script.js"></script>
</body>
</html>
