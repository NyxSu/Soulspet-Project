<?php
// ============================================================
// SoulsPets - Dashboard administrador
// ============================================================
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Dashboard';
include __DIR__ . '/includes_admin_header.php';

$db = getDB();

// Contadores para las estadísticas
$totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'cliente'")->fetchColumn();
$reservasHoy   = $db->query("SELECT COUNT(*) FROM reservas WHERE fecha = CURDATE()")->fetchColumn();
$reservasTotal = $db->query("SELECT COUNT(*) FROM reservas")->fetchColumn();
$reservasPend  = $db->query("SELECT COUNT(*) FROM reservas WHERE estado = 'pendiente'")->fetchColumn();

// Últimas 8 reservas
$ultimas = $db->query(
    "SELECT r.id, r.fecha, r.hora, r.estado,
            u.nombre AS cliente,
            m.nombre_mascota, m.tipo,
            s.nombre AS servicio
     FROM reservas r
     JOIN usuarios  u ON r.usuario_id  = u.id
     JOIN mascotas  m ON r.mascota_id  = m.id
     JOIN servicios s ON r.servicio_id = s.id
     ORDER BY r.created_at DESC LIMIT 8"
)->fetchAll();
?>

<!-- ADMIN HERO BANNER -->
<div class="admin-welcome-banner">
    <div class="admin-banner-content">
        <div class="admin-banner-text">
            <div class="admin-banner-greeting">
                <span class="admin-avatar">🛡️</span>
                <div>
                    <p class="admin-banner-sub">Panel de Administración · SoulsPet</p>
                    <h1 class="admin-banner-title">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> 👋</h1>
                </div>
            </div>
            <p class="admin-banner-desc">Gestiona reservas, clientes y servicios. Aquí está el resumen del sistema.</p>
            <div class="admin-banner-tags">
                <span class="admin-tag">🐶 Perros</span>
                <span class="admin-tag">🐱 Gatos</span>
                <span class="admin-tag">✂️ Grooming</span>
                <span class="admin-tag">🏠 Hospedaje</span>
            </div>
        </div>
        <div class="admin-banner-images">
            <div class="admin-img-wrap admin-img-main">
                <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=280&q=80&auto=format&fit=crop" alt="Perro feliz">
                <div class="admin-img-badge">🐶</div>
            </div>
            <div class="admin-img-wrap admin-img-secondary">
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=180&q=80&auto=format&fit=crop" alt="Gato">
                <div class="admin-img-badge">🐱</div>
            </div>
            <div class="admin-banner-paws">🐾</div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number" data-count="<?= $totalUsuarios ?>"><?= $totalUsuarios ?></div>
        <div class="stat-label">👥 Clientes registrados</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" data-count="<?= $reservasHoy ?>"><?= $reservasHoy ?></div>
        <div class="stat-label">📅 Reservas hoy</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" data-count="<?= $reservasTotal ?>"><?= $reservasTotal ?></div>
        <div class="stat-label">📋 Reservas totales</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" data-count="<?= $reservasPend ?>"><?= $reservasPend ?></div>
        <div class="stat-label">⏳ Pendientes</div>
    </div>
</div>

<!-- Últimas reservas -->
<div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-md);overflow:hidden;">
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;">
        <h3 style="font-size:1rem;">Últimas reservas</h3>
        <a href="/soulspet/admin/reservas.php" class="btn btn-outline btn-sm">Ver todas</a>
    </div>

      <?php if (empty($ultimas)): ?>
        <div class="empty-state"><p>No hay reservas aún.</p></div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Mascota</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimas as $r): ?>
                <tr>
                    <td class="text-muted" style="font-size:.82rem;">#<?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['cliente']) ?></td>
                    <td>
                        <span class="pet-tag">
                            <?= $r['tipo'] === 'perro' ? '🐶' : '🐱' ?>
                            <?= htmlspecialchars($r['nombre_mascota']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($r['servicio']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['fecha'])) ?> <?= date('h:i A', strtotime($r['hora'])) ?></td>
                    <td><span class="badge badge-<?= $r['estado'] ?>"><?= ucfirst($r['estado']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

</main>
<script src="/soulspet/assets/js/script.js"></script>
</body>
</html>


