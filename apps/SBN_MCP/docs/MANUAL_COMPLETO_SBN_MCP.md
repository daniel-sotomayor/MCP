# Manual Completo de Uso del Sistema SBN MCP

**Universidad Politécnica Territorial de Caracas Mariscal Sucre (UPTECMS)**  
**Proyecto para la Maternidad Concepción Palacios**

## Índice

1. Introducción
2. Descripción general del sistema
3. Diagramas del sistema
4. Capturas de pantalla del sistema
5. Requisitos e instalación
6. Acceso y autenticación
7. Gestión de usuarios y roles
8. Flujo de trabajo por rol
9. Inventario de bienes
10. Registro y edición de bienes
11. Movimientos y actas
12. Reportes
13. Auditoría
14. Configuración del sistema
15. Mantenimiento y respaldo
16. Problemas comunes y soluciones
17. Glosario

---

## 1. Introducción

Este manual describe el uso completo del Sistema de Gestión de Bienes Nacionales (`SBN_MCP`) desarrollado para la Maternidad Concepción Palacios, bajo el proyecto de la Universidad Politécnica Territorial de Caracas Mariscal Sucre (UPTECMS). El sistema soporta la gestión del inventario de bienes, control de movimientos, actas, reportes, control de usuarios y auditoría.

Está basado en un framework MVC propio con PHP 7.4+, MySQL/MariaDB y front-end en HTML/CSS/JavaScript.

---

## 2. Descripción general del sistema

### Módulos principales

- **Autenticación**: login, cambio de contraseña, recuperación de contraseña.
- **Usuarios y roles**: administración de cuentas, permisos y seguridad.
- **Inventario**: listado, búsqueda, filtros y detalle de bienes.
- **Registro de bienes**: creación y edición de bienes nacionales.
- **Movimientos y actas**: traslados, desincorporaciones e incorporaciones con actas.
- **Reportes**: generación de BM-1, BM-2, BM-3 y BM-4.
- **Auditoría**: registro de todas las operaciones del sistema.
- **Configuración**: parámetros del sistema editables.

### Objetivos del sistema

- Controlar y registrar bienes nacionales.
- Dar trazabilidad a cambios de ubicación y estado.
- Generar reportes oficiales y exportaciones.
- Controlar acceso mediante roles.
- Registrar auditoría de acciones críticas.

## 3. Diagramas del sistema

El proyecto contiene los siguientes diagramas que soportan el manual:

- **Diagrama Entidad-Relación**: estructura de tablas y relaciones de la base de datos.
- **Diagrama de Flujo de Acciones del Usuario**: recorrido de usuario desde login hasta reportes y auditoría.
- **Diagrama de Flujo de Datos**: cómo circulan los datos entre módulos y la base de datos.
- **Diagrama de Procesos BPMN**: procesos clave de inventario, movimientos y aprobaciones.

> Inserte aquí las imágenes exportadas de los diagramas o incruste los diagramas disponibles en la carpeta `docs/`.

### Espacio para captura de pantalla del diagrama

![Captura de pantalla del diagrama](ruta/a/diagrama.png)

## 4. Capturas de pantalla del sistema

En esta sección se pueden agregar imágenes o capturas de pantalla del sistema para ilustrar las principales pantallas y flujos de trabajo.

### Capturas sugeridas

1. Login y autenticación

![Captura de pantalla login](ruta/a/login.png)

2. Dashboard principal

![Captura de pantalla dashboard](ruta/a/dashboard.png)

3. Inventario de bienes

![Captura de pantalla inventario](ruta/a/inventario.png)

4. Registro de nuevo bien

![Captura de pantalla registrar bien](ruta/a/registrar_bien.png)

5. Creación de acta de movimiento

![Captura de pantalla crear acta](ruta/a/crear_acta.png)

6. Reportes

![Captura de pantalla reporte](ruta/a/reporte.png)

7. Auditoría

![Captura de pantalla auditoría](ruta/a/auditoria.png)

---

## 5. Requisitos e instalación

### Requisitos técnicos

