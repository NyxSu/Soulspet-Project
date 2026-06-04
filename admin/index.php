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