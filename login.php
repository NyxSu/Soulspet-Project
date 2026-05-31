<?php
// ============================================================
// SoulsPets - Login cliente
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: /soulspet/');
    exit;
}