# Diagramas de Flujo de Acciones de Usuario — SBN MCP

> **Sistema:** Gestión de Bienes Nacionales — Maternidad Concepción Palacios (UPTEC-MS)
> **Documento:** Flujos de interacción del usuario final por rol y por tarea
> **Base:** Análisis de `app/views` (UI), `app/controllers` (comportamiento) y rutas en `app/core/App.php`

---

## Índice de flujos

| # | Flujo | Usuario objetivo |
|---|-------|------------------|
| U-01 | Navegación principal del sistema (todos los roles) | Todos |
| U-02 | Inicio de sesión y primer acceso | Todos |
| U-03 | Recuperación de contraseña olvidada | Todos |
| U-04 | Registrar un bien nacional (formulario 6 pasos) | administrador, gerencia_bn, registrador |
| U-05 | Buscar, filtrar y ver bienes | Todos |
| U-06 | Editar un bien | administrador, gerencia_bn, controlador_inventario |
| U-07 | Desincorporar un bien | administrador, gerencia_bn |
| U-08 | Verificar semestralmente un bien | administrador, gerencia_bn, validador_inventario |
| U-09 | Crear un acta de movimiento | administrador, gerencia_bn |
| U-10 | Descargar acta (Word/PDF) | Todos los roles |
| U-11 | Generar y exportar reportes BM-1 a BM-4 | administrador, gerencia_bn, controlador_inventario |
| U-12 | Consultar el Control Mural por departamento | Todos |
| U-13 | Gestionar usuarios (administrador) | administrador |
| U-14 | Registrar y gestionar mantenimientos | administrador, gerencia_bn |
| U-15 | Revisar auditoría del sistema | administrador, gerencia_bn, controlador_inventario |
| U-16 | Administrar configuración y backups | administrador |
| U-17 | Cerrar sesión | Todos |

---

## U-01: Navegación principal del sistema

El menú lateral (`app/views/partials/sidebar.php`) cambia según el rol del usuario autenticado.

```mermaid
flowchart TD
    A["🌐 Acceder a la aplicación"] --> B{"¿Sesión<br/>activa?"}
    B -->|No| C["🔐 Pantalla de login"]
    B -->|Sí| D["📊 Dashboard<br/>(métricas, alertas,<br/>bienes y movimientos<br/>recientes)"]
    C --> E["👤 Ingresar credenciales"]
    E --> F{"¿Autenticación<br/>válida?"}
    F -->|No| C
    F -->|Sí| G{"¿primer_login?"}
    G -->|Sí| H["✏️ Cambiar contraseña<br/>(obligatorio)"]
    G -->|No| D
    H --> D

    D --> I{"¿Módulo<br/>seleccionado?"}
    I -->|Inventario| J["📦 /bienes"]
    I -->|Control Mural| K["📍 /inventario-mural"]
    I -->|Movimientos y Actas| L["📜 /movimientos"]
    I -->|Reportes| M["📊 /reportes"]
    I -->|Mantenimientos| N["🛠️ /mantenimientos"]
    I -->|Auditoría| O["📝 /auditoria"]
    I -->|Usuarios| P["👥 /usuarios"]
    I -->|Configuración| Q["⚙️ /configuracion"]
    I -->|Backups| R["💾 /backup/list"]

    J --> S["✅ Fin: módulo Inventario"]
    K --> S
    L --> S
    M --> S
    N --> S
    O --> S
    P --> S
    Q --> S
    R --> S

    style A fill:#e1f5ff
    style D fill:#c8e6c9
    style H fill:#fff9c4
    style S fill:#ffcdd2
```

**Visibilidad por rol (según rutas en `App.php`):**

| Módulo | admin | gerencia_bn | controlador_inventario | registrador |
|--------|:-----:|:-----------:|:----------------------:|:-----------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Inventario /bienes | ✅ | ✅ | ✅ | ✅ |
| Registrar bien | ✅ | ✅ | — | ✅ |
| Editar bien | ✅ | ✅ | ✅ | — |
| Desincorporar bien | ✅ | ✅ | — | — |
| Control Mural | ✅ | ✅ | ✅ | ✅ |
| Movimientos (ver) | ✅ | ✅ | ✅ | ✅ |
| Crear actas | ✅ | ✅ | — | — |
| Reportes | ✅ | ✅ | ✅ | — |
| Mantenimientos (ver) | ✅ | ✅ | ✅ | — |
| Mantenimientos (crear/editar) | ✅ | ✅ | — | — |
| Auditoría | ✅ | ✅ | ✅ | — |
| Usuarios | ✅ | — | — | — |
| Configuración | ✅ | — | — | — |
| Backups | ✅ | — | — | — |

