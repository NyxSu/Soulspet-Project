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