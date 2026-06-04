<?php
// ============================================================
// SoulsPets - Hacer una reserva
// ============================================================
session_start();
require_once __DIR__ . '/config/database.php';

// Requiere login de cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'cliente') {
    $_SESSION['flash'] = ['tipo' => 'error', 'msg' => 'Debes iniciar sesión para reservar.'];
    header('Location: /soulspet/login.php');
    exit;
}

$userId  = $_SESSION['usuario_id'];
$db      = getDB();
$error   = '';

// Obtener mascotas del usuario
$mascotas = $db->prepare('SELECT * FROM mascotas WHERE usuario_id = ?');
$mascotas->execute([$userId]);
$mascotas = $mascotas->fetchAll();

// Obtener servicios
$servicios = $db->query('SELECT * FROM servicios ORDER BY nombre')->fetchAll();

$horarios = ['10:00', '12:00', '15:00', '17:00'];
$horariosLabel = ['10:00 AM', '12:00 PM', '3:00 PM', '5:00 PM'];

// Pre-seleccionar servicio si viene de la URL
$preServicio = intval($_GET['servicio'] ?? 0);

// ---- Formulario de nueva mascota (inline) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_mascota'])) {
    $nomMasc = trim($_POST['nombre_mascota'] ?? '');
    $tipo    = $_POST['tipo'] ?? '';
    if ($nomMasc && in_array($tipo, ['perro', 'gato'])) {
        $ins = $db->prepare('INSERT INTO mascotas (usuario_id, nombre_mascota, tipo) VALUES (?, ?, ?)');
        $ins->execute([$userId, $nomMasc, $tipo]);
        $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Mascota "' . $nomMasc . '" agregada.'];
        header('Location: /soulspet/reservar.php');
        exit;
    } else {
        $error = 'Completa el nombre y tipo de mascota.';
    }
}

// ---- Formulario de reserva ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hacer_reserva'])) {
    $mascotaId  = intval($_POST['mascota_id']  ?? 0);
    $servicioId = intval($_POST['servicio_id'] ?? 0);
    $fecha      = $_POST['fecha']  ?? '';
    $hora       = $_POST['hora']   ?? '';

    if (!$mascotaId || !$servicioId || !$fecha || !$hora) {
        $error = 'Completa todos los campos de la reserva.';
    } elseif (!in_array($hora, $horarios)) {
        $error = 'Hora no válida.';
    } elseif (strtotime($fecha) <= strtotime('today')) {
        $error = 'La fecha debe ser a partir de mañana.';
    } else {
        // Verificar que la mascota pertenece al usuario
        $chk = $db->prepare('SELECT id FROM mascotas WHERE id = ? AND usuario_id = ?');
        $chk->execute([$mascotaId, $userId]);
        if (!$chk->fetch()) {
            $error = 'Mascota no válida.';
        } else {
            // Verificar disponibilidad (misma fecha+hora)
            $disp = $db->prepare(
                "SELECT id FROM reservas WHERE fecha = ? AND hora = ? AND estado NOT IN ('cancelada')"
            );
            $disp->execute([$fecha, $hora . ':00']);
            if ($disp->fetch()) {
                $error = 'Ese horario ya está ocupado. Elige otro.';
            } else {
                $ins = $db->prepare(
                    'INSERT INTO reservas (usuario_id, mascota_id, servicio_id, fecha, hora) VALUES (?, ?, ?, ?, ?)'
                );
                $ins->execute([$userId, $mascotaId, $servicioId, $fecha, $hora . ':00']);
                $_SESSION['flash'] = ['tipo' => 'success', 'msg' => '¡Reserva realizada con éxito! Te esperamos.'];
                header('Location: /soulspet/mis_reservas.php');
                exit;
            }
        }
    }
}

$pageTitle = 'Reservar';
include __DIR__ . '/includes/header.php';
?>

<div class="page-hero">
    <span class="section-tag">Agenda fácil</span>
    <h1>Reservar una cita</h1>
    <p>Elige tu mascota, el servicio y el horario que prefieras.</p>
</div>

<div class="content-section">
    <?php if ($error): ?>
        <div class="flash flash-error" style="margin-bottom:1.5rem;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;align-items:start;">

        <!-- Formulario reserva -->
        <div class="form-card" style="max-width:100%;">
            <h3 style="margin-bottom:1.5rem;">Nueva reserva</h3>
            <form method="POST" data-validate>
                <input type="hidden" name="hacer_reserva" value="1">

                <div class="form-group">
                    <label for="mascota_id">Tu mascota</label>
                    <?php if (empty($mascotas)): ?>
                        <p class="text-muted" style="font-size:.88rem;padding:.75rem 1rem;background:var(--color-bg3);border-radius:var(--radius-sm);">
                            Aún no tienes mascotas. Agrega una primero →
                        </p>
                    <?php else: ?>
                        <select id="mascota_id" name="mascota_id" required>
                            <option value="">Selecciona una mascota</option>
                            <?php foreach ($mascotas as $m): ?>
                                <option value="<?= $m['id'] ?>">
                                    <?= htmlspecialchars($m['nombre_mascota']) ?> (<?= $m['tipo'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="servicio_id">Servicio</label>
                    <select id="servicio_id" name="servicio_id" required>
                        <option value="">Selecciona un servicio</option>
                        <?php foreach ($servicios as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $preServicio === $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre']) ?> – S/ <?= number_format($s['precio'], 2) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha"
                               value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="hora">Horario</label>
                        <select id="hora" name="hora" required>
                            <option value="">Elige horario</option>
                            <?php foreach ($horarios as $i => $h): ?>
                                <option value="<?= $h ?>" <?= ($_POST['hora'] ?? '') === $h ? 'selected' : '' ?>>
                                    <?= $horariosLabel[$i] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full"
                        <?= empty($mascotas) ? 'disabled' : '' ?>>
                    Confirmar Reserva
                </button>
            </form>
        </div>

        <!-- Agregar mascota -->
        <div class="form-card" style="max-width:100%;">
            <h3 style="margin-bottom:.5rem;">Mis mascotas</h3>
            <p class="text-muted" style="font-size:.88rem;margin-bottom:1.5rem;">Agrega las mascotas que quieras cuidar.</p>

            <?php if (!empty($mascotas)): ?>
                <div style="margin-bottom:1.5rem;">
                    <?php foreach ($mascotas as $m): ?>
                        <div style="display:flex;align-items:center;gap:.75rem;padding:.65rem 0;border-bottom:1px solid var(--color-border);">
                            <span style="font-size:1.5rem;"><?= $m['tipo'] === 'perro' ? '🐶' : '🐱' ?></span>
                            <div>
                                <strong><?= htmlspecialchars($m['nombre_mascota']) ?></strong>
                                <br><span class="text-muted" style="font-size:.8rem;text-transform:capitalize;"><?= $m['tipo'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" data-validate>
                <input type="hidden" name="agregar_mascota" value="1">
                <div class="form-group">
                    <label for="nombre_mascota">Nombre de la mascota</label>
                    <input type="text" id="nombre_mascota" name="nombre_mascota"
                           placeholder="Ej: Max, Luna..." required>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecciona</option>
                        <option value="perro">🐶 Perro</option>
                        <option value="gato">🐱 Gato</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline btn-full">+ Agregar mascota</button>
            </form>
        </div>

    </div><!-- /grid -->
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<style>
@media (max-width: 680px) {
    .content-section > div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