---

## U-02: Inicio de sesión y primer acceso

```mermaid
flowchart TD
    A["🟢 Usuario abre el sistema"] --> B["🔐 Ver pantalla de login"]
    B --> C["✏️ Escribir username y contraseña"]
    C --> D["🔘 Clic en 'Ingresar'"]
    D --> E{"¿Respuesta del<br/>servidor?"}
    E -->|403 Token inválido| F["❌ Recargar página<br/>e intentar de nuevo"]
    E -->|400/401/403/429| G["❌ Ver mensaje de error:<br/>credenciales, usuario inactivo,<br/>bloqueado o intentos excedidos"]
    E -->|200 OK| H{"¿Redirige a<br/>/cambiar-clave?"}
    H -->|Sí| I["✏️ Crear nueva contraseña<br/>(mín. 8, mayúsc., minúsc.,<br/>número y símbolo)"]
    H -->|No| J["📊 Dashboard"]
    I --> K{"¿Coinciden y<br/>cumple fortaleza?"}
    K -->|No| I
    K -->|Sí| J
    J --> L["✅ Sesión iniciada"]

    style A fill:#c8e6c9
    style L fill:#c8e6c9
    style J fill:#e1f5ff
    style G fill:#ffcdd2
```

---

## U-03: Recuperación de contraseña olvidada

```mermaid
flowchart TD
    A["🟢 Usuario no puede ingresar"] --> B["🔗 Clic en '¿Olvidó su contraseña?'"]
    B --> C["✏️ Ingresar correo y cédula"]
    C --> D["🔘 Enviar solicitud"]
    D --> E["📧 Revisar correo<br/>(código de 6 dígitos)"]
    E --> F{"¿Recibió<br/>el código?"}
    F -->|No| G["⚠️ Validar correo/cédula<br/>o intentar al día siguiente<br/>(máx. 5 intentos/día)"]
    G --> C
    F -->|Sí| H["✏️ Ingresar código"]
    H --> I{"¿Código<br/>correcto?"}
    I -->|No| J["❌ Código inválido o expirado"]
    J --> H
    I -->|Sí| K["✏️ Definir nueva contraseña<br/>(ventana de 10 minutos)"]
    K --> L{"¿Cumple<br/>fortaleza?"}
    L -->|No| K
    L -->|Sí| M["✅ Contraseña actualizada"]
    M --> N["🔐 Iniciar sesión con<br/>la nueva contraseña"]
    N --> O["✅ Fin"]

    style A fill:#c8e6c9
    style O fill:#c8e6c9
    style M fill:#e1f5ff
    style J fill:#ffcdd2
```

---

## U-04: Registrar un bien nacional (6 pasos)

Formulario multietapa (`app/views/bien/create.php`) con validación AJAX de duplicados.

