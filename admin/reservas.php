<?php
// ============================================================
// SoulsPets - Administrar reservas
// ============================================================
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Reservas';
include __DIR__ . '/includes_admin_header.php';

$db = getDB();

// Cambiar estado de una reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'], $_POST['nuevo_estado'])) {
    $rid    = intval($_POST['reserva_id']);
    $estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];
    $estado = $_POST['nuevo_estado'];

    if (in_array($estado, $estados)) {
        $upd = $db->prepare('UPDATE reservas SET estado = ? WHERE id = ?');
        $upd->execute([$estado, $rid]);
        $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Estado de reserva #' . $rid . ' actualizado.'];
    }
    header('Location: /soulspet/admin/reservas.php');
    exit;
}

// Filtro por estado
$filtro = $_GET['estado'] ?? '';
$estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];

$sql = "SELECT r.id, r.fecha, r.hora, r.estado, r.created_at,
               u.nombre AS cliente, u.email,
               m.nombre_mascota, m.tipo,
               s.nombre AS servicio, s.precio
        FROM reservas r
        JOIN usuarios  u ON r.usuario_id  = u.id
        JOIN mascotas  m ON r.mascota_id  = m.id
        JOIN servicios s ON r.servicio_id = s.id";

if ($filtro && in_array($filtro, $estados)) {
    $sql .= " WHERE r.estado = " . $db->quote($filtro);
}
$sql .= " ORDER BY r.fecha DESC, r.hora DESC";

$reservas = $db->query($sql)->fetchAll();
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.8rem;">Reservas</h1>
        <p class="text-muted"><?= count($reservas) ?> reserva(s) <?= $filtro ? 'con estado "' . htmlspecialchars($filtro) . '"' : 'en total' ?>.</p>
    </div>
</div>

<!-- Filtros -->
<div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <a href="/soulspet/admin/reservas.php" class="btn btn-sm <?= !$filtro ? 'btn-primary' : 'btn-outline' ?>">Todas</a>
    <?php foreach ($estados as $e): ?>
    <a href="?estado=<?= $e ?>" class="btn btn-sm <?= $filtro === $e ? 'btn-primary' : 'btn-outline' ?>">
        <?= ucfirst($e) ?>
    </a>
    <?php endforeach; ?>
</div>

<?php if (empty($reservas)): ?>
    <div class="empty-state"><div class="icon">📋</div><p>No hay reservas con ese filtro.</p></div>
<?php else: ?>
<div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-md);overflow:hidden;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Mascota</th>
                    <th>Servicio</th>
                    <th>Precio</th>
                    <th>Fecha / Hora</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $r): ?>
                <tr>
                    <td class="text-muted" style="font-size:.82rem;">#<?= $r['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['cliente']) ?></strong><br>
                        <span class="text-muted" style="font-size:.78rem;"><?= htmlspecialchars($r['email']) ?></span>
                    </td>
                    <td>
                        <span class="pet-tag">
                            <?= $r['tipo'] === 'perro' ? '🐶' : '🐱' ?>
                            <?= htmlspecialchars($r['nombre_mascota']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($r['servicio']) ?></td>
                    <td class="text-gold" style="font-weight:600;">S/ <?= number_format($r['precio'], 2) ?></td>
                    <td>
                        <?= date('d/m/Y', strtotime($r['fecha'])) ?><br>
                        <span class="text-muted" style="font-size:.82rem;"><?= date('h:i A', strtotime($r['hora'])) ?></span>
                    </td>
                    <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                    <td>
                        <!-- Formulario inline para cambiar estado -->
                        <form method="POST" style="display:flex;gap:.4rem;align-items:center;flex-wrap:wrap;">
                            <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                            <select name="nuevo_estado" style="background:var(--color-bg3);border:1px solid var(--color-border);color:var(--color-text);border-radius:var(--radius-sm);padding:.35rem .5rem;font-size:.82rem;font-family:var(--font-body);">
                                <?php foreach ($estados as $e): ?>
                                    <option value="<?= $e ?>" <?= $r['estado'] === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm admin-confirm"
                                    data-msg="¿Cambiar el estado de la reserva #<?= $r['id'] ?>?">
                                Guardar
                            </button>
                        </form>
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
