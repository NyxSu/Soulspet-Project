<?php
// ============================================================
// SoulsPets - Página de servicios
// ============================================================
require_once __DIR__ . '/config/database.php';

$db       = getDB();
$servicios = $db->query('SELECT * FROM servicios ORDER BY precio ASC')->fetchAll();
$icons    = ['🛁', '💅', '🪮', '✂️', '🏠'];

$pageTitle = 'Servicios';
include __DIR__ . '/includes/header.php';
?>

<div class="page-hero">
    <span class="section-tag">Lo que ofrecemos</span>
    <h1>Nuestros Servicios</h1>
    <p>Tratamientos profesionales para que tu mascota siempre luzca y se sienta increíble.</p>
</div>

<div class="content-section">
    <div class="services-grid">
        <?php foreach ($servicios as $i => $s): ?>
        <div class="service-card">
            <div class="service-icon"><?= $icons[$i % count($icons)] ?></div>
            <h3><?= htmlspecialchars($s['nombre']) ?></h3>
            <p><?= htmlspecialchars($s['descripcion']) ?></p>
            <span class="service-price">S/ <?= number_format($s['precio'], 2) ?></span>
            <div style="margin-top:1.25rem;">
                <a href="/soulspet/reservar.php?servicio=<?= $s['id'] ?>" class="btn btn-outline btn-sm">Reservar este servicio</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:var(--radius-md);padding:2rem;margin-top:2.5rem;text-align:center;">
        <h3>Horarios disponibles</h3>
        <p class="text-muted" style="margin:.5rem 0 1.5rem;">Atendemos de lunes a sábado en los siguientes horarios:</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <?php foreach (['10:00 AM', '12:00 PM', '3:00 PM', '5:00 PM'] as $h): ?>
            <span style="background:var(--color-bg3);border:1px solid var(--color-border);border-radius:var(--radius-sm);padding:.5rem 1.25rem;font-weight:600;color:var(--color-gold);">
                <?= $h ?>
            </span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