```mermaid
flowchart TD
    A["🟢 Ir a Inventario →<br/>'Registrar Nuevo Bien'"] --> B["📋 Paso 1 · Clasificación"]
    B --> B1["✏️ Seleccionar tipo de bien<br/>(Publicación 9)"]
    B1 --> B2["✏️ Código ministerio (opc.)<br/>+ Nro. de Bien (según rol)"]
    B2 --> B3["✏️ Seleccionar estado<br/>(Operativo, Inoperativo,<br/>En Resguardo, Chatarra)"]
    B3 --> B4["➡️ Siguiente"]
    B4 --> C["📋 Paso 2 · Identificación"]
    C --> C1["✏️ Nombre (obligatorio),<br/>descripción, marca,<br/>modelo, serial, color"]
    C1 --> C2["✏️ Año fabricación, condición,<br/>cantidad"]
    C2 --> C3["📸 Foto del bien (opcional,<br/>jpg/png/webp máx 5 MB)"]
    C3 --> C4["➡️ Siguiente"]
    C4 --> D["📋 Paso 3 · Ubicación"]
    D --> D1["✏️ Seleccionar área<br/>(edificio/piso/departamento)"]
    D1 --> D2["✏️ Oficina y posición"]
    D2 --> D3["🔄 Sistema genera C.I.N:<br/>Edificio/Piso/Depto/Oficina/Posición"]
    D3 --> D4["➡️ Siguiente"]
    D4 --> E["📋 Paso 4 · Responsable"]
    E --> E1["✏️ Buscar usuario responsable<br/>(o ingresar nombre+cédula)"]
    E1 --> E2["📸 Foto del responsable (opc.)"]
    E2 --> E3["➡️ Siguiente"]
    E3 --> F["📋 Paso 5 · Datos Económicos"]
    F --> F1["✏️ Valor inicial (defecto 0),<br/>valor residual, vida útil<br/>(defecto 10 años)"]
    F1 --> F2["✏️ Nº factura y fecha de<br/>adquisición (opc.)"]
    F2 --> F3["➡️ Siguiente"]
    F3 --> G["📋 Paso 6 · Observaciones"]
    G --> G1["✏️ Notas (opcional)"]
    G1 --> G2["🔘 Guardar Bien"]
    G2 --> H["🔍 Validación AJAX<br/>duplicados (nro, serial,<br/>código ministerio)"]
    H --> H1{"¿Todo<br/>válido?"}
    H1 -->|No| H2["❌ Volver al paso<br/>con el error"]
    H2 --> G1
    H1 -->|Sí| I["🎉 Modal de éxito:<br/>'Bien registrado correctamente'"]
    I --> J["🔄 Redirigir a /bienes"]
    J --> K["✅ Fin: bien visible<br/>con códigos generados"]

    style A fill:#c8e6c9
    style K fill:#c8e6c9
    style I fill:#e1f5ff
    style H2 fill:#ffcdd2
```

---

## U-05: Buscar, filtrar y ver bienes

```mermaid
flowchart TD
    A["🟢 Menú → Inventario<br/>(/bienes)"] --> B["📋 Tabla paginada<br/>(20 bienes por página)"]
    B --> C{"¿Filtrar<br/>resultados?"}
    C -->|No| D["📄 Navegar páginas"]
    C -->|Sí| E["✏️ Búsqueda general:<br/>código, nombre, nro. bien,<br/>serial, marca, descripción"]
    E --> F["🔧 Filtros combinables:<br/>clasificación, estado,<br/>edificio, área"]
    F --> G["🔘 Aplicar filtros"]
    G --> H{"¿Resultados?"}
    H -->|No| I["⚠️ 'Sin resultados'"]
    H -->|Sí| D
    D --> J["👆 Clic en un bien"]
    J --> K["📄 Ver detalle completo:<br/>datos, C.I.N, responsable,<br/>valor, estado"]
    K --> L{"¿Acción sobre<br/>el bien?"}
    L -->|Editar| M["✏️ Ir a edición (U-06)"]
    L -->|Desincorporar| N["🗑️ Ir a desincorporación (U-07)"]
    L -->|Verificar semestral| O["✅ Ir a verificación (U-08)"]
    L -->|Ver historial| P["📜 Ver movimientos<br/>y verificaciones del bien"]
    L -->|Ninguna| Q["🔴 Fin"]
    M --> Q
    N --> Q
    O --> Q
    P --> Q

    style A fill:#c8e6c9
    style Q fill:#c8e6c9
    style K fill:#e1f5ff
    style I fill:#fff9c4
```

---

## U-06: Editar un bien

```mermaid
flowchart TD
    A["🟢 Botón 'Editar' en el bien"] --> B{"¿Rol permite editar?<br/>(admin, gerencia_bn,<br/>controlador_inventario)"}
    B -->|No| C["⛔ Página 403 Acceso denegado"]
    B -->|Sí| D["📋 Formulario precargado<br/>con los datos actuales"]
    D --> E["✏️ Modificar campos<br/>(área recalcula C.I.N)"]
    E --> F["📸 Opcional: nueva foto<br/>del bien o responsable"]
    F --> G["🔘 Guardar cambios"]
    G --> H{"¿CSRF válido?"}
    H -->|No| I["❌ Token inválido"]
    H -->|Sí| J["✅ Bien actualizado"]
    J --> K["🔄 Redirigir al detalle<br/>del bien"]
    K --> L["🔴 Fin"]
    I --> L

    style A fill:#c8e6c9
    style L fill:#c8e6c9
    style J fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-07: Desincorporar un bien (dar de baja)

```mermaid
flowchart TD
    A["🟢 Botón 'Desincorporar'<br/>en el detalle del bien"] --> B{"¿Rol autorizado?<br/>(admin, gerencia_bn)"}
    B -->|No| C["⛔ 403 Acceso denegado"]
    B -->|Sí| D["⚠️ Confirmar la baja<br/>del bien (diálogo)"]
    D --> E{"¿Confirma?"}
    E -->|No| F["🔴 Fin: sin cambios"]
    E -->|Sí| G["📤 DELETE /bienes/:id"]
    G --> H{"¿Éxito?"}
    H -->|No| I["❌ Error al desincorporar"]
    H -->|Sí| J["✅ Bien desincorporado:<br/>estado = Desincorporado (5)<br/>+ movimiento registrado"]
    J --> K["🔄 Refrescar inventario"]
    K --> L["🔴 Fin"]
    I --> L

    style A fill:#c8e6c9
    style L fill:#c8e6c9
    style J fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-08: Verificar semestralmente un bien

