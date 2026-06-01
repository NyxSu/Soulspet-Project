// ============================================================
// SoulsPets - JavaScript principal
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // Menú hamburguesa (móvil)
    // ----------------------------------------------------------
    const toggle = document.getElementById('navToggle');
    const nav    = document.getElementById('mainNav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
            this.setAttribute('aria-expanded', nav.classList.contains('open'));
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', function (e) {
            if (!toggle.contains(e.target) && !nav.contains(e.target)) {
                nav.classList.remove('open');
            }
        });
    }
