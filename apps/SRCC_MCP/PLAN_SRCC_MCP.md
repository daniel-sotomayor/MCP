# PLAN DEL SISTEMA SRCC_MCP
## Sistema de Registro y Control de Citas — Maternidad Concepción Palacios

Ubicación: `C:\xampp\htdocs\MCP\apps\SRCC_MCP`
Fecha del plan: 07/08/2026
Base técnica: reutilizar el framework MVC de SBN_MCP (validado en producción local), con TODO el baseline de seguridad ya aplicado en ese sistema.

---

## 1. Objetivo

Sistema web que gestiona el ciclo de vida de las citas médicas del hospital:
registro de pacientes, agenda de médicos por especialidad, asignación de citas
con control de disponibilidad, confirmación/aprobación, asistencia, cancelación
y reportes gerenciales.

## 2. Módulos y funciones (MVP)

| # | Módulo | Funciones |
|---|--------|-----------|
| M1 | Autenticación | Login con rate limit, cambio de clave, recuperación por correo, logout seguro |
| M2 | Gestión de usuarios y roles | Roles: `administrador`, `recepcion`, `gerencia`, `medico` |
| M3 | Pacientes | Alta/edición/búsqueda (incluido búsqueda por cédula), deduplicación por cédula |
| M4 | Médicos y especialidades | CRUD especialidades y médicos, horario de atención por médico |
| M5 | Citas | Creación, disponibilidad (anti-solapamiento / anti-pasado), aprobación, completar, cancelar con motivo, no-show |
| M6 | Agenda | Vista diaria/semanal por médico, calendario sencillo (sin libs externas) |
| M7 | Notificaciones | Recordatorio de cita (correo/aviso interno), avisos de agenda al recepcionista |
| M8 | Reportes | Citas por médico/día, ocupación, tasas de no asistencia, pacientes nuevos |
| M9 | Auditoría | Bitácora completa de cambios (tabla auditoría + Logger) |

## 3. Reglas de negocio del dominio (críticas)

1. **No crear cita en el pasado**: fecha/hora >= hoy.
2. **No crear cita fuera del horario de atención** del médico (hora_inicio/hora_fin de medicos).
3. **Anti-solapamiento**: bloqueo único `(medico_id, fecha, hora_inicio)` y verificación en la transacción de inserción.
4. **Segregación de funciones**: quien registra la cita NO la aprueba (mismo patrón anti auto-aprobación de SBN_MCP). Roles: `recepcion` registra → `administrador`/`gerencia` aprueba (o `recepcion` aprueba si otra persona la registró).
5. **State→estado**: `pendiente` → `confirmada` → `completada`; `cancelada`/`no asistida` con motivo y responsable obligatorios. No se edita una cita en pasado salvo registro de resultado.
6. **Cancelación protegida**: requiere CSRF, motivo y log de seguridad.
7. **Cédula única por paciente** (dedup con mensaje amigable), validación V/E.

## 4. Arquitectura (replicar estructura SBN_MCP)

```
apps/SRCC_MCP/
├─ app/
│  ├─ core/            Sass.php, Router.php, Controller.php, App.php, Database.php, Session.php, Logger.php, Cache.php
│  ├─ controllers/     AuthController, UserController, PacienteController, MedicoController,
│  │                    EspecialidadController, CitaController, AgendaController, ReporteController, DashboardController, NotificationController
│  ├─ models/          (oqueries encapsuladas igual que SBN_MCP)
│  ├─ helpers/         SecurityHelper (sanitizeRichHtml, csrf, validadores V/E, email), EmailHelper, AuditTrait, Cache
│  ├─ views/           auth/, layout/ (header, sidebar, footer idéntico patrón), paciente/, medico/, cita/, agenda/, reporte/ (error/403,404)
│  └─ routes.php        (o registerRoutes en App.php)
├─ config/             app.php (RUTA_SITE="/MCP/apps/SRCC_MCP"), database.php
├─ public/             index.php, .htaccess (S-Indexes), css/ js/ img/
├─ sql/                srcc_citas.sql + migraciones/ (001.cpp)
├─ logs/               con .htaccess "Require all denied" (Apache 2.4)
├─ cache/              con .htaccess
├─ backups/            con .htaccess
├─ uploads/            con .htaccess
├─ tests/              run_tests.php, *Test.php
├─ .env.development → (SEPARAR: .env NO se versiona; crear .env.example)
├─ .env                (fuera de repos + .gitignore)
└─ .htaccess           (raíz SRCC_MCP: bloquea app/ config/ sql/ scripts/ logs/ backups/ reports/ cache/ uploads/ scratch/ tests/ + sistema de archivos)
```

Componentes copiados/refactorizados 1:1 desde SBN_MCP: core completo, SecurityHelper, AuditTrait, EmailHelper, layout/header/footer (con timer idle), tests harness. Todo cambio: aplicar lo del Baseline de seguridad (sección 6).

## 5. Esquema de base de datos `srcc_citas` (borrador v1)