```mermaid
flowchart TD
    A["🟢 Botón 'Verificar Semestral'<br/>en el detalle del bien"] --> B{"¿Rol autorizado?<br/>(admin, gerencia_bn,<br/>validador_inventario)"}
    B -->|No| C["⛔ 403 Acceso denegado"]
    B -->|Sí| D["✏️ Escribir observaciones<br/>(opcional)"]
    D --> E["🔘 Registrar verificación"]
    E --> F["📤 POST verificar-semestral"]
    F --> G{"¿Éxito?"}
    G -->|No| H["❌ Error interno"]
    G -->|Sí| I["✅ Flash de éxito<br/>+ notificación por correo"]
    I --> J["🔄 Redirigir al bien"]
    J --> K["📜 Historial disponible en<br/>'Verificaciones' del bien"]
    K --> L["🔴 Fin"]
    H --> L

    style A fill:#c8e6c9
    style L fill:#c8e6c9
    style I fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-09: Crear un acta de movimiento

Solo administrador y gerencia_bn. El acta se genera con formato institucional, se previsualiza y se guarda con su contenido.

```mermaid
flowchart TD
    A["🟢 Menú → Movimientos →<br/>'Nueva Acta'"] --> B{"¿Rol autorizado?<br/>(admin, gerencia_bn)"}
    B -->|No| C["⛔ 403 Acceso denegado"]
    B -->|Sí| D["📋 Seleccionar bien<br/>y tipo de acta"]
    D --> E{"¿Tipo de<br/>acta?"}
    E -->|Traslado| F["📍 Elegir área de destino<br/>(la actual = origen)"]
    E -->|Desincorporación| G["🗑️ Confirmar área actual<br/>(baja del inventario)"]
    E -->|Asignación| H["👤 Llenar datos del<br/>responsable: CI,<br/>nombre y apellido"]
    F --> I["✏️ Motivo del movimiento<br/>(obligatorio)"]
    G --> I
    H --> I
    I --> J["👁️ Previsualizar acta<br/>(HTML institucional:<br/>1. Info general,<br/>2. Datos del bien,<br/>3. Responsables/firmas)"]
    J --> K{"¿Se ve<br/>correcta?"}
    K -->|No| D
    K -->|Sí| L["🔘 Guardar Acta"]
    L --> M{"¿Validación<br/>por tipo OK?"}
    M -->|No| N["❌ Errores por campo"]
    N --> I
    M -->|Sí| O["✅ Acta registrada<br/>+ bien actualizado<br/>(traslado → área,<br/>desincorp → estado 5)"]
    O --> P["🔄 Redirigir a<br/>/movimientos/:id"]
    P --> Q["🔴 Fin: acta en el listado"]

    style A fill:#c8e6c9
    style Q fill:#c8e6c9
    style O fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-10: Descargar acta (Word / PDF)

```mermaid
flowchart TD
    A["🟢 Detalle del movimiento<br/>/movimientos/:id"] --> B["🔘 Botón 'Descargar'"]
    B --> C{"¿Formato<br/>elegido?"}
    C -->|Word| D["📥 GET descargar-word<br/>HTML con header JPG<br/>incrustado (base64)"]
    C -->|PDF| E["📥 GET descargar-pdf<br/>TCPDF A4 horizontal<br/>con membrete"]
    D --> F["💾 Archivo: acta_tipo_id.doc"]
    E --> G["💾 Archivo: acta_tipo_id.pdf"]
    F --> H["✅ Descarga completada"]
    G --> H
    H --> I["🔴 Fin"]

    style A fill:#c8e6c9
    style I fill:#c8e6c9
    style D fill:#e1f5ff
    style E fill:#e1f5ff
```

