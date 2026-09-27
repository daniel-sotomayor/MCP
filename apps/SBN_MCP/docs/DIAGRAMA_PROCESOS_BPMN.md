# Diagramas de Procesos del Sistema — SBN MCP

> **Sistema:** Gestión de Bienes Nacionales — Maternidad Concepción Palacios (UPTEC-MS)
> **Documento:** Diagramas de procesos de negocio y de sistema (BPMN/Mermaid)
> **Base:** Análisis completo del código (`app/controllers`, `app/core`, `sql/hospital_bienes.sql`)
>
> **Versión en imagen/PDF (renders de los 16 procesos):**
> - 📄 PDF único con todos los diagramas: [`diagramas/DIAGRAMA_PROCESOS_SISTEMA.pdf`](diagramas/DIAGRAMA_PROCESOS_SISTEMA.pdf)
> - 🖼️ PNG individuales: [`diagramas/diagrama_01.png`](diagramas/diagrama_01.png) a [`diagramas/diagrama_16.png`](diagramas/diagrama_16.png)

---

## Índice de procesos

| # | Proceso | Controlador | Módulo |
|---|---------|-------------|--------|
| P-01 | Autenticación (login, primer login, logout) | `AuthController` | Autenticación |
| P-02 | Recuperación de contraseña | `PasswordResetController` | Autenticación |
| P-03 | Registro de bien nacional (6 pasos) | `BienController::store` | Inventario |
| P-04 | Edición de bien | `BienController::update` | Inventario |
| P-05 | Desincorporación de bien | `BienController::destroy` | Inventario |
| P-06 | Verificación semestral de bien | `BienController::verificarSemestral` | Inventario |
| P-07 | Movimiento / Acta (traslado, asignación, desincorporación) | `MovimientoController::store` | Movimientos |
| P-08 | Aprobación / rechazo de movimiento | `MovimientoController::approve/reject` | Movimientos |
| P-09 | Generación de reportes BM-1 a BM-4 | `ReporteController` | Reportes |
| P-10 | Control Mural (inventario por departamento) | `InventarioMuralController` | Control Mural |
| P-11 | Gestión de usuarios | `AdminController` | Administración |
| P-12 | Gestión de mantenimientos | `MantenimientoController` | Mantenimientos |
| P-13 | Auditoría del sistema | `AuditoriaController` | Auditoría |
| P-14 | Notificaciones en tiempo real (SSE) | `NotificationController` | Notificaciones |
| P-15 | Backup de la base de datos | `BackupHelper` | Administración |
| P-16 | Configuración del sistema | `ConfiguracionController` | Administración |

---

## P-01: Proceso de Autenticación

`AuthController` — login con rate limiting (5 intentos fallidos = bloqueo 15 min), sesión con regeneración de ID, redirección por estado del usuario.

```mermaid
graph TD
    A["🟢 Inicio:<br/>Usuario accede a /login"] --> B{"¿Sesión<br/>ya activa?"}
    B -->|Sí| B1["Redirigir:<br/>primer_login?<br/>/cambiar-clave : /dashboard"]
    B -->|No| C["✏️ Mostrar formulario<br/>username + password"]
    C --> D["📤 POST /login<br/>Verificar CSRF"]
    D -->|Token inválido| D1["❌ 403 Token inválido"]
    D -->|Token válido| E{"Rate limit:<br/>bloqueado hasta?"}
    E -->|Sí bloqueado| E1["⛔ 429 Esperar 15 minutos"]
    E -->|No| F["🔍 Buscar usuario<br/>activo + rol activo"]
    F --> G{"¿Credenciales<br/>correctas?<br/>(bcrypt)"}
    G -->|No| G1["➕ Registrar intento<br/>fallido"]
    G1 --> G2{"Intentos >= 5?"}
    G2 -->|Sí| G3["🔐 Bloquear usuario<br/>15 minutos"]
    G3 --> G4["❌ 401 Credenciales incorrectas"]
    G2 -->|No| G4
    G -->|Sí| H{"¿Usuario<br/>activo?"}
    H -->|No| H1["❌ 403 Usuario inactivo"]
    H -->|Sí| I["✅ Crear sesión:<br/>user_id, username, rol,<br/>primer_login + regenerar ID"]
    I --> J["📝 Auditoría: login<br/>reset intentos_fallidos<br/>+ ultimo_acceso"]
    J --> K{"¿primer_login?"}
    K -->|Sí| L["✏️ /cambiar-clave<br/>validar fortaleza<br/>(8+, mayúsc, minúsc, nº, esp.)"]
    L --> M["💾 UPDATE password_hash<br/>primer_login = FALSE"]
    M --> N["📝 Auditoría: UPDATE usuarios"]
    K -->|No| O["📊 /dashboard"]
    N --> O
    O --> P{"¿Logout?"}
    P -->|No| Q["🔁 Continuar operación"]
    P -->|Sí| R["🚪 POST /logout<br/>auditoría + Session::destroy"]
    R --> S["🔴 Fin:<br/>redirigir a /login"]

    style A fill:#c8e6c9
    style S fill:#ffcdd2
    style I fill:#e1f5ff
    style G3 fill:#fff9c4
```

