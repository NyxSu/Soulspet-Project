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

<!-- HERO con imagen de fondo -->
<section class="hero hero-visual">
    <div class="hero-bg-overlay"></div>
    <div class="hero-inner hero-split">
        <div class="hero-content">
            <span class="hero-tag">🐾 Centro de cuidado profesional</span>
            <h1>El bienestar de tu<br>mascota, nuestra<br><em>pasión</em></h1>
            <p>Cuidamos a perros y gatos con amor y profesionalismo. Reserva una cita hoy y deja que tu compañero brille.</p>
            <div class="hero-ctas">
                <a href="/soulspet/reservar.php" class="btn btn-primary">Reservar cita</a>
                <a href="/soulspet/servicios.php" class="btn btn-outline">Ver servicios</a>
            </div>
            <div class="hero-trust">
                <div class="trust-item">
                    <span class="trust-num">500+</span>
                    <span class="trust-label">Mascotas felices</span>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <span class="trust-num">5★</span>
                    <span class="trust-label">Valoración media</span>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <span class="trust-num">3 años</span>
                    <span class="trust-label">De experiencia</span>
                </div>
            </div>
        </div>
        <div class="hero-gallery">
            <div class="gallery-main">
                <img src="https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=600&q=80&auto=format&fit=crop" alt="Perro feliz" loading="eager">
                <div class="gallery-badge">🐶 ¡Hola!</div>
            </div>
            <div class="gallery-side">
                <div class="gallery-thumb">
                    <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=300&q=80&auto=format&fit=crop" alt="Gato elegante" loading="eager">
                    <span class="gallery-thumb-label">🐱 Gatos</span>
                </div>
                <div class="gallery-thumb">
                    <img src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=300&q=80&auto=format&fit=crop" alt="Perro en spa" loading="eager">
                    <span class="gallery-thumb-label">✂️ Grooming</span>
                </div>
                <div class="gallery-thumb gallery-thumb-wide">
                    <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?auto=format&fit=crop&w=1200&q=80" alt="Mascotas juntas" loading="eager">
                    <span class="gallery-thumb-label">🐾 Amigos</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CARACTERÍSTICAS -->
<section class="section">
    <div class="section-header">
        <span class="section-tag">¿Por qué nosotros?</span>
        <h2>Cuidado con corazón</h2>
        <p>Más que un spa, somos un hogar temporal para tu mascota.</p>
    </div>
    <div class="features-grid">
        <div class="feature-item">
            <div class="icon">🏆</div>
            <h3>Profesionales certificados</h3>
            <p>Nuestro equipo tiene años de experiencia cuidando perros y gatos de todas las razas.</p>
        </div>
        <div class="feature-item">
            <div class="icon">❤️</div>
            <h3>Trato con amor</h3>
            <p>Cada mascota recibe atención personalizada y cariño en cada sesión.</p>
        </div>
        <div class="feature-item">
            <div class="icon">📅</div>
            <h3>Reservas fáciles</h3>
            <p>Agenda tu cita en línea en menos de un minuto, sin llamadas ni esperas.</p>
        </div>
    </div>
</section>