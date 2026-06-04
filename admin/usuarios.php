<?php
// ============================================================
// SoulsPets - Lista de usuarios (admin)
// ============================================================
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Usuarios';
include __DIR__ . '/includes_admin_header.php';

$db = getDB();

// Obtener clientes con conteo de reservas y mascotas
$usuarios = $db->query(
    "SELECT u.id, u.nombre, u.email, u.created_at,
            COUNT(DISTINCT r.id) AS total_reservas,
            COUNT(DISTINCT m.id) AS total_mascotas
     FROM usuarios u
     LEFT JOIN reservas r ON r.usuario_id = u.id
     LEFT JOIN mascotas m ON m.usuario_id = u.id
     WHERE u.rol = 'cliente'
     GROUP BY u.id
     ORDER BY u.created_at DESC"
)->fetchAll();
?>

<div style="margin-bottom:2rem;">
    <h1 style="font-size:1.8rem;">Usuarios</h1>
    <p class="text-muted"><?= count($usuarios) ?> cliente(s) registrado(s).</p>
</div>

<?php if (empty($usuarios)): ?>
    <div class="empty-state"><div class="icon">👥</div><p>No hay usuarios registrados todavía.</p></div>
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
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td class="text-muted" style="font-size:.82rem;"><?= $u['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($u['nombre']) ?></strong>
                    </td>
                    <td class="text-muted"><?= htmlspecialchars($u['email']) ?></td>
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
                    <td class="text-muted" style="font-size:.85rem;">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

</main>
<script src="/soulspet/assets/js/script.js"></script>
</body>
</html>