```sql
usuarios            -- copia la estructura de SBN_MCP (username, password_hash bcrypt, primer_login, sesión fallidas/bloqueos, rol FK)
roles               -- administrador | recepcion | gerencia | medico
especialidades      -- id, nombre, activo
medicos             -- id, especialidad_id FK, nombre, telef/horario: hora_ini TIME, hora_fin TIME, consultorio, espac_activo, activo
pacientes           -- id, nombre_completo, cedula VARCHAR(15) UNIQUE, nacimiento DATE, sexo ENUM('F','M'), telefono, correo,
                     --   direccion, historia_clinica VARCHAR(20), activo, creado_por FK, ts campos
citas               -- id, paciente_id FK, medico_id FK, fecha DATE, hora_inicio TIME, hora_fin TIME,
                     --   tipo ENUM('primera','control'), motivo TEXT, estado ENUM('pendiente','confirmada','completada','cancelada','no_asistida'),
                     --   registrado_por FK, aprobado_por FK, aprobada_en, cancelado_por FK, motivo_cancelacion,
                     --   usuario_atendio FK, notas_en, created_at, updated_at,
                     --   UNIQUE KEY uq_medico_fecha_hora (medico_id, fecha, hora_inicio),
                     --   KEY idx_fecha (fecha), KEY idx_paciente (paciente_id)
auditoria (copiar definición) 
notificaciones (copiar definición)
password_resets (copiar definición)
```

## 6. Baseline de seguridad obligatorio (todo lo aplicado ya en SBN_MCP)

1. **Sesión**: `IDLE_TIMEOUT=600` + `touch()` en cada request autenticado + client timer en footer (warning 9'/logout 10'); `session_regenerate_id(true)` en login (anti-fixación); cookies httponly y Secure.
2. **CSRF en TODOS los POST**: verifyCSRFToken/verifyCSRF (header X-CSRF-Token aceptado), incluidos: login, logout, cambiar clave, CRUD citas/pacientes/médicos, aprobar/rechazar, notificaciones (`markAsRead/all`), backup (rutas POST), verificación semestral-equivalente.
3. **XSS**: `esc()` (htmlspecialchars) en TODA salida PHP; `SecurityHelper::sanitizeRichHtml()` whithelist DOMDocument para texto enriquecido; nunca ecográfico de inputs.
4. **Anti auto-aprobación / segregación**: movimientos/citas `estado='pendiente'`, `usuario_aprueba_id=NULL`, `id: 403+Logger:security('intento_autoaprobacion')` si solicitante=intentidad aprobador.
5. **Inyección SQL**: PDO preparado únicamente; sin interpolación; whitelist de enums para `tipo`.
6. **Headers**: `X-Frame-Options: DENY`, CSP con `frame-ancestors 'none'; object-src 'none'; base-uri 'self'; form-action 'self'`, `nosniff`, Referrer-Policy, Permissions-Policy.
7. **Archivos/inventario**: `.htaccess` raíz + por app (bloqueo app/config/sql/scripts/logs/backups/reports/cache/uploads/scratch/tests/vendor + FilesMatch .env/ini/log/sql/bak + ant traversal ../), `Options -Indexes`; `.env` NO validado como script (marcado).
8. **Criptografía**: bcrypt (cost 12) para claves; códigos de recuperación guardados SOLO в форме fragmento/hash (sha256 6 hex) nunca en clear; no secrets en repos (.env en .gitignore, solo .env.example).
9. **Auth brute force**: rate limit login 5 fallos → 15 min bloqueo; límite verificación de código 10 intentos/hora (Cache file); mensaje genérico anti-enumeración.
10. **Logging & Monitoring**: Logger::security/critical en: login_failed, login_blocked, role_denied, session_idle_timeout, csrf_invalid (logout), intento_autoaprobacion, accesos 403; rotación de logs 10 MB/30 días; bitácora auditoría por acción de escritura.
11. **Open Redirect**: redirect() siempre incorpora base + prefijo `/` (destino nunca absoluto); host saneado con regex para anti-poisoning.
12. **Uploads**: solo extensiones whitelist, renombrar con prefijo uniqid, camino fuera de raíz, .htaccess denegado Apache 2.4 (`Require all denied`).

## 7. Fases de implementación

- **F0 (0.5 d)**: clonar esqueleto SBN_MCP → SRCC_MCP (core, helpers, layout, .htaccess, .gitignore, env.example); renombrar identidades; instalar vendor.
- **F1 (1 d)**: BD `srcc_citas.sql` + migraciones; rutas base; login/logout/contraseña + recuperación; roles; login test.
- **F2 (1.5 d)**: CRUD especialidades y médicos (+ validaciones horario); CRUD pacientes (dedup cédula; + formatteruxe).
- **F3 (2 d)**: CRUD citas con todas las reglas (disponibilidad, no pasadas, segregación pendiente→confirmada), agenda semanal y diaria.
- **F4 (1 d)**: notificaciones + recordatorios; reportes (día/médico, ocupación, no asistencia); dashboard.
- **F5 (1 d)**: pruebas de módulo (tests/), revisión contra baseline sección 6, hardening restante producido (APP_DEBUG=false), manual de usuario breve.

Total estimado: **~6 días** (1 persona full-time).

## 8. Entregables finales

1. Código fuente completo en `apps/SRCC_MCP`.
2. SQL de instalación + migraciones versionadas.
3. Suite de tests automatizados (derivada de la de SBN_MCP).
4. Este plan actualizado con anotaciones de decisiones tomadas.
5. Checklist de seguridad cumplido (baseline sección 6) firmado vs. issues auditados en SBN_MCP.

## 9. Dependencias y riesgos

- **Dependencia**: autenticación por correo real (smtp config en .env) — si no hay SMTP disponible, mantener recordatorio interno dentro de la app y reporte manual.
- **Riesgo**: cambio de rol `medico` (usa el sistema?) — definir si el médico usa actua admin o solo consulta agenda; si solo consulta, vista única de solo lectura.
- **Riesgo**: carga inicial de datos (especialidades/médicos/pacientes históricos) — planificar importación por CSV en F2 si hay datos previos.
- **Supuesto**: Apache 2.4 + PHP 8.x + MySQL (XAMPP local igual al actual).

---

_Generado como parte del endurecimiento de seguridad de SBN_MCP (2026-08-07)._