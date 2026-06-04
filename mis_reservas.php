<?php
// ============================================================
// SoulsPets - Mis reservas (cliente)
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: /soulspet/login.php');
    exit;
}

$userId = $_SESSION['usuario_id'];
$db     = getDB();

// Acción: cancelar reserva
if (isset($_GET['cancelar'])) {
    $rid  = intval($_GET['cancelar']);
    $stmt = $db->prepare(
        "UPDATE reservas SET estado = 'cancelada' WHERE id = ? AND usuario_id = ? AND estado = 'pendiente'"
    );
    $stmt->execute([$rid, $userId]);
    $_SESSION['flash'] = ['tipo' => 'info', 'msg' => 'Reserva cancelada.'];
    header('Location: /soulspet/mis_reservas.php');
    exit;
}

// Obtener reservas con detalles
$stmt = $db->prepare(
    "SELECT r.id, r.fecha, r.hora, r.estado,
            m.nombre_mascota, m.tipo,
            s.nombre AS servicio, s.precio
     FROM reservas r
     JOIN mascotas  m ON r.mascota_id  = m.id
     JOIN servicios s ON r.servicio_id = s.id
     WHERE r.usuario_id = ?
     ORDER BY r.fecha DESC, r.hora DESC"
);
$stmt->execute([$userId]);
$reservas = $stmt->fetchAll();

$pageTitle = 'Mis Reservas';
include __DIR__ . '/includes/header.php';
?>

<div class="page-hero">
    <span class="section-tag">Historial</span>
    <h1>Mis Reservas</h1>
    <p>Consulta y gestiona todas tus citas en SoulsPets.</p>
</div>

<div class="content-section">
    <?php if (empty($reservas)): ?>
        <div class="empty-state">
            <div class="icon">📅</div>
            <h3>Sin reservas todavía</h3>
            <p>¡Agenda tu primera cita y dale el mejor cuidado a tu mascota!</p>
            <a href="/soulspet/reservar.php" class="btn btn-primary">Reservar ahora</a>
        </div>
    <?php else: ?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
            <p class="text-muted"><?= count($reservas) ?> reserva(s) en total.</p>
            <a href="/soulspet/reservar.php" class="btn btn-primary btn-sm">+ Nueva reserva</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Mascota</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td class="text-muted" style="font-size:.82rem;">#<?= $r['id'] ?></td>
                        <td>
                            <span class="pet-tag">
                                <?= $r['tipo'] === 'perro' ? '🐶' : '🐱' ?>
                                <?= htmlspecialchars($r['nombre_mascota']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($r['servicio']) ?></td>
                        <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                        <td><?= date('h:i A', strtotime($r['hora'])) ?></td>
                        <td class="text-gold" style="font-weight:600;">S/ <?= number_format($r['precio'], 2) ?></td>
                        <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                        <td>
                            <?php if ($r['estado'] === 'pendiente'): ?>
                                <a href="/soulspet/mis_reservas.php?cancelar=<?= $r['id'] ?>"
                                   class="btn btn-danger btn-sm btn-cancelar">
                                   Cancelar
                                </a>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:.82rem;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