- **PHP** 7.4+
- **MySQL** 8.0 / MariaDB 10.4+
- **Apache** 2.4 (XAMPP/LAMP)
- **Composer** 2.0+
- Extensiones PHP: `pdo_mysql`, `mbstring`, `json`, `openssl`, `gd`

### Instalación en Windows con XAMPP

1. Copiar el proyecto a `C:\xampp\htdocs\SBN_MCP`
2. Iniciar Apache y MySQL desde XAMPP Control Panel.
3. Abrir `http://localhost/phpmyadmin`.
4. Importar `sql/hospital_bienes_DEFINITIVO.sql`.
5. Abrir terminal en la carpeta del proyecto y ejecutar:

```bash
cd C:\xampp\htdocs\SBN_MCP
composer update
```

6. Asegurar permisos de escritura en `uploads/` y `logs/`.
7. Acceder a:

```
http://localhost/SBN_MCP/public/
Usuario: admin
Clave: Admin_bn
```

### Captura de pantalla de instalación

![Captura de pantalla de instalación](ruta/a/instalacion.png)

### Instalación en Linux con XAMPP/LAMP

1. Copiar el proyecto a `/opt/lampp/htdocs/SBN_MCP`.
2. Configurar permisos:

```bash
cd /opt/lampp/htdocs/SBN_MCP
sudo chmod -R 777 uploads/ logs/ vendor/
sudo chown -R $USER:$USER .
```

3. Importar base de datos desde phpMyAdmin.
4. Instalar dependencias:

```bash
composer update
```

5. Configurar `.env` con credenciales MySQL.

### Archivo de configuración `.env`