---

## P-02: Proceso de Recuperación de Contraseña

`PasswordResetController` — código de 6 dígitos por correo (vigencia 30 min), máx. 5 intentos/día, sesión temporal de 10 minutos para restablecer.

```mermaid
graph TD
    A["🟢 Inicio:<br/>/recuperar-clave"] --> B["✏️ Ingresar<br/>correo + cédula"]
    B --> C["📤 POST /recuperar-clave/enviar"]
    C --> D{"Verificar CSRF<br/>y campos"}
    D -->|Inválido| D1["❌ Error 400/403"]
    D -->|Válido| E{"¿Existe usuario con<br/>email + cédula?"}
    E -->|No| E1["ℹ️ Respuesta genérica<br/>(no revelar existencia)"]
    E -->|Sí| F{"¿Usuario activo?"}
    F -->|No| F1["❌ 403 Usuario inactivo"]
    F -->|Sí| G{"¿Intentos del día<br/>< 5?"}
    G -->|No| G1["⛔ 429 Límite diario<br/>superado"]
    G -->|Sí| H["🔢 Generar código<br/>6 dígitos + expiración<br/>30 min"]
    H --> I["💾 INSERT password_resets<br/>(upsert por usuario/día)"]
    I --> J["📧 Enviar correo con<br/>código de verificación"]
    J --> K{"¿Correo<br/>enviado?"}
    K -->|No| K1["❌ 500 Error de envío"]
    K -->|Sí| L["📝 Auditoría: password_reset_requested"]
    L --> M["🟡 Pantalla: verificar código"]
    M --> N["✏️ Ingresar código<br/>POST /verificar-codigo"]
    N --> O{"¿Código válido,<br/>no usado y no expirado?"}
    O -->|No| O1["❌ Código inválido o expirado"]
    O -->|Sí| P["✅ Marcar código usado<br/>+ sesión temporal reset<br/>(10 minutos)"]
    P --> Q["✏️ /restablecer-clave<br/>nueva clave + confirmar"]
    Q --> R{"¿Sesión reset<br/>válida y vigente?"}
    R -->|No| R1["❌ Sesión inválida/vencida"]
    R -->|Sí| S{"¿Fortaleza de<br/>clave válida?"}
    S -->|No| S1["❌ Errores de fortaleza"]
    S -->|Sí| T["💾 UPDATE password_hash<br/>primer_login = FALSE"]
    T --> U["🧹 Limpiar sesión reset"]
    U --> V["📝 Auditoría: password_reset_completed"]
    V --> W["🔴 Fin:<br/>redirigir a /login"]

    style A fill:#c8e6c9
    style W fill:#ffcdd2
    style T fill:#e1f5ff
    style G1 fill:#fff9c4
```

---

## P-03: Proceso de Registro de Bien Nacional

