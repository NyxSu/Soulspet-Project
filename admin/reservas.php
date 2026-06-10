<?php
// ============================================================
// SoulsPets - Administrar reservas (CRUD completo + pestañas)
// ============================================================
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Reservas';
include __DIR__ . '/includes_admin_header.php';

$db = getDB();

// ── LIMPIAR TODO EL HISTORIAL ───────────────────────────────
if (isset($_GET['limpiar_historial'])) {
    $del = $db->prepare("DELETE FROM reservas WHERE estado IN ('completada','cancelada')");
    $del->execute();
    $n = $del->rowCount();
    $_SESSION['flash'] = ['tipo' => 'success', 'msg' => "$n reserva(s) eliminada(s) del historial."];
    header('Location: /admin/reservas.php?vista=historial');
    exit;
}

// ── ELIMINAR RESERVA INDIVIDUAL ─────────────────────────────
if (isset($_GET['eliminar'])) {
    $rid = intval($_GET['eliminar']);
    $chk = $db->prepare("SELECT id FROM reservas WHERE id = ? AND estado IN ('cancelada','completada')");
    $chk->execute([$rid]);
    if ($chk->fetch()) {
        $del = $db->prepare('DELETE FROM reservas WHERE id = ?');
        $del->execute([$rid]);
        $_SESSION['flash'] = ['tipo' => 'success', 'msg' => "Reserva #$rid eliminada del historial."];
    } else {
        $_SESSION['flash'] = ['tipo' => 'error', 'msg' => 'Solo se pueden eliminar reservas completadas o canceladas.'];
    }
    header('Location: /admin/reservas.php?vista=historial');
    exit;
}

// ── CAMBIAR ESTADO ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserva_id'], $_POST['nuevo_estado'])) {
    $rid     = intval($_POST['reserva_id']);
    $estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];
    $estado  = $_POST['nuevo_estado'];
    if (in_array($estado, $estados)) {
        $upd = $db->prepare('UPDATE reservas SET estado = ? WHERE id = ?');
        $upd->execute([$estado, $rid]);
        $_SESSION['flash'] = ['tipo' => 'success', 'msg' => "Estado de reserva #$rid actualizado."];
    }
    header('Location: /admin/reservas.php');
    exit;
}

// ── VISTA Y FILTROS ─────────────────────────────────────────
$vista            = $_GET['vista'] ?? 'activas';
$filtro           = $_GET['estado'] ?? '';
$estadosActivos   = ['pendiente', 'confirmada'];
$estadosHistorial = ['completada', 'cancelada'];

$baseSQL = "SELECT r.id, r.fecha, r.hora, r.estado, r.created_at,
                   u.nombre AS cliente, u.email,
                   m.nombre_mascota, m.tipo,
                   s.nombre AS servicio, s.precio
            FROM reservas r
            JOIN usuarios  u ON r.usuario_id  = u.id
            JOIN mascotas  m ON r.mascota_id  = m.id
            JOIN servicios s ON r.servicio_id = s.id";

if ($vista === 'historial') {
    $inList = "'" . implode("','", $estadosHistorial) . "'";
    $sql = $baseSQL . " WHERE r.estado IN ($inList)";
    if ($filtro && in_array($filtro, $estadosHistorial)) {
        $sql = $baseSQL . " WHERE r.estado = " . $db->quote($filtro);
    }
} else {
    $inList = "'" . implode("','", $estadosActivos) . "'";
    $sql = $baseSQL . " WHERE r.estado IN ($inList)";
    if ($filtro && in_array($filtro, $estadosActivos)) {
        $sql = $baseSQL . " WHERE r.estado = " . $db->quote($filtro);
    }
}
$sql .= " ORDER BY r.fecha DESC, r.hora DESC";
$reservas = $db->query($sql)->fetchAll();

$cntActivas   = $db->query("SELECT COUNT(*) FROM reservas WHERE estado IN ('pendiente','confirmada')")->fetchColumn();
$cntHistorial = $db->query("SELECT COUNT(*) FROM reservas WHERE estado IN ('completada','cancelada')")->fetchColumn();
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.8rem;">Reservas</h1>
        <p class="text-muted"><?= count($reservas) ?> reserva(s) en esta vista.</p>
    </div>
</div>

