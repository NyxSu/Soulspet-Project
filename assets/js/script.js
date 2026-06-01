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
    
    // ----------------------------------------------------------
    // Flash: auto-ocultar después de 4 segundos
    // ----------------------------------------------------------
    const flash = document.querySelector('.flash');
    if (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity .5s ease';
            flash.style.opacity = '0';
            setTimeout(function () { flash.remove(); }, 500);
        }, 4000);
    }

    // ----------------------------------------------------------
    // Confirmar cancelación de reserva
    // ----------------------------------------------------------
    document.querySelectorAll('.btn-cancelar').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('¿Estás seguro de que quieres cancelar esta reserva?')) {
                e.preventDefault();
            }
        });
    });

    // ----------------------------------------------------------
    // Confirmar acciones del admin
    // ----------------------------------------------------------
    document.querySelectorAll('.admin-confirm').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const msg = this.dataset.msg || '¿Confirmar esta acción?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // ----------------------------------------------------------
    // Mostrar/ocultar contraseña
    // ----------------------------------------------------------
    document.querySelectorAll('.toggle-pass').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.querySelector(this.dataset.target);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.textContent = input.type === 'password' ? '👁️' : '🙈';
        });
    });

    // ----------------------------------------------------------
    // Validación fecha mínima en formulario de reserva
    // ----------------------------------------------------------
    const fechaInput = document.getElementById('fecha');
    if (fechaInput) {
        const hoy = new Date();
        // Mínimo: mañana
        hoy.setDate(hoy.getDate() + 1);
        const yyyy = hoy.getFullYear();
        const mm   = String(hoy.getMonth() + 1).padStart(2, '0');
        const dd   = String(hoy.getDate()).padStart(2, '0');
        fechaInput.min = `${yyyy}-${mm}-${dd}`;
    }