`BienController::store` — formulario multietapa (6 pasos), validación de duplicados, generación automática de códigos (interno Pub.9 + SUDEBIP), carga de imágenes, auditoría y movimiento de incorporación auto-aprobado. Roles: administrador, gerencia_bn, registrador.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /bienes/nuevo"] --> B["📋 Formulario 6 pasos"]
    B --> C["Paso 1: Clasificación<br/>tipo + nro. bien/código<br/>ministerio + estado"]
    C --> C1{"¿Puede asignar<br/>Nro. de Bien?<br/>(admin, gerencia_bn)"}
    C1 -->|Sí| C2["✏️ Ingresar Nro. Bien<br/>(6-10 alfanuméricos)"]
    C1 -->|No| C3["ℹ️ Nro. será asignado<br/>por gerencia"]
    C2 --> D["Paso 2: Identificación<br/>nombre, descripción, marca,<br/>modelo, serial, color,<br/>año, condición, foto"]
    C3 --> D
    D --> E["Paso 3: Ubicación C.I.N<br/>área (edificio/piso),<br/>oficina, posición"]
    E --> F["Paso 4: Responsable<br/>(opcional): usuario,<br/>cédula, foto"]
    F --> G["Paso 5: Datos económicos<br/>valor inicial/residual,<br/>vida útil, factura, fecha"]
    G --> H["Paso 6: Observaciones"]
    H --> I["📤 POST /bienes<br/>verificar CSRF"]
    I -->|Token inválido| I1["❌ 403 Token inválido"]
    I -->|Token válido| J{"¿Datos<br/>válidos?"}
    J -->|No| J1["❌ Mostrar errores<br/>por paso"]
    J1 --> J2["🔁 Volver al paso<br/>con error"]
    J -->|Sí| K{"¿Nro. Bien / serial /<br/>código ministerio<br/>duplicados?"}
    K -->|Sí| K1["❌ Mostrar duplicado<br/>(validación AJAX previa)"]
    K1 --> J2
    K -->|No| L["🔄 Generar códigos:<br/>interno TIPO-EDIF-PISO-SEQ<br/>SUDEBIP BN-AAAA-000000<br/>(FOR UPDATE)"]
    L --> M["💾 INSERT bienes<br/>(transacción)"]
    M --> N["📸 Subir imagen del bien<br/>y foto del responsable<br/>(MIME real, máx 5 MB)"]
    N --> O["📝 Auditoría: INSERT bienes"]
    O --> P["🔗 Registrar movimiento<br/>'incorporacion' auto-aprobado"]
    P --> Q["💾 COMMIT"]
    Q --> R["✅ Éxito:<br/>redirigir a /bienes"]
    R --> S["🔴 Fin:<br/>bien visible en inventario"]

    style A fill:#c8e6c9
    style S fill:#ffcdd2
    style R fill:#e1f5ff
    style J1 fill:#ffcdd2
    style K1 fill:#ffcdd2
    style L fill:#fff9c4
```

---

## P-04: Proceso de Edición de Bien

`BienController::update` — solo administrador, gerencia_bn (datos del registro) y controlador_inventario (corrige cualquier campo).

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /bienes/:id/editar"] --> B["🔍 Cargar bien<br/>+ catálogos"]
    B --> C{"¿Rol autorizado?<br/>admin, gerencia_bn,<br/>controlador_inventario"}
    C -->|No| C1["⛔ 403 Acceso denegado"]
    C -->|Sí| D["✏️ Editar formulario"]
    D --> E["📤 PUT /bienes/:id"]
    E --> F{"Verificar CSRF"}
    F -->|No| F1["❌ 403 Token inválido"]
    F -->|Sí| G["💾 UPDATE bienes<br/>(datos + C.I.N derivado<br/>del área)"]
    G --> H["📸 ¿Nueva imagen?<br/>procesar y actualizar"]
    H --> I["📝 Auditoría: UPDATE bienes"]
    I --> J["✅ Redirigir a<br/>/bienes/:id"]
    J --> K["🔴 Fin"]

    style A fill:#c8e6c9
    style K fill:#ffcdd2
    style C1 fill:#ffcdd2
    style G fill:#e1f5ff
```

---

## P-05: Proceso de Desincorporación de Bien

`BienController::destroy` — solo administrador y gerencia_bn. Marca estado 5 (Desincorporado) y registra movimiento.

```mermaid
graph TD
    A["🟢 Inicio:<br/>acción 'Desincorporar'<br/>sobre un bien"] --> B{"¿Rol autorizado?<br/>admin, gerencia_bn"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C["📤 DELETE /bienes/:id<br/>verificar CSRF"]
    C -->|Token inválido| C1["❌ 403 Token inválido"]
    C -->|Token válido| D{"¿Bien<br/>existe?"}
    D -->|No| D1["❌ 404 Bien no encontrado"]
    D -->|Sí| E["🔒 Iniciar transacción"]
    E --> F["💾 UPDATE bienes<br/>id_estado = 5<br/>(Desincorporado)"]
    F --> G["🔗 INSERT movimiento<br/>'desincorporacion'<br/>estado aprobado"]
    G --> H["📝 Auditoría: DELETE bienes"]
    H --> I["💾 COMMIT"]
    I --> J["✅ Éxito"]
    I -->|Error| I1["↩️ ROLLBACK + log error"]
    I1 --> J1["❌ 500 Error interno"]
    J --> K["🔴 Fin:<br/>bien dado de baja"]

    style A fill:#c8e6c9
    style K fill:#ffcdd2
    style F fill:#e1f5ff
    style B1 fill:#ffcdd2
```

