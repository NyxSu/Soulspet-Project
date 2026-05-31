<?php
// ============================================================
// SoulsPets - Página principal
// ============================================================
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Inicio';

// Obtener servicios para mostrar en home
$db       = getDB();
$servicios = $db->query('SELECT * FROM servicios LIMIT 4')->fetchAll();

$icons = ['🛁','✂️','🪮','💅','🏠'];

include __DIR__ . '/includes/header.php';
?>