---

## U-11: Generar y exportar reportes (BM-1 a BM-4)

```mermaid
flowchart TD
    A["🟢 Menú → Reportes<br/>(/reportes)"] --> B{"¿Rol autorizado?<br/>(admin, gerencia_bn,<br/>controlador_inventario)"}
    B -->|No| C["⛔ 403 Acceso denegado"]
    B -->|Sí| D{"¿Qué<br/>reporte?"}
    D -->|BM-1| E["📋 Inventario de bienes<br/>activos"]
    D -->|BM-2| F["📋 Bienes desincorporados<br/>y chatarra"]
    D -->|BM-3| G["📋 Movimientos del período<br/>(filtros: fecha desde/hasta,<br/>tipo, estado)"]
    D -->|BM-4| H["📋 Resumen ejecutivo<br/>(totales, distribución,<br/>top 10)"]
    E --> I["🔍 Visualizar en pantalla<br/>con totales"]
    F --> I
    G --> I
    H --> I
    I --> J{"¿Exportar?"}
    J -->|PDF| K["📄 Descargar PDF oficial"]
    J -->|Excel| L["📊 Descargar XLSX"]
    J -->|CSV| M["📥 Descargar CSV<br/>(separador ';')"]
    J -->|No| N["🔴 Fin: solo visualización"]
    K --> O["✅ Descarga completada"]
    L --> O
    M --> O
    O --> N

    style A fill:#c8e6c9
    style N fill:#c8e6c9
    style I fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-12: Consultar el Control Mural por departamento

```mermaid
flowchart TD
    A["🟢 Menú → Control Mural<br/>(/inventario-mural)"] --> B["📋 Ver resumen global<br/>+ áreas agrupadas<br/>por edificio"]
    B --> C{"¿Qué área<br/>consultar?"}
    C -->|Ninguna| D["🔴 Fin: vista global"]
    C -->|Seleccionar área| E["👆 Clic en el área"]
    E --> F["📋 Ver bienes del área:<br/>código, estado, oficina,<br/>posición, valor"]
    F --> G{"¿Exportar el<br/>control del área?"}
    G -->|CSV| H["📥 Descargar CSV del área"]
    G -->|Excel| I["📥 Descargar XLSX del área"]
    G -->|No| J["🔁 Volver al mural"]
    H --> J
    I --> J
    J --> D

    style A fill:#c8e6c9
    style D fill:#c8e6c9
    style F fill:#e1f5ff
```

---

## U-13: Gestionar usuarios (administrador)

```mermaid
flowchart TD
    A["🟢 Menú → Usuarios<br/>(solo administrador)"] --> B{"¿Acción?"}
    B -->|Listar| C["📋 Tabla de usuarios:<br/>rol, estado, último acceso"]
    B -->|Crear| D["✏️ Llenar datos:<br/>nombres, cédula, email,<br/>cargo, rol, clave temporal"]
    D --> D1["🖥️ Ver preview del username<br/>mientras escribe"]
    D1 --> E["🔘 Guardar"]
    E --> F{"¿Datos válidos?"}
    F -->|No| G["❌ Errores por campo"]
    G --> D
    F -->|Sí| H["✅ Usuario creado<br/>+ mostrar username"]
    B -->|Editar| I["✏️ Modificar datos/rol,<br/>activar-desactivar,<br/>o resetear clave temporal"]
    I --> J["🔘 Guardar cambios"]
    B -->|Desactivar| K["🗑️ Desactivar cuenta"]
    K --> K1{"¿Es su propia<br/>cuenta?"}
    K1 -->|Sí| K2["❌ No permitido"]
    K1 -->|No| K3["✅ Usuario desactivado"]
    B -->|Asignar validador| L["🔘 Asignar rol<br/>validador_inventario"]
    L --> M["✅ Rol asignado"]
    H --> N["🔴 Fin"]
    J --> N
    K3 --> N
    M --> N

    style A fill:#c8e6c9
    style N fill:#c8e6c9
    style H fill:#e1f5ff
    style G fill:#ffcdd2