Valores importantes:

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=hospital_bienes
DB_USER=root
DB_PASS=
APP_ENV=development
APP_DEBUG=true
APP_TIMEZONE=America/Caracas
```

> Adaptar `DB_PASS` si MySQL tiene contraseña.

---

## 6. Acceso y autenticación

### Página de login

- URL principal: `/public/login`
- Usuario y contraseña.
- Incluye protección CSRF y limitación de intentos.

### Captura de pantalla del login

![Captura de pantalla del login](ruta/a/login.png)

### Flujo de login

1. El usuario ingresa nombre de usuario y contraseña.
2. El sistema valida credenciales y estado del usuario.
3. Si es el primer ingreso, redirige a la pantalla de cambio de contraseña.
4. Si está activo y es válido, redirige a `dashboard`.

### Cambio de contraseña en primer ingreso

- Obligatorio para usuarios nuevos o con clave temporal.
- La contraseña se guarda con hash bcrypt.
- Después del cambio, el usuario accede al panel.

### Recuperación de contraseña

1. Hacer clic en "¿Olvidaste tu contraseña?".
2. Ingresar correo electrónico.
3. El sistema envía un código de verificación.
4. Ingresar el código y nueva contraseña.
5. El sistema actualiza la contraseña y habilita el acceso.

### Requisitos de contraseña

- Mínimo 12 caracteres.
- Al menos una mayúscula.
- Al menos una minúscula.
- Al menos un número.
- Al menos un carácter especial.

---

## 7. Gestión de usuarios y roles

### Acceso al módulo de usuarios

- Solo lo puede ver el usuario con rol **administrador**.
- Ruta probable: `/administracion/usuarios`.

### Vista de usuarios

Muestra lista de usuarios con:

- Nombre completo
- Usuario
- Email
- Rol
- Estado (Activo/Inactivo)

### Crear usuario nuevo

Pasos:

1. Ingresar tipo y número de documento.
2. Nombre y apellido.
3. Email y usuario.
4. Teléfono y cargo.
5. Seleccionar rol.
6. Guardar.

El sistema puede generar contraseña temporal. El usuario debe cambiarla en su primer acceso.

### Editar usuario

Permite modificar:

- Email
- Teléfono
- Cargo
- Rol
- Estado

### Desactivar usuario

Cambiar el estado a Inactivo evita el acceso al sistema.

### Resetear contraseña

- Opción disponible en el perfil de usuario.
- Genera contraseña temporal.
- Debe comunicarse al usuario para que inicie sesión y cambie la clave.

---

## 8. Flujo de trabajo por rol

### Administrador

Funciones principales:

- Crear, editar y desactivar usuarios.
- Administrar roles y permisos.
- Crear, editar y aprobar movimientos.
- Generar reportes.
- Ver auditoría.
- Configurar parámetros del sistema.

### Gerencia de Bienes Nacionales (`gerencia_bn`)

Funciones principales:

- Registrar bienes.
- Crear actas de movimiento.
- Aprobar movimientos.
- Generar reportes.
- Ver auditoría.

### Registrador

Funciones principales:

- Registrar nuevos bienes.
- Ver bienes y movimientos.
- No puede aprobar movimientos ni eliminar bienes.

### Controlador de Inventario

Funciones principales:

- Supervisar inventario.
- Ver y consultar bienes.
- Generar reportes.
- No puede eliminar bienes ni gestionar usuarios.

### Validador de Inventario

Funciones principales:

- Ver bienes.
- Marcar bienes como verificados.
- Generar reportes de verificación.
- No puede crear ni editar bienes.

---

## 9. Inventario de bienes

### Acceder al inventario

- Menú lateral: **Inventario**.
- Se muestra un listado paginado.

### Captura de pantalla del inventario

![Captura de pantalla del inventario](ruta/a/inventario.png)

### Campos importantes

- `Código Sudebip`
- `Código Interno`
- `Nro. Bien Ministerio`
- `Nombre`
- `Clasificación`
- `Estado`
- `Área`
- `Responsable`
- `Valor inicial`

### Búsqueda y filtros

- Búsqueda libre por nombre, código, serial o marca.
- Filtrar por estado.
- Filtrar por área.
- Filtrar por clasificación.
- Filtrar por edificio.

### Paginación

- 20 registros por página.
- Navegación con flechas y número de página.

### Detalle de bien

Al seleccionar un bien se muestra:

- Información general.
- Ubicación CIN.
- Responsable actual.
- Historial de movimientos.
- Imágenes y documentos asociados.

---

## 10. Registro y edición de bienes

### Crear un bien nacional

1. Ir a **Inventario** → **Registrar Nuevo Bien**.
2. Completar los datos básicos:
   - Nombre del bien
   - Descripción
   - Marca y modelo
   - Serial
   - Año de fabricación
   - Número de factura
   - Fecha de adquisición
   - Valor inicial y valor residual
   - Vida útil en años
3. Seleccionar:
   - Tipo de bien (clasificación)
   - Estado inicial
   - Área y ubicación física (edificio, piso, oficina)
4. Asignar responsable:
   - Usuario de la institución responsable del bien.
5. Confirmar número de bien ministerial o marcar como `S/N`.
6. Subir imágenes del bien o del responsable si se dispone.
7. Guardar.

### Captura de pantalla del formulario de registro de bien

![Captura de pantalla de registrar bien](ruta/a/registrar_bien.png)

### Campos clave del registro

- `codigo_sudebip`: identificador único del sistema.
- `codigo_interno`: generado automáticamente según tipo y área.
- `nro_bien_ministerio`: número oficial del ministerio.
- `es_sn`: indica si no tiene número ministerial.
- `id_tipo`: clasificación del bien.
- `id_area`: área donde se ubica.
- `responsable_id` o `responsable_nombre`.
- `cin_edificio`, `cin_piso`, `cin_departamento`, `cin_oficina`, `cin_posicion`.

### Edición de un bien

- Abrir el bien desde el inventario.
- Editar campos permitidos.
- Guardar cambios.
- Cada edición queda registrada en auditoría.

### Validaciones

- El nombre del bien es obligatorio.
- El tipo y área deben existir en el sistema.
- El valor inicial es numérico.
- El número de bien ministerial puede quedar como `S/N` si no aplica.

---

## 11. Movimientos y actas

### Tipos de movimientos

- **Incorporación**: ingreso formal de un bien al inventario.
- **Traslado**: cambio de ubicación entre áreas.
- **Desincorporación**: baja definitiva de un bien (chatarra, donación, baja administrativa).

### Acceso a movimientos

- Menú lateral: **Movimientos y Actas**.
- Vista de lista con filtros:
  - Tipo de movimiento.
  - Estado.
  - Búsqueda por bien.

### Flujo de creación de actas

1. Seleccionar **Nueva Acta**.
2. Elegir bien.
3. Seleccionar tipo de movimiento.
4. Indicar área de origen y, si aplica, área de destino.
5. Ingresar motivo y justificación.
6. Previsualizar el acta.
7. Guardar.

### Captura de pantalla de creación de acta

![Captura de pantalla de crear acta](ruta/a/crear_acta.png)

### Previsualización de acta

- El sistema genera un documento con formato oficial.
- Permite revisar antes de guardar.
- El formato incluye:
  - Identificación del bien.
  - Tipo de movimiento.
  - Áreas de origen/destino.
  - Motivo.
  - Firma del solicitante.

### Guardar acta

- Se registra el acta en la base de datos.
- El sistema actualiza el bien automáticamente según el tipo de movimiento.

#### Comportamiento en el sistema actual

- Si el tipo es **traslado**, se actualiza el área del bien y su ubicación CIN.
- Si el tipo es **desincorporación**, se cambia el estado del bien a baja.
- El acta se almacena con el contenido HTML para descarga.

### Aprobación de actas

- El sistema tiene métodos para aprobar o rechazar actas pendientes.
- En el flujo ideal, un segundo usuario con permiso revisa la acta y aprueba o rechaza.
- Los cambios de estado quedan registrados en auditoría.

### Descarga de actas

- Desde la vista del movimiento se puede descargar:
  - Formato Word (`.doc`)
  - Formato PDF

### Consideraciones de seguridad

- Solo roles autorizados pueden crear actas: `administrador` y `gerencia_bn`.
- No se puede aprobar un acta propia si se usa separación de funciones.
- Todas las acciones se registran en auditoría.

---

## 12. Reportes

### Acceso a reportes

- Menú lateral: **Reportes**.
- Submódulos para cada reporte.

### Tipos de reportes disponibles

#### BM-1: Inventario de Bienes Activos

- Incluye bienes operativos, inoperativos y en resguardo.
- Exportable en CSV y PDF.

#### BM-2: Bienes Desincorporados

- Muestra bienes dados de baja o desincorporados.
- Incluye fecha de actualización y valor.

#### BM-3: Movimientos del Período

- Lista de movimientos entre fechas.
- Incluye tipo, estado, bien, origen, destino y solicitante.
- Exportable en CSV.

#### BM-4: Resumen Ejecutivo

- Muestra totales:
  - Bienes totales.
  - Bienes activos.
  - Bienes de baja.
- Incluye gráficos y tablas auxiliares:
  - Movimientos del mes.
  - Bienes por clasificación.
  - Bienes por edificio.
  - Top 10 bienes por valor.
  - Áreas con más bienes.

### Generar un reporte

1. Seleccionar el reporte deseado.
2. Ingresar filtros de fecha o categoría si aplica.
3. Hacer clic en **Generar**.
4. Revisar los datos en pantalla.
5. Exportar a PDF o CSV si se requiere.
### Captura de pantalla del reporte

![Captura de pantalla de reporte](ruta/a/reporte.png)
### Uso de reportes

- Auditorías internas.
- Informes a gerencia.
- Control de estado y ubicaciones.
- Seguimiento de desincorporaciones.

---

## 13. Auditoría

### Propósito

Registrar todas las acciones críticas del sistema para trazabilidad y control.

### Qué se registra

- Creación y edición de usuarios.
- Accesos y login.
- Cambios en bienes.
- Movimientos y actas.
- Cambios de configuración.
- Actualizaciones de contraseñas.

### Acceso al log de auditoría

- Módulo disponible para usuarios con permisos de auditoría.
- Mostrado en forma de lista paginada.

### Captura de pantalla de auditoría

![Captura de pantalla de auditoría](ruta/a/auditoria.png)

### Filtros de auditoría

- Tabla afectada.
- Acción (`INSERT`, `UPDATE`, `DELETE`, `LOGIN`, `LOGOUT`, etc.).
- Búsqueda por usuario o IP.

### Consultar un registro

- Revisar la fecha de operación.
- Ver usuario responsable.
- Ver tabla y registro afectado.
- Ver datos del agente de usuario y dirección IP.

---

## 14. Configuración del sistema

### Acceso

- Menú lateral: **Configuración**.
- Solo usuarios autorizados pueden ver y modificar.

### Captura de pantalla de configuración

![Captura de pantalla de configuración](ruta/a/configuracion.png)

### Parámetros editables

- El sistema carga parámetros desde la tabla `configuracion`.
- Solo los parámetros marcados como editables son modificables.
- Se valida el tipo de dato antes de guardar.

### Cambios disponibles

- Datos generales del sistema.
- Límites de valores.
- Opciones de comportamiento configurables.

### Guardar cambios

- Cambiar valores en el formulario.
- Enviar formulario.
- El sistema valida CSRF y persiste cambios.
- Se registra auditoría de la actualización.

---

## 15. Mantenimiento y respaldo

### Respaldo de la base de datos

- Hacer dump de la base de datos con `mysqldump` o phpMyAdmin.
- Guardar copia en un lugar seguro.
- Importar el dump en caso de recuperación.

### Respaldo de archivos

- Resguardar carpetas:
  - `uploads/`
  - `logs/`
  - `vendor/` (opcional, se puede regenerar con Composer)

### Actualización de dependencias

1. Abrir terminal en la raíz del proyecto.
2. Ejecutar:

```bash
composer update
```

3. Verificar que `vendor/` esté actualizado.

### Logs de errores

- Revisar archivos en `logs/`.
- Buscar información de fallos de PHP o SQL.

---

## 16. Problemas comunes y soluciones

### Error de instalación de Composer

- Mensaje: `The lock file is not up to date`.
- Solución: ejecutar `composer update`.

### Error de acceso denegado

- Causa: el usuario está inactivo o no tiene rol.
- Solución: activar usuario o asignar rol correcto.

### Reporte vacío

- Causa: rango de fechas o filtros no tienen datos.
- Solución: ampliar el período o cambiar filtros.

### Error en generación de PDF

- Causa: falta la librería TCPDF.
- Solución: ejecutar `composer install` y verificar `vendor/tecnickcom/tcpdf`.

### Imagen no carga en bien

- Causa: carpeta `uploads/` sin permisos.
- Solución: otorgar permisos de escritura.

---

## 17. Glosario

- **Bien Nacional**: activo físico registrado por la institución.
- **Acta**: documento formal que registra un movimiento.
- **Inventario**: listado de bienes con su ubicación y estado.
- **Movimiento**: evento de traslado, incorporación o desincorporación.
- **Auditoría**: registro de todas las operaciones de usuario.
- **Rol**: conjunto de permisos asignados a un usuario.
- **CSRF**: protección contra falsificación de peticiones.
- **CIN**: ubicación física codificada (edificio/piso/departamento/oficina/posición).

---

## Anexos

### Rutas principales del sistema

- `/login` - Pantalla de acceso.
- `/dashboard` - Panel principal.
- `/bienes` - Inventario de bienes.
- `/bienes/crear` - Crear un nuevo bien.
- `/movimientos` - Listado de movimientos.
- `/movimientos/crear` - Crear nueva acta.
- `/reportes` - Menú de reportes.
- `/auditoria` - Auditoría del sistema.
- `/configuracion` - Parámetros del sistema.

### Tipos de estados de bienes

Aunque los nombres pueden variar según la base de datos, el sistema usa estados como:

- Operativo
- Inoperativo
- En Resguardo
- Baja / Desincorporado


---

## Recomendaciones finales

- Configurar usuarios con el rol correcto antes de usar el sistema.
- No usar cuentas compartidas.
- Registrar correctamente el área y responsable de cada bien.
- Usar reportes periódicamente para reconciliar inventario físico.
- Revisar auditoría siempre que se detecten cambios inesperados.

---

**Este documento cubre el uso completo del sistema SBN MCP, incluyendo instalación, roles, inventario, movimientos, reportes, auditoría y configuración.**
