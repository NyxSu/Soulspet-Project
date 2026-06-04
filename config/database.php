<?php
// ============================================================
// SoulsPets - Configuración de base de datos
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // Usuario de XAMPP por defecto
define('DB_PASS', '');           // Contraseña vacía en XAMPP por defecto
define('DB_NAME', 'soulspet');

/**
 * Retorna una conexión PDO a la base de datos.
 * Lanza excepción si falla la conexión.
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:2rem;color:#c0392b;">
                <h2>Error de conexión a la base de datos</h2>
                <p>Verifica que XAMPP esté corriendo y la BD "soulspet" exista.</p>
                <small>' . htmlspecialchars($e->getMessage()) . '</small>
                </div>');
        }
    }
    return $pdo;
}
