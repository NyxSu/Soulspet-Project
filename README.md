<!-- ============================================================
     SoulsPets — Official Project Roadmap
     Última actualización: 2025
     ============================================================ -->

<div align="center">

# 🐾 SoulsPets

### Centro de Cuidado Profesional para Mascotas

**Plataforma web de gestión de reservas para perros y gatos**

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?style=flat-square&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

---

[Descripción](#-descripción-general) · [Objetivos](#-objetivos-del-sistema) · [Módulos](#-arquitectura-de-módulos) · [Roadmap](#-roadmap-de-desarrollo) · [Flujo Git](#-flujo-de-trabajo-en-github) · [Pull Requests](#-gestión-de-pull-requests)

</div>

---

## 📋 Descripción General

SoulsPets es una **plataforma web full-stack** diseñada para digitalizar y optimizar la gestión de citas en un centro de cuidado de mascotas especializado en perros y gatos. El sistema permite a los clientes registrarse, agregar sus mascotas, consultar servicios disponibles y agendar citas en horarios fijos. Los administradores cuentan con un panel de control privado para gestionar reservas, usuarios y el estado de la operación en tiempo real.

> **Stack tecnológico:** PHP · MySQL · HTML · CSS · JavaScript puro · XAMPP (dev) · cPanel (producción)  
> **Sin frameworks externos** — código limpio, portable y fácil de mantener.

---

## 🎯 Objetivos del Sistema

| # | Objetivo | Módulo relacionado |
|---|----------|--------------------|
| 1 | Digitalizar el proceso de reservas eliminando la gestión telefónica | Reservas |
| 2 | Proveer una interfaz responsive, intuitiva y atractiva | Frontend |
| 3 | Garantizar la seguridad de datos con bcrypt y prepared statements | Auth / BD |
| 4 | Ofrecer al administrador visibilidad total de la operación | Panel Admin |
| 5 | Publicar el sistema en producción con HTTPS y dominio propio | Deploy |

---

## 🧩 Arquitectura de Módulos

```
soulspet/
│
├── 🎨 FRONTEND
│   ├── assets/css/style.css        → Sistema de estilos (dark mode, responsive)
│   ├── assets/js/script.js         → Interactividad del cliente
│   ├── includes/header.php         → Header compartido
│   └── includes/footer.php         → Footer compartido
│
├── 🔐 AUTENTICACIÓN
│   ├── login.php                   → Login de clientes
│   ├── registro.php                → Registro de nuevos clientes
│   └── logout.php                  → Cierre de sesión
│
├── 📅 RESERVAS
│   ├── reservar.php                → Crear reserva + gestión de mascotas
│   ├── mis_reservas.php            → Ver historial y cancelar
│   └── servicios.php              → Catálogo de servicios
│
├── 👤 PERFIL
│   └── perfil.php                  → Editar nombre y contraseña
│
├── 🛠️ PANEL ADMIN
│   ├── admin/index.php             → Dashboard con estadísticas
│   ├── admin/reservas.php          → Gestión de reservas
│   ├── admin/usuarios.php          → Lista de clientes
│   └── admin/login.php             → Acceso exclusivo admin
│
├── 🗄️ BASE DE DATOS
│   ├── config/database.php         → Conexión PDO
│   └── database/soulspet.sql       → Esquema completo + datos iniciales
│
└── 🚀 DEPLOY
    └── setup.php                   → Configuración inicial en producción
```

---

## 🗺️ Roadmap de Desarrollo

> **Convención de ramas:** `main` → rama protegida · `develop` → integración continua · `feature/*` → desarrollo de funcionalidades  
> **Política de merge:** Todo código entra a `develop` vía Pull Request con al menos **1 revisión aprobada** antes de mergear a `main`.

---

### Fase 0 — Configuración del Repositorio

> Preparación del entorno compartido, estructura de ramas y estándares del equipo.

| Tarea | Responsable | Rama | Estado |
|-------|------------|------|--------|
| Crear repositorio en GitHub y configurar rama `main` como protegida | @usuario1 | — | - [x] |
| Crear rama `develop` y establecer reglas de branch protection | @usuario1 | — | - [x] |
| Configurar `.gitignore` (excluir `config/database.php`, `setup.php`, `.env`) | @usuario2 | `setup/gitignore` | - [x] |
| Redactar `README.md` inicial con descripción, instalación y estructura | @usuario3 | `setup/readme` | - [x] |
| Definir convenciones de commits (`feat:`, `fix:`, `docs:`, `style:`, `refactor:`) | @usuario2 | — | - [x] |
| Crear `CONTRIBUTING.md` con guía del flujo de trabajo del equipo | @usuario3 | `setup/contributing` | - [x] |
| Configurar plantillas de Issues y Pull Requests en `.github/` | @usuario1 | `setup/github-templates` | - [x] |

---

### Fase 1 — Base del Sistema & Diseño Visual

> Construcción de la estructura de carpetas, sistema de estilos y componentes visuales compartidos.

#### 1.1 Infraestructura base

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| Crear estructura de carpetas del proyecto | @usuario1 | `feature/project-structure` | Fase 0 | - [x] |
| Configurar `config/database.php` con conexión PDO y manejo de errores | @usuario1 | `feature/database-config` | `feature/project-structure` | - [x] |
| Escribir script SQL completo: tablas, FK, constraints, datos iniciales | @usuario2 | `feature/database-schema` | `feature/project-structure` | - [x] |
| Crear `setup.php` para configuración inicial en producción | @usuario3 | `feature/setup-script` | `feature/database-schema` | - [x] |

#### 1.2 Sistema de estilos

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| Variables CSS: paleta de colores, tipografía, radios, sombras, transiciones | @usuario2 | `feature/css-design-system` | `feature/project-structure` | - [x] |
| Reset CSS y estilos base del documento | @usuario2 | `feature/css-design-system` | — | - [x] |
| Estilos de componentes: botones, formularios, inputs, selects | @usuario3 | `feature/css-components` | `feature/css-design-system` | - [x] |
| Estilos de tablas, badges de estado y flash messages | @usuario3 | `feature/css-components` | — | - [x] |
| Media queries para responsive (320px → 1920px) | @usuario1 | `feature/css-responsive` | `feature/css-components` | - [x] |

#### 1.3 Componentes compartidos

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `includes/header.php`: logo, navegación, menú hamburguesa, flash messages | @usuario1 | `feature/header-component` | `feature/css-design-system` | - [x] |
| `includes/footer.php`: columnas de contacto, links y copyright | @usuario2 | `feature/footer-component` | `feature/css-design-system` | - [x] |
| `assets/js/script.js`: menú hamburguesa, flash auto-hide, confirmaciones, fecha mínima | @usuario3 | `feature/javascript-core` | `feature/header-component` | - [x] |

---

### Fase 2 — Páginas Públicas & Autenticación

> Desarrollo de todas las páginas visibles sin login y el sistema completo de autenticación.

#### 2.1 Páginas públicas

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `index.php`: hero split con galería, trust bar (500+ mascotas, 5★, 3 años), sección features | @usuario2 | `feature/homepage` | Fase 1 completa | - [x] |
| `servicios.php`: catálogo de servicios con precios, descripción y tarjetas de horarios | @usuario1 | `feature/services-page` | Fase 1 completa | - [x] |
| Animaciones CSS: zoom en galería, hover en cards, efectos de aparición | @usuario3 | `feature/ui-animations` | `feature/homepage` | - [x] |

#### 2.2 Autenticación

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `registro.php`: formulario + validación server-side + hash bcrypt + auto-login | @usuario3 | `feature/auth-register` | `feature/database-config` | - [x] |
| `login.php`: verificación con `password_verify()` + sesiones + redirección por rol | @usuario1 | `feature/auth-login` | `feature/auth-register` | - [x] |
| `logout.php`: destrucción completa de sesión PHP | @usuario2 | `feature/auth-logout` | `feature/auth-login` | - [x] |
| Protección de rutas: verificación de sesión y rol al inicio de cada página protegida | @usuario1 | `feature/route-protection` | `feature/auth-login` | - [x] |
| `admin/login.php`: login separado exclusivo para administradores | @usuario2 | `feature/admin-auth` | `feature/route-protection` | - [x] |

---

### Fase 3 — Módulo de Reservas & Perfil de Cliente

> Desarrollo del núcleo del negocio: gestión de mascotas, reservas y perfil del usuario.

#### 3.1 Mascotas

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| Formulario inline de registro de mascotas (nombre + tipo: perro/gato) | @usuario2 | `feature/pets-management` | `feature/auth-login` | - [x] |
| Listado de mascotas del usuario logueado con íconos por tipo | @usuario2 | `feature/pets-management` | — | - [x] |
| Validación: la mascota debe pertenecer al usuario antes de usarla en una reserva | @usuario1 | `feature/pets-validation` | `feature/pets-management` | - [x] |

#### 3.2 Reservas

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `reservar.php`: selector de mascota, servicio (con precio visible), fecha y horario fijo | @usuario3 | `feature/booking-form` | `feature/pets-management` | - [x] |
| Validación server-side: fecha mínima (mañana), horario válido, disponibilidad del slot | @usuario1 | `feature/booking-validation` | `feature/booking-form` | - [x] |
| Inserción de reserva en BD con estado inicial `pendiente` | @usuario3 | `feature/booking-insert` | `feature/booking-validation` | - [x] |
| `mis_reservas.php`: listado por usuario ordenado por fecha, con badges de estado | @usuario2 | `feature/my-bookings` | `feature/booking-insert` | - [x] |
| Acción cancelar: solo reservas `pendiente`, con confirmación JS antes de ejecutar | @usuario1 | `feature/booking-cancel` | `feature/my-bookings` | - [x] |

#### 3.3 Perfil de cliente

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `perfil.php`: editar nombre del cliente sin requerir nuevo login | @usuario3 | `feature/profile-edit` | `feature/auth-login` | - [x] |
| Cambio de contraseña: verificar contraseña actual con `password_verify()` antes de actualizar | @usuario2 | `feature/profile-password` | `feature/profile-edit` | - [x] |

---

### Fase 4 — Panel de Administración

> Desarrollo completo del panel privado del administrador con dashboard, gestión de reservas y usuarios.

#### 4.1 Dashboard

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `admin/index.php`: banner de bienvenida personalizado con nombre del admin | @usuario1 | `feature/admin-dashboard` | `feature/admin-auth` | - [x] |
| Contadores en tiempo real: clientes registrados, reservas hoy, total histórico, pendientes | @usuario2 | `feature/admin-stats` | `feature/admin-dashboard` | - [x] |
| Animación JS de contadores numéricos al cargar la página | @usuario3 | `feature/admin-counters-anim` | `feature/admin-stats` | - [x] |
| Tabla de últimas 8 reservas recientes con datos del cliente, mascota y servicio | @usuario1 | `feature/admin-recent` | `feature/admin-stats` | - [x] |

#### 4.2 Gestión de reservas

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `admin/reservas.php`: listado completo con datos de cliente, mascota, servicio, fecha y precio | @usuario3 | `feature/admin-bookings` | `feature/admin-dashboard` | - [x] |
| Sistema de filtros por estado: pendiente / confirmada / completada / cancelada | @usuario2 | `feature/admin-bookings-filter` | `feature/admin-bookings` | - [x] |
| Formulario inline de cambio de estado con confirmación JS antes de guardar | @usuario1 | `feature/admin-status-change` | `feature/admin-bookings-filter` | - [x] |

#### 4.3 Gestión de usuarios

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| `admin/usuarios.php`: lista de clientes con email, fecha de registro | @usuario2 | `feature/admin-users` | `feature/admin-dashboard` | - [x] |
| Estadísticas por cliente vía JOIN: cantidad de mascotas y total de reservas | @usuario3 | `feature/admin-users-stats` | `feature/admin-users` | - [x] |

---

### Fase 5 — Seguridad & Calidad de Código

> Revisión transversal de seguridad, pruebas funcionales y refactorización.

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| Auditar todas las consultas SQL: migrar a PDO prepared statements al 100% | @usuario1 | `fix/sql-prepared-statements` | Fases 2–4 completas | - [x] |
| Auditar todas las salidas HTML: aplicar `htmlspecialchars()` en cada dato dinámico | @usuario2 | `fix/xss-protection` | Fases 2–4 completas | - [x] |
| Revisar protección de rutas: ninguna página privada accesible sin sesión válida | @usuario3 | `fix/route-audit` | Fases 2–4 completas | - [x] |
| Pruebas funcionales de flujo completo: registro → reserva → panel admin → cancelación | @usuario1 | `test/full-flow` | `fix/route-audit` | - [x] |
| Pruebas de responsividad en dispositivos móviles (375px, 768px, 1280px) | @usuario2 | `test/responsive` | `fix/xss-protection` | - [x] |
| Pruebas de compatibilidad cross-browser: Chrome, Firefox, Edge | @usuario3 | `test/cross-browser` | `fix/xss-protection` | - [x] |

---

### Fase 6 — Despliegue a Producción

> Configuración del entorno de producción, migración de base de datos, publicación y entrega final.

| Tarea | Responsable | Rama | Depende de | Estado |
|-------|------------|------|-----------|--------|
| Contratar plan de hosting con soporte PHP 7.4+ y MySQL 5.7+ (cPanel) | @usuario1 | — | Fase 5 completa | - [ ] |
| Registrar dominio y apuntar nameservers al hosting | @usuario1 | — | — | - [ ] |
| Crear base de datos MySQL en cPanel y usuario con todos los privilegios | @usuario2 | `deploy/database-production` | Hosting activo | - [ ] |
| Actualizar `config/database.php` con credenciales de producción | @usuario2 | `deploy/database-production` | — | - [ ] |
| Importar `database/soulspet.sql` vía phpMyAdmin del hosting | @usuario3 | `deploy/database-production` | — | - [ ] |
| Subir todos los archivos al hosting por FTP / File Manager de cPanel | @usuario1 | `deploy/files-upload` | `deploy/database-production` | - [ ] |
| Ejecutar `setup.php` en producción para regenerar hash del admin y eliminar el archivo | @usuario3 | `deploy/setup-production` | `deploy/files-upload` | - [ ] |
| Activar certificado SSL gratuito (Let's Encrypt) desde cPanel | @usuario2 | `deploy/ssl-config` | `deploy/files-upload` | - [ ] |
| Pruebas completas en producción: todos los flujos de usuario y admin | @usuario1 | `deploy/production-qa` | `deploy/ssl-config` | - [ ] |
| Desactivar `display_errors` en `php.ini` o `.htaccess` para producción | @usuario3 | `deploy/hardening` | `deploy/production-qa` | - [ ] |
| Documentación final: actualizar `README.md` con URL de producción y credenciales demo | @usuario2 | `docs/final` | `deploy/production-qa` | - [ ] |

---

## 📊 Distribución de Responsabilidades

> Resumen del balance de trabajo entre los tres integrantes del equipo.

| Módulo | @usuario1 | @usuario2 | @usuario3 |
|--------|-----------|-----------|-----------|
| Configuración del repositorio | ✅ Líder | ✅ Apoyo | ✅ Apoyo |
| Infraestructura base | ✅ Líder | ✅ BD/SQL | ✅ Setup |
| Sistema de estilos CSS | ✅ Responsive | ✅ Líder | ✅ Componentes |
| Componentes compartidos | ✅ Header | ✅ Footer | ✅ JavaScript |
| Páginas públicas | ✅ Servicios | ✅ Homepage | ✅ Animaciones |
| Autenticación | ✅ Login + rutas | ✅ Logout + admin | ✅ Registro |
| Mascotas | ✅ Validación | ✅ Líder | — |
| Reservas | ✅ Validación + cancelar | ✅ Historial | ✅ Formulario + inserción |
| Perfil de cliente | — | ✅ Contraseña | ✅ Líder |
| Dashboard admin | ✅ Banner + recientes | ✅ Contadores | ✅ Animaciones |
| Gestión de reservas (admin) | ✅ Cambio estado | ✅ Filtros | ✅ Listado |
| Gestión de usuarios (admin) | — | ✅ Listado | ✅ Estadísticas |
| Seguridad y auditoría | ✅ SQL + QA | ✅ XSS + responsive | ✅ Rutas + browsers |
| Despliegue | ✅ Hosting + FTP | ✅ BD + SSL + docs | ✅ SQL + setup + hardening |

---

## 🌿 Flujo de Trabajo en GitHub

### Estructura de ramas

```
main                        ← Código estable, listo para producción
│
└── develop                 ← Integración continua (rama base de todo PR)
    │
    ├── feature/homepage
    ├── feature/auth-login
    ├── feature/booking-form
    ├── feature/admin-dashboard
    ├── fix/sql-prepared-statements
    ├── test/full-flow
    └── deploy/database-production
```

### Convención de nombres de ramas

| Prefijo | Uso | Ejemplo |
|---------|-----|---------|
| `feature/` | Nueva funcionalidad | `feature/booking-cancel` |
| `fix/` | Corrección de bug | `fix/session-not-destroyed` |
| `refactor/` | Mejora de código sin cambio funcional | `refactor/database-helper` |
| `test/` | Pruebas y validaciones | `test/cross-browser` |
| `docs/` | Documentación | `docs/final` |
| `deploy/` | Tareas de despliegue | `deploy/ssl-config` |
| `setup/` | Configuración inicial del repositorio | `setup/gitignore` |

### Convención de commits

```
feat: agregar formulario de reserva con validación de disponibilidad
fix: corregir redirección al cerrar sesión en panel admin
docs: actualizar README con URL de producción
style: ajustar padding en cards de servicios para móvil
refactor: centralizar verificación de sesión en función helper
test: validar flujo completo de reserva en producción
deploy: importar esquema SQL en hosting y verificar integridad
```

### Ciclo de vida de una tarea

```
1. Crear issue en GitHub describiendo la tarea
      ↓
2. Asignar issue al responsable + agregar label y milestone
      ↓
3. Crear rama desde develop:
   git checkout develop
   git pull origin develop
   git checkout -b feature/nombre-funcionalidad
      ↓
4. Desarrollar + commits frecuentes y descriptivos
      ↓
5. Push de la rama al repositorio remoto:
   git push origin feature/nombre-funcionalidad
      ↓
6. Abrir Pull Request hacia develop
      ↓
7. Al menos 1 integrante revisa y aprueba el PR
      ↓
8. Merge a develop + eliminar rama local y remota
      ↓
9. Al finalizar una fase: PR de develop → main
```

---

## 🔀 Gestión de Pull Requests

### Reglas del equipo

- **Todo código entra por PR.** Nadie hace push directo a `develop` ni a `main`.
- **Mínimo 1 aprobación** requerida para mergear a `develop`.
- **Mínimo 2 aprobaciones** requeridas para mergear `develop → main`.
- El **autor del PR no puede aprobar su propio PR**.
- Cada PR debe estar vinculado a un Issue mediante `Closes #número`.
- Usar **Squash and Merge** para mantener el historial limpio en `develop`.
- Usar **Merge Commit** para los merges de `develop → main` (trazabilidad completa).

### Estructura estándar de un Pull Request

```markdown
## ¿Qué hace este PR?
Descripción clara y concisa del cambio implementado.

## Cambios incluidos
- [ ] Funcionalidad X implementada
- [ ] Pruebas realizadas manualmente
- [ ] Sin errores PHP en consola
- [ ] Responsive verificado en móvil

## Issue relacionado
Closes #12

## Capturas de pantalla (si aplica)
<!-- Agregar antes/después si hay cambios visuales -->

## Notas para el revisor
<!-- Indicar qué revisar con especial atención -->
```

### Checklist de revisión de código

Antes de aprobar un PR, el revisor debe verificar:

- [ ] El código no introduce vulnerabilidades (SQL sin prepared statements, salidas sin `htmlspecialchars`)
- [ ] Las rutas protegidas verifican sesión y rol correctamente
- [ ] El código es legible y sigue las convenciones del proyecto
- [ ] La funcionalidad fue probada y funciona según lo esperado
- [ ] No rompe funcionalidades existentes en `develop`
- [ ] El PR está vinculado a su Issue correspondiente

---

## 📦 Entregables por Fase

| Fase | Entregable | Responsable principal | Estado |
|------|-----------|----------------------|--------|
| 0 | Repositorio configurado con ramas, plantillas y estándares | @usuario1 | ✅ Completado |
| 1 | Sistema de estilos, componentes base y esquema de BD | @usuario2 | ✅ Completado |
| 2 | Páginas públicas y módulo de autenticación funcional | @usuario3 | ✅ Completado |
| 3 | Módulo de reservas y perfil de cliente funcional | @usuario1 | ✅ Completado |
| 4 | Panel de administración completo | @usuario2 | ✅ Completado |
| 5 | Sistema auditado, seguro y probado en local | @usuario3 | ✅ Completado |
| 6 | Sistema publicado en producción con HTTPS | @usuario1 | 🔄 En progreso |

---

## 🔐 Seguridad implementada

| Amenaza | Mitigación aplicada |
|---------|---------------------|
| SQL Injection | PDO con prepared statements en el 100% de las consultas |
| XSS | `htmlspecialchars()` en toda salida de datos al HTML |
| Contraseñas expuestas | `password_hash()` con bcrypt + `password_verify()` |
| Acceso no autorizado | Sesiones PHP con verificación de rol en cada ruta protegida |
| Manipulación de datos ajenos | Verificación de `usuario_id` antes de cualquier operación de escritura |
| Errores expuestos en producción | `display_errors = Off` en entorno de producción |

---

## 🚀 Instalación local rápida

```bash
# 1. Clonar el repositorio
git clone https://github.com/usuario/soulspet.git
cd soulspet

# 2. Copiar a htdocs de XAMPP
cp -r . C:/xampp/htdocs/soulspet/

# 3. Iniciar Apache y MySQL en XAMPP

# 4. Crear base de datos e importar esquema
# → Abrir phpMyAdmin → Nueva BD "soulspet" → Importar database/soulspet.sql

# 5. Configurar conexión (editar si tu MySQL tiene contraseña)
# → config/database.php: ajustar DB_USER y DB_PASS

# 6. Ejecutar configuración inicial (UNA SOLA VEZ)
# → http://localhost/soulspet/setup.php
# → Eliminar setup.php después de ejecutarlo

# 7. Acceder al sistema
# → http://localhost/soulspet/
```

**Credenciales de administrador por defecto:**  
📧 `admin@soulspet.com` · 🔑 `Admin123`

---

<div align="center">

---

**SoulsPets** © 2025 — Todos los derechos reservados  
*Porque cada alma merece el mejor cuidado* 🐾

[![@usuario1](https://img.shields.io/badge/-%40usuario1-181717?style=flat-square&logo=github)](https://github.com/usuario1)
[![@usuario2](https://img.shields.io/badge/-%40usuario2-181717?style=flat-square&logo=github)](https://github.com/usuario2)
[![@usuario3](https://img.shields.io/badge/-%40usuario3-181717?style=flat-square&logo=github)](https://github.com/usuario3)

</div>