---

## P-06: Proceso de Verificación Semestral de Bien

`BienController::verificarSemestral` — roles: administrador, gerencia_bn, validador_inventario.

```mermaid
graph TD
    A["🟢 Inicio:<br/>/bienes/:id/verificar-semestral"] --> B{"¿Rol autorizado?<br/>admin, gerencia_bn,<br/>validador_inventario"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C["📤 POST con<br/>observaciones + CSRF"]
    C -->|Token inválido| C1["❌ 403 Token inválido"]
    C -->|Token válido| D["🔒 Transacción"]
    D --> E["💾 INSERT verificaciones<br/>tipo = semestral"]
    E --> F["📝 Auditoría: VERIFY<br/>verificaciones"]
    F --> G["💾 COMMIT"]
    G --> H["📧 Notificar por correo<br/>al contacto de control"]
    H --> I["✅ Flash: verificación<br/>registrada"]
    I --> J["🔴 Fin:<br/>redirigir a /bienes/:id"]
    G -->|Error| G1["↩️ ROLLBACK + flash error"]
    G1 --> J

    style A fill:#c8e6c9
    style J fill:#ffcdd2
    style E fill:#e1f5ff
    style B1 fill:#ffcdd2
```

---

## P-07: Proceso de Movimiento / Acta de Bienes

`MovimientoController::store` — tipos: incorporación, traslado, desincorporación, asignación. Roles: administrador, gerencia_bn. Incluye previsualización del acta institucional y actualización del bien según tipo.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /movimientos/nuevo"] --> B{"¿Rol autorizado?<br/>admin, gerencia_bn"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C["📋 Seleccionar bien<br/>+ tipo de acta"]
    C --> D{"Tipo de<br/>acta?"}
    D -->|Traslado| D1["📍 Definir área<br/>origen + destino"]
    D -->|Desincorporación| D2["🗑️ Área origen<br/>(sale del inventario)"]
    D -->|Asignación| D3["👤 Datos responsable:<br/>CI, nombre, apellido"]
    D -->|Incorporación| D4["➕ Solo vía registro<br/>de bien (P-03)"]
    D1 --> E["✏️ Motivo obligatorio"]
    D2 --> E
    D3 --> E
    E --> F["👁️ POST /movimientos/previsualizar<br/>genera HTML del acta<br/>(encabezado + 3 secciones)"]
    F --> G["📤 POST /movimientos<br/>verificar CSRF"]
    G -->|Token inválido| G1["❌ 403 Token inválido"]
    G -->|Token válido| H{"¿Validaciones<br/>por tipo OK?"}
    H -->|No| H1["❌ Errores por campo"]
    H -->|Sí| I["🔒 Transacción"]
    I --> J["💾 INSERT movimientos<br/>estado = aprobado,<br/>contenido_acta guardado"]
    J --> K{"¿Tipo?"}
    K -->|Traslado| K1["💾 UPDATE bienes:<br/>id_area + C.I.N destino"]
    K -->|Desincorporación| K2["💾 UPDATE bienes:<br/>id_estado = 5"]
    K -->|Asignación| K3["💾 Guardar responsable_datos<br/>(JSON)"]
    K1 --> L["📝 Auditoría: INSERT movimientos"]
    K2 --> L
    K3 --> L
    L --> M["💾 COMMIT"]
    M --> N["✅ Éxito:<br/>redirigir a /movimientos/:id"]
    N --> O["📥 Opcional: descargar<br/>acta Word o PDF"]
    O --> P["🔴 Fin"]
    M -->|Error| M1["↩️ ROLLBACK + 500"]
    M1 --> P

    style A fill:#c8e6c9
    style P fill:#ffcdd2
    style J fill:#e1f5ff
    style H1 fill:#ffcdd2
    style B1 fill:#ffcdd2