```

---

## U-14: Registrar y gestionar mantenimientos

```mermaid
flowchart TD
    A["🟢 Menú → Mantenimientos"] --> B{"¿Acción?"}
    B -->|Ver listado| C["📋 Filtros por bien,<br/>tipo servicio y fechas"]
    C --> D["👆 Ver detalle de un<br/>mantenimiento"]
    B -->|Nuevo| E["✏️ Seleccionar bien +<br/>tipo (preventivo/correctivo),<br/>fechas, proveedor, técnico,<br/>costo, diagnóstico,<br/>trabajo realizado, garantía"]
    E --> F["🔘 Guardar"]
    F --> G{"¿Bien y tipo<br/>presentes?"}
    G -->|No| H["❌ Errores"]
    H --> E
    G -->|Sí| I["✅ Mantenimiento registrado"]
    I --> J["🔄 Ir al detalle"]
    B -->|Editar| K["✏️ Modificar campos"]
    K --> L["🔘 Guardar cambios"]
    L --> M["✅ Actualizado"]
    J --> N["🔴 Fin"]
    D --> N
    M --> N

    style A fill:#c8e6c9
    style N fill:#c8e6c9
    style I fill:#e1f5ff
    style H fill:#ffcdd2
```

---

## U-15: Revisar auditoría del sistema

```mermaid
flowchart TD
    A["🟢 Menú → Auditoría"] --> B{"¿Rol autorizado?<br/>(admin, gerencia_bn,<br/>controlador_inventario)"}
    B -->|No| C["⛔ 403 Acceso denegado"]
    B -->|Sí| D["📋 Ver log paginado<br/>(50 registros/página)<br/>ordenado por fecha"]
    D --> E{"¿Filtrar?"}
    E -->|Sí| F["🔧 Filtros: tabla afectada,<br/>acción, búsqueda libre<br/>(tabla, acción, usuario, IP)"]
    F --> G["🔘 Aplicar"]
    G --> D
    E -->|No| H["🔍 Inspeccionar registros:<br/>quién, qué, cuándo,<br/>desde qué IP"]
    H --> I["🔴 Fin"]

    style A fill:#c8e6c9
    style I fill:#c8e6c9
    style D fill:#e1f5ff
    style C fill:#ffcdd2
```

---

## U-16: Administrar configuración y backups (administrador)

```mermaid
flowchart TD
    A["🟢 Menú → Configuración<br/>(solo administrador)"] --> B{"¿Acción?"}
    B -->|Editar parámetros| C["✏️ Modificar:<br/>nombre app, institución,<br/>RIF, frecuencia inventario,<br/>tiempo de sesión,<br/>intentos máximos login"]
    C --> D["🔘 Guardar"]
    D --> E{"¿CSRF y tipos<br/>válidos?"}
    E -->|No| F["❌ Rechazo / sin cambios"]
    E -->|Sí| G["✅ Configuración guardada<br/>+ auditoría"]
    B -->|Crear backup| H["💾 Ejecutar backup<br/>de la base de datos"]
    H --> I["✅ Archivo en backups/"]
    B -->|Listar backups| J["📂 Ver archivos<br/>con tamaño y fecha"]
    G --> K["🔴 Fin"]
    I --> K
    J --> K

    style A fill:#c8e6c9
    style K fill:#c8e6c9
    style G fill:#e1f5ff
    style F fill:#ffcdd2
```

---

## U-17: Cerrar sesión

```mermaid
flowchart TD
    A["🟢 Usuario autenticado"] --> B["👤 Clic en el menú<br/>de usuario (esquina)"]
    B --> C["📋 Menú desplegable"]
    C --> D["🚪 Clic en 'Cerrar sesión'"]
    D --> E["📤 POST /logout<br/>auditoría + destrucción<br/>de la sesión"]
    E --> F["🌐 Redirigir a /login"]
    F --> G["🔐 Pantalla de login"]
    G --> H["✅ Fin: sesión cerrada"]

    style A fill:#e1f5ff
    style H fill:#c8e6c9
    style E fill:#e1f5ff
```

---

## Leyenda de símbolos

- 🟢 **Inicio / Fin** — nodos verdes (inicio) y rojos (fin)
- 📋 **Pantalla / formulario**
- ✏️ **Entrada de datos del usuario**
- 🔘 **Botón / acción de clic**
- ✅ **Éxito / confirmación**
- ❌ **Error / rechazo**
- ⛔ **Acceso denegado (403)**
- ⚠️ **Advertencia / confirmación requerida**
- 🔄 **Redirección / refresco**
- 📥 **Descarga**
- 📄 **Visualización de datos**
- 🔍 **Búsqueda / inspección**