<!-- ── PESTAÑAS ─────────────────────────────────────────── -->
<div style="display:flex;gap:.5rem;margin-bottom:1.25rem;border-bottom:1px solid var(--color-border);padding-bottom:.75rem;flex-wrap:wrap;">
    <a href="/admin/reservas.php?vista=activas"
       style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1.1rem;border-radius:var(--radius-sm);
              font-size:.88rem;font-weight:600;text-decoration:none;
              background:<?= $vista==='activas' ? 'var(--color-gold)' : 'var(--color-bg3)' ?>;
              color:<?= $vista==='activas' ? '#0f0f0f' : 'var(--color-muted)' ?>;
              border:1px solid <?= $vista==='activas' ? 'var(--color-gold)' : 'var(--color-border)' ?>;">
        📋 Activas
        <span style="background:rgba(0,0,0,.18);border-radius:99px;padding:.05rem .5rem;font-size:.78rem;">
            <?= $cntActivas ?>
        </span>
    </a>
    <a href="/admin/reservas.php?vista=historial"
       style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem 1.1rem;border-radius:var(--radius-sm);
              font-size:.88rem;font-weight:600;text-decoration:none;
              background:<?= $vista==='historial' ? 'var(--color-gold)' : 'var(--color-bg3)' ?>;
              color:<?= $vista==='historial' ? '#0f0f0f' : 'var(--color-muted)' ?>;
              border:1px solid <?= $vista==='historial' ? 'var(--color-gold)' : 'var(--color-border)' ?>;">
        🗂️ Historial
        <span style="background:rgba(0,0,0,.18);border-radius:99px;padding:.05rem .5rem;font-size:.78rem;">
            <?= $cntHistorial ?>
        </span>
    </a>
</div>

<!-- Banner informativo historial -->
<?php if ($vista === 'historial'): ?>
<div style="background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.25);border-radius:var(--radius-sm);
            padding:.75rem 1.1rem;margin-bottom:1.25rem;font-size:.88rem;color:#e74c3c;">
    🗑️ <strong>Historial:</strong> Reservas completadas y canceladas. Puedes eliminarlas permanentemente para mantener la BD limpia. Esta acción no se puede deshacer.
</div>
<?php endif; ?>

<!-- ── FILTROS ───────────────────────────────────────────── -->
<div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <?php if ($vista === 'activas'): ?>
        <a href="/admin/reservas.php?vista=activas"  class="btn btn-sm <?= !$filtro ? 'btn-primary' : 'btn-outline' ?>">Todas</a>
        <?php foreach ($estadosActivos as $e): ?>
        <a href="?vista=activas&estado=<?= $e ?>"    class="btn btn-sm <?= $filtro===$e ? 'btn-primary' : 'btn-outline' ?>"><?= ucfirst($e) ?></a>
        <?php endforeach; ?>
    <?php else: ?>
        <a href="/admin/reservas.php?vista=historial" class="btn btn-sm <?= !$filtro ? 'btn-primary' : 'btn-outline' ?>">Todas</a>
        <?php foreach ($estadosHistorial as $e): ?>
        <a href="?vista=historial&estado=<?= $e ?>"   class="btn btn-sm <?= $filtro===$e ? 'btn-primary' : 'btn-outline' ?>"><?= ucfirst($e) ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ── TABLA ─────────────────────────────────────────────── -->
<?php if (empty($reservas)): ?>
    <div class="empty-state">
        <div class="icon"><?= $vista==='historial' ? '🗂️' : '📋' ?></div>
        <p>No hay reservas <?= $vista==='historial' ? 'en el historial' : 'activas' ?>
           <?= $filtro ? 'con estado "'.htmlspecialchars($filtro).'"' : '' ?>.</p>
    </div>
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
                    <th><?= $vista==='activas' ? 'Cambiar estado' : 'Acción' ?></th>
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
                        <?php if ($vista === 'activas'): ?>
                        <form method="POST" style="display:flex;gap:.4rem;align-items:center;flex-wrap:wrap;">
                            <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                            <select name="nuevo_estado"
                                    style="background:var(--color-bg3);border:1px solid var(--color-border);
                                           color:var(--color-text);border-radius:var(--radius-sm);
                                           padding:.35rem .5rem;font-size:.82rem;font-family:var(--font-body);">
                                <?php foreach (['pendiente','confirmada','completada','cancelada'] as $e): ?>
                                    <option value="<?= $e ?>" <?= $r['estado']===$e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm admin-confirm"
                                    data-msg="¿Cambiar el estado de la reserva #<?= $r['id'] ?>?">
                                Guardar
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="/admin/reservas.php?eliminar=<?= $r['id'] ?>&vista=historial"
                           class="btn btn-danger btn-sm admin-confirm"
                           data-msg="¿Eliminar permanentemente la reserva #<?= $r['id'] ?>? Esta acción no se puede deshacer.">
                            🗑️ Eliminar
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Limpiar historial completo -->
    <?php if ($vista === 'historial' && count($reservas) > 0): ?>
    <div style="padding:1rem 1.5rem;border-top:1px solid var(--color-border);display:flex;justify-content:flex-end;">
        <a href="/admin/reservas.php?limpiar_historial=1"
           class="btn btn-danger btn-sm admin-confirm"
           data-msg="¿Eliminar TODAS las reservas del historial (completadas y canceladas)? Esta acción no se puede deshacer.">
            🗑️ Limpiar todo el historial (<?= count($reservas) ?>)
        </a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

</main>
<script src="/assets/js/script.js"></script>
</body>
</html>