```

---

## P-08: Proceso de Aprobación / Rechazo de Movimiento

`MovimientoController::approve / reject` — solo se aplica a movimientos en estado `pendiente`; separación de funciones (no se aprueba acta propia).

```mermaid
graph TD
    A["🟢 Inicio:<br/>movimiento en estado pendiente"] --> B{"¿Rol autorizado?<br/>admin, gerencia_bn"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C{"¿Acción<br/>del usuario?"}
    C -->|Aprobar| D["📤 POST /movimientos/:id/aprobar"]
    C -->|Rechazar| E["📤 POST /movimientos/:id/rechazar"]
    D --> F{"¿Estado = pendiente<br/>y existe?"}
    E --> G{"¿Estado = pendiente<br/>y existe?"}
    F -->|No| F1["❌ Solo movimientos<br/>pendientes"]
    G -->|No| G1["❌ Solo movimientos<br/>pendientes"]
    F -->|Sí| H["💾 UPDATE movimientos<br/>estado = aprobado,<br/>usuario_aprueba, fecha"]
    G -->|Sí| I["💾 UPDATE movimientos<br/>estado = rechazado,<br/>usuario_aprueba, fecha"]
    H --> J["📝 Auditoría: UPDATE movimientos"]
    I --> J
    J --> K["✅ JSON success"]
    K --> L["🔴 Fin"]

    style A fill:#c8e6c9
    style L fill:#ffcdd2
    style H fill:#e1f5ff
    style I fill:#fff9c4
    style F1 fill:#ffcdd2
```

---

## P-09: Proceso de Generación de Reportes (BM-1 a BM-4)

`ReporteController` — roles: administrador, gerencia_bn, controlador_inventario. Formatos: HTML, PDF (TCPDF), CSV, Excel (PhpSpreadsheet).

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /reportes"] --> B{"¿Rol autorizado?<br/>admin, gerencia_bn,<br/>controlador_inventario"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C{"¿Qué<br/>reporte?"}
    C -->|BM-1| D["📋 Inventario de bienes<br/>activos (es_baja = 0)"]
    C -->|BM-2| E["📋 Bienes desincorporados<br/>(es_baja = 1)"]
    C -->|BM-3| F["📋 Movimientos del período<br/>+ filtros fecha/tipo/estado"]
    C -->|BM-4| G["📋 Resumen ejecutivo<br/>por estado/tipo/edificio,<br/>top valor, top áreas"]
    D --> H["🔍 Consultas con<br/>agregados (COUNT/SUM)"]
    E --> H
    F --> H
    G --> H
    H --> I{"¿Formato<br/>de salida?"}
    I -->|HTML| I1["📄 Vista en navegador"]
    I -->|PDF| I2["📄 TCPDF con membrete<br/>+ título + tabla"]
    I -->|CSV| I3["📄 CSV separado por ';'<br/>con BOM UTF-8"]
    I -->|Excel| I4["📄 XLSX con título,<br/>encabezados y datos"]
    I -->|Previsualizar| I5["🖥️ AJAX JSON headers+rows<br/>(para exportación)"]
    I1 --> J["✅ Reporte disponible"]
    I2 --> J
    I3 --> J
    I4 --> J
    I5 --> J
    J --> K["🔴 Fin:<br/>descarga o visualización"]

    style A fill:#c8e6c9
    style K fill:#ffcdd2
    style I1 fill:#e1f5ff
    style B1 fill:#ffcdd2
```

---

## P-10: Proceso de Control Mural (Inventario por Departamento)

`InventarioMuralController` — todos los roles autenticados. Muestra el inventario agrupado por edificio/área y permite exportar por departamento.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /inventario-mural"] --> B["📊 Resumen global<br/>(total + valor, sin baja)"]
    B --> C["🏢 Agrupar áreas<br/>por edificio con<br/>totales por estado"]
    C --> D["👆 Seleccionar área<br/>GET /inventario-mural/:id"]
    D --> E["📋 Listar bienes del área<br/>con C.I.N y estado"]
    E --> F{"¿Acción?"}
    F -->|Exportar CSV| F1["📥 CSV con BOM<br/>por área"]
    F -->|Exportar Excel| F2["📥 XLSX control mural"]
    F -->|Previsualizar| F3["🖥️ AJAX JSON"]
    F -->|Volver| F4["🔁 Al listado de áreas"]
    F1 --> G["✅ Descarga"]
    F2 --> G
    F3 --> G
    G --> H["🔴 Fin"]

    style A fill:#c8e6c9
    style H fill:#ffcdd2
    style E fill:#e1f5ff
```

---

## P-11: Proceso de Gestión de Usuarios

`AdminController` — solo administrador. Username autogenerado (inicial del nombre + apellido, con desambiguación), clave temporal con cambio obligatorio en primer login.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /usuarios"] --> B{"¿Rol = administrador?"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C{"¿Acción?"}
    C -->|Crear| D["📋 Formulario:<br/>nombres, cédula (6-9),<br/>email, cargo, rol,<br/>clave temporal"]
    D --> D1["🖥️ AJAX preview-username<br/>(1ª letra + apellido)"]
    D1 --> E["📤 POST /usuarios<br/>verificar CSRF"]
    E --> F{"¿Datos válidos?<br/>(formato, cédula/email<br/>únicos, clave fuerte)"}
    F -->|No| F1["❌ Errores por campo"]
    F -->|Sí| G["💾 INSERT usuario<br/>username autogenerado,<br/>primer_login = 1"]
    G --> H["📝 Auditoría: INSERT usuarios"]
    H --> I["✅ Éxito: mostrar username<br/>generado"]
    C -->|Editar| J["📋 Formulario edición"]
    J --> K["📤 PUT /usuarios/:id"]
    K --> L["💾 UPDATE rol/cargo/email/activo<br/>(+ opcional nueva clave temporal)"]
    L --> M["📝 Auditoría: UPDATE usuarios"]
    C -->|Desactivar| N["📤 DELETE /usuarios/:id"]
    N --> N1{"¿Es su propio<br/>usuario?"}
    N1 -->|Sí| N2["❌ No puede eliminarse"]
    N1 -->|No| N3["💾 UPDATE activo = 0"]
    N3 --> M
    C -->|Asignar validador| O["📤 POST /usuarios/:id/asignar-validador"]
    O --> P["💾 UPDATE id_rol = validador_inventario"]
    P --> M
    I --> Q["✅ Acción completada"]
    M --> Q
    Q --> R["🔴 Fin"]

    style A fill:#c8e6c9
    style R fill:#ffcdd2
    style G fill:#e1f5ff
    style F1 fill:#ffcdd2
    style B1 fill:#ffcdd2
```

---

## P-12: Proceso de Gestión de Mantenimientos

`MantenimientoController` — ver: admin/gerencia_bn/controlador_inventario; crear/editar: admin/gerencia_bn.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /mantenimientos"] --> B["📋 Listado con filtros<br/>bien, tipo, fechas"]
    B --> C{"¿Acción?"}
    C -->|Ver detalle| C1["👁️ /mantenimientos/:id"]
    C -->|Nuevo| D["📋 Formulario:<br/>bien, tipo servicio,<br/>fechas, proveedor,<br/>técnico, costo,<br/>diagnóstico, trabajo,<br/>garantía"]
    D --> E["📤 POST /mantenimientos<br/>verificar CSRF"]
    E --> F{"¿Bien y tipo<br/>requeridos?"}
    F -->|No| F1["❌ Errores"]
    F -->|Sí| G["💾 INSERT mantenimientos<br/>realizado_por = sesión"]
    G --> H["📝 Auditoría: INSERT"]
    H --> I["✅ Redirigir a detalle"]
    C -->|Editar| J["📋 Formulario edición"]
    J --> K["📤 PUT /mantenimientos/:id"]
    K --> L["💾 UPDATE + auditoría"]
    L --> I
    I --> M["🔴 Fin"]

    style A fill:#c8e6c9
    style M fill:#ffcdd2
    style G fill:#e1f5ff
    style F1 fill:#ffcdd2
```

---

## P-13: Proceso de Auditoría del Sistema

`AuditoriaController` — roles: administrador, gerencia_bn, controlador_inventario. Registro automático de todas las operaciones (INSERT/UPDATE/DELETE/login/logout/VERIFY).

```mermaid
graph TD
    A["🟢 Inicio: operación<br/>sobre el sistema"] --> B["📝 Trait AuditTrait /<br/>logAuditAuth registra:<br/>tabla, registro, acción,<br/>usuario, IP, user-agent,<br/>fecha"]
    B --> C["💾 INSERT auditoria"]
    C --> D["👁️ GET /auditoria<br/>(roles autorizados)"]
    D --> E["📋 Log paginado (50/pág)<br/>+ filtros tabla/acción/búsqueda"]
    E --> F{"¿Filtrar<br/>más?"}
    F -->|Sí| E
    F -->|No| G["🔴 Fin:<br/>visualización del log"]

    style A fill:#c8e6c9
    style G fill:#ffcdd2
    style B fill:#e1f5ff
```

---

## P-14: Proceso de Notificaciones en Tiempo Real (SSE)

`NotificationController` — Server-Sent Events; el cliente mantiene conexión y recibe notificaciones nuevas cada 5 s (heartbeat cada 30 s).

```mermaid
graph TD
    A["🟢 Inicio:<br/>usuario autenticado"] --> B["🔌 GET /notificaciones/stream<br/>headers text/event-stream"]
    B --> C["✅ Evento 'connected'"]
    C --> D["🔄 Loop cada 5 segundos:<br/>consultar notificaciones<br/>id > último + no leídas"]
    D --> E{"¿Nuevas<br/>notificaciones?"}
    E -->|Sí| F["📤 Enviar evento 'notification'<br/>tipo, título, mensaje, link"]
    E -->|No| G{"¿Heartbeat<br/>(30 s)?"}
    G -->|Sí| H["💓 Enviar heartbeat"]
    G -->|No| D
    F --> I["👁️ Usuario abre notificaciones"]
    I --> J["📊 GET count / recent"]
    J --> K["✅ Marcar leída:<br/>POST read o read-all"]
    K --> L["💾 UPDATE notificaciones<br/>leida = TRUE"]
    L --> M["🔴 Fin"]

    style A fill:#c8e6c9
    style M fill:#ffcdd2
    style B fill:#e1f5ff
```

---

## P-15: Proceso de Backup de Base de Datos

`BackupHelper` — solo administrador.

```mermaid
graph TD
    A["🟢 Inicio:<br/>solicitud de backup"] --> B{"¿Rol = administrador?"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C{"¿Acción?"}
    C -->|Crear backup| D["📤 GET /backup/create"]
    D --> E["💾 Volcado mysqldump<br/>+ archivo en backups/"]
    E --> F{"¿Éxito?"}
    F -->|Sí| F1["✅ Registrar backups_log<br/>status = success"]
    F -->|No| F2["❌ Registrar backups_log<br/>status = error"]
    C -->|Listar| G["📋 GET /backup/list"]
    G --> H["📂 Mostrar archivos<br/>con tamaño y fecha"]
    F1 --> I["🔴 Fin"]
    F2 --> I
    H --> I

    style A fill:#c8e6c9
    style I fill:#ffcdd2
    style E fill:#e1f5ff
    style B1 fill:#ffcdd2
```

---

## P-16: Proceso de Configuración del Sistema

`ConfiguracionController` — solo administrador. Parámetros editables con validación de tipo de dato.

```mermaid
graph TD
    A["🟢 Inicio:<br/>GET /configuracion"] --> B{"¿Rol = administrador?"}
    B -->|No| B1["⛔ 403 Acceso denegado"]
    B -->|Sí| C["📋 Listar parámetros<br/>editables (app_nombre,<br/>institucion, frecuencia,<br/>sesión, intentos login...)"]
    C --> D["✏️ Editar valores<br/>POST /configuracion"]
    D --> E{"Verificar CSRF<br/>+ claves válidas"}
    E -->|No| E1["❌ 403/400"]
    E -->|Sí| F["🔄 Validar tipo de dato<br/>(string/integer/float/boolean)"]
    F --> G["💾 UPDATE configuracion<br/>por clave (editable)"]
    G --> H["📝 Auditoría: UPDATE configuracion"]
    H --> I["✅ Configuración guardada"]
    I --> J["🔴 Fin"]

    style A fill:#c8e6c9
    style J fill:#ffcdd2
    style G fill:#e1f5ff
    style B1 fill:#ffcdd2
```

---

## Leyenda general de símbolos

- 🟢 **Inicio / Fin** — nodos verdes (inicio) y rojos (fin)
- 📋 **Proceso / pantalla**
- ✏️ **Entrada de usuario**
- ✅ **Éxito / confirmación**
- ❌ **Error / rechazo**
- ⛔ **Acceso denegado (403)**
- 💾 **Persistencia / escritura en BD**
- 🔍 **Consulta / lectura**
- 📝 **Auditoría**
- 📤 **Petición HTTP**
- 🔄 **Lógica / generación / bucle**
- ⏳ **Espera / pendiente**
- 📥 **Descarga**
- 📧 **Correo electrónico**
