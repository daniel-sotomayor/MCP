# Manual de Usuario y Manual Técnico del Sistema SBN_MCP

**Proyecto:** Sistema de Gestión de Bienes Nacionales de la Maternidad Concepción Palacios (SBN_MCP)

**Institución:** Universidad Politécnica Territorial de Caracas Mariscal Sucre (UPTECMS)

**Fecha de generación:** 02 de junio de 2026

---

## Tabla de contenidos

1. Introducción
2. Descripción general del sistema
3. Niveles de acceso y roles
4. Guía de uso del sistema
   - 4.1 Autenticación e inicio de sesión
   - 4.2 Dashboard
   - 4.3 Gestión de inventario
   - 4.4 Registro y edición de bienes
   - 4.5 Movimientos, traslados y actas
   - 4.6 Desincorporaciones
   - 4.7 Reportes e informes
   - 4.8 Auditoría
5. Manual técnico e instalación
   - 5.1 Arquitectura del sistema
   - 5.2 Modelo de datos
   - 5.3 Requisitos del sistema
   - 5.4 Instalación en Windows (XAMPP)
   - 5.5 Configuración del entorno
   - 5.6 Verificación y puesta en marcha
6. Seguridad e integridad de datos
7. Mantenimiento y respaldo
8. Problemas comunes y soluciones
9. Glosario
10. Anexos

---

## 1. Introducción

Este manual ha sido elaborado para el personal administrativo, técnico y de gerencia de la Maternidad Concepción Palacios. Describe el uso del sistema SBN_MCP, sus módulos principales, la instalación técnica y las buenas prácticas de operación.

El sistema SBN_MCP permite gestionar el inventario de bienes nacionales, controlar movimientos y traslados, procesar desincorporaciones, emitir reportes oficiales y registrar auditoría de todas las operaciones.

## 2. Descripción general del sistema

SBN_MCP es una aplicación web desarrollada con una arquitectura orientada a capas. Incluye los siguientes módulos principales:

- Autenticación e inicio de sesión
- Gestión de usuarios y roles
- Inventario de bienes
- Registro y edición de bienes
- Movimientos, traslados y actas
- Desincorporaciones
- Reportes fiscales BM-1, BM-2, BM-3 y BM-4
- Auditoría y bitácora
- Configuración del sistema

### Objetivos del sistema

- Controlar y registrar bienes nacionales de la institución.
- Dar trazabilidad a cambios de ubicación, responsables y estados.
- Generar reportes oficiales y archivos exportables.
- Asegurar integridad y seguridad de la información.
- Facilitar la supervisión y auditoría de los procesos.

## 3. Niveles de acceso y roles

El sistema utiliza un esquema de Control de Acceso Basado en Roles (RBAC). Los roles principales son:

- **Administrador:** Acceso completo al sistema, gestión de usuarios, configuración, auditoría y respaldo.
- **Gerencia:** Gestión de reportes, aprobación de solicitudes de movimiento y supervisión general.
- **Registrador:** Registro de nuevos bienes, carga de datos y digitalización de soportes.
- **Controlador:** Seguimiento físico del inventario, verificación de ubicaciones y control de consistencia.
- **Validador:** Verificación de datos durante auditoría y revisión previa a desincorporaciones.

## 4. Guía de uso del sistema

### 4.1 Autenticación e inicio de sesión

1. Abra el navegador institucional e ingrese la dirección del sistema.
2. Introduzca su usuario o correo institucional y la contraseña asignada.
3. Presione el botón de ingreso.
4. El sistema valida las credenciales y abre el dashboard según su rol.

#### Seguridad de acceso

- Después de tres intentos fallidos consecutivos, la cuenta puede bloquearse temporalmente.
- El sistema utiliza protección CSRF y contraseñas hasheadas con bcrypt.
- Al primer ingreso con contraseña temporal, el usuario debe cambiarla.

### 4.2 Dashboard

El panel principal muestra información personalizada según el rol:

- Resumen de bienes registrados y su estado.
- Alertas de traslados pendientes, validaciones y auditorías.
- Accesos directos a módulos frecuentes.

### 4.3 Gestión de inventario

Desde el módulo de inventario puede visualizar, buscar, filtrar y exportar bienes.

#### Consultas y filtros

- Búsqueda por nombre, código, serial o marca.
- Filtros por estado, área, clasificación y edificio.
- Exportación de resultados a PDF o CSV.

#### Detalle del bien

Cada bien muestra:

- Información general y ubicación.
- Responsable actual.
- Historial de movimientos.
- Imágenes y documentos asociados.

### 4.4 Registro y edición de bienes

#### Registrar un nuevo bien

1. Seleccione **Bienes > Nuevo Registro**.
2. Complete los datos del activo:
   - Código CIN
   - Código interno MCP
   - Código ministerial MPPS
   - Descripción, marca, modelo y serial
   - Costo de adquisición, estado físico y condición
   - Departamento/área asignada
3. Adjunte la imagen del bien y el soporte digital (PDF, JPG, PNG).
4. Presione **Guardar registro**.

El sistema genera un identificador único y registra el evento en la bitácora de auditoría.

#### Editar un bien

1. Busque el bien en el catálogo.
2. Presione **Editar**.
3. Modifique los campos permitidos según su rol.
4. Indique la justificación de la modificación.
5. Guarde los cambios.

### 4.5 Movimientos, traslados y actas

#### Solicitar un traslado

1. Seleccione **Movimientos > Solicitar Traslado**.
2. Busque el activo por código o ficha.
3. Indique departamento de origen y destino.
4. Agregue el motivo del movimiento y los responsables.
5. Envíe la solicitud.

El sistema crea una solicitud con estado **Pendiente de Validación**. Al aprobarla, actualiza la ubicación y emite el acta BM-3.

#### Creación de actas

- El acta incluye identificación del bien, tipo de movimiento, origen, destino, motivo y firma del solicitante.
- Se puede previsualizar antes de guardar.
- La acta se almacena en la base de datos y se genera en formato Word o PDF.

### 4.6 Desincorporaciones

1. Ingrese a **Bienes > Desincorporación**.
2. Seleccione el bien y el tipo de desincorporación.
3. Indique la causa técnica u operativa.
4. Adjunte el informe técnico correspondiente.
5. Solicite la validación del Validador y la firma de Gerencia.

Al completar el proceso, el bien cambia a estado **Desincorporado** y se conserva la trazabilidad histórica.

### 4.7 Reportes e informes

El sistema genera los formatos oficiales de la Contraloría General de la República:

- BM-1: Inventario de bienes activos.
- BM-2: Bienes desincorporados.
- BM-3: Movimientos del período.
- BM-4: Resumen ejecutivo.

#### Generación de reportes

1. Vaya a **Reportes > Fiscales**.
2. Seleccione el formato deseado.
3. Defina el período de corte y filtros necesarios.
4. Presione **Generar PDF** o **Exportar CSV**.

### 4.8 Auditoría

El módulo de auditoría registra:

- Usuario y rol.
- Acción realizada.
- Tabla o módulo afectado.
- Fecha, hora e IP de origen.
- Valores previos y nuevos en actualizaciones.

#### Consultar auditoría

- Filtre por acción, usuario, IP o tabla.
- Revise el historial y los detalles de cada operación.

## 5. Manual técnico e instalación

### 5.1 Arquitectura del sistema

El sistema está construido con tres capas:

- **Presentación:** HTML5, CSS3 y JavaScript para la interfaz.
- **Lógica de negocio:** PHP 7.4+ / 8.x con patrón MVC.
- **Datos:** MySQL 8.0 / MariaDB 10.4+ con InnoDB.

### 5.2 Modelo de datos

Las principales entidades son:

- `usuarios`
- `roles`
- `departamentos`
- `bienes_nacionales`
- `movimientos_bienes`
- `desincorporaciones`
- `bitacora_auditoria`

#### Ejemplos de tablas principales

**roles**
```sql
CREATE TABLE roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL UNIQUE,
  descripcion TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**usuarios**
```sql
CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  cedula VARCHAR(15) NOT NULL UNIQUE,
  nombres VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  estado ENUM('activo', 'inactivo', 'bloqueado') DEFAULT 'activo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_rol) REFERENCES roles(id_rol) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**departamentos**
```sql
CREATE TABLE departamentos (
  id_departamento INT AUTO_INCREMENT PRIMARY KEY,
  codigo_dept VARCHAR(20) NOT NULL UNIQUE,
  nombre_dept VARCHAR(150) NOT NULL,
  piso_ubicacion VARCHAR(50),
  responsable_dept VARCHAR(150),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**bienes_nacionales**
```sql
CREATE TABLE bienes_nacionales (
  id_bien INT AUTO_INCREMENT PRIMARY KEY,
  codigo_cin VARCHAR(50) NOT NULL UNIQUE,
  codigo_mcp VARCHAR(50) NOT NULL UNIQUE,
  codigo_mpps VARCHAR(50) NULL,
  descripcion_bien TEXT NOT NULL,
  marca VARCHAR(100),
  modelo VARCHAR(100),
  serial_fabrica VARCHAR(100),
  estado_fisico ENUM('excelente', 'bueno', 'regular', 'deficiente', 'inoperativo') NOT NULL,
  condicion_bien ENUM('activo', 'en_traslado', 'en_mantenimiento', 'desincorporado') DEFAULT 'activo',
  costo_adquisicion DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  id_departamento INT NOT NULL,
  id_registrador INT NOT NULL,
  imagen_bien VARCHAR(255) DEFAULT 'default_asset.png',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE RESTRICT,
  FOREIGN KEY (id_registrador) REFERENCES usuarios(id_usuario) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**bitacora_auditoria**
```sql
CREATE TABLE bitacora_auditoria (
  id_auditoria BIGINT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  accion_realizada VARCHAR(100) NOT NULL,
  tabla_afectada VARCHAR(50) NOT NULL,
  detalles_cambio LONGTEXT,
  direccion_ip VARCHAR(45) NOT NULL,
  user_agent TEXT,
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5.3 Requisitos del sistema

#### Hardware mínimo

- Procesador Dual Core 2.0 GHz o superior.
- 4 GB de RAM mínimo (8 GB recomendado).
- 20 GB de espacio libre en disco.
- Conectividad de red 10/100/1000 Mbps.

#### Software

- Sistema operativo: Windows 10/11 o Linux compatible.
- Servidor web: Apache 2.4.x.
- PHP 7.4+ con extensiones: pdo_mysql, mbstring, openssl, gd, curl, json, zip.
- Base de datos: MySQL 8.0+ o MariaDB 10.4+.
- Navegador: Chrome, Firefox, Edge o Brave actualizado.

### 5.4 Instalación en Windows con XAMPP

1. Instale XAMPP con Apache y MySQL.
2. Inicie Apache y MySQL desde el panel de control de XAMPP.
3. Copie la carpeta del proyecto a `C:\xampp\htdocs\SBN_MCP`.
4. Abra `http://localhost/phpmyadmin/`.
5. Cree la base de datos `hospital_bienes` con cotejamiento `utf8mb4_unicode_ci`.
6. Importe el archivo `sql/hospital_bienes_DEFINITIVO.sql`.
7. En la raíz del proyecto, ejecute:

```powershell
cd C:\xampp\htdocs\SBN_MCP
composer update
```

8. Asegure que las carpetas `uploads/` y `logs/` tengan permisos de escritura.

### 5.5 Configuración del entorno

Configure las credenciales de base de datos en el archivo `.env` o en `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'hospital_bienes');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
define('BASE_URL', 'http://localhost/SBN_MCP/');
define('APP_NAME', 'Sistema de Bienes Nacionales - MCP');
```

### 5.6 Verificación y puesta en marcha

1. Abra el navegador en `http://localhost/SBN_MCP/public/`.
2. Inicie sesión con las credenciales de administrador.
3. Si es la primera sesión, cambie la contraseña temporal.

Credenciales de ejemplo:

- Usuario: `admin@mcp.gob.ve`
- Contraseña temporal: `AdminSBN2026*`

> Nota: Cambie inmediatamente la contraseña temporal y no reuse cuentas compartidas.

## 6. Seguridad e integridad de datos

- Todas las consultas SQL deben usar sentencias preparadas con PDO.
- Formularios POST incluyen tokens CSRF para evitar ataques de falsificación.
- Las contraseñas se almacenan hasheadas con bcrypt.
- Se sanitizan y escapan todas las salidas HTML con `htmlspecialchars()`.
- El acceso a módulos sensibles se controla por roles.

## 7. Mantenimiento y respaldo

### Copias de seguridad

- Mantenga al menos tres copias de respaldo.
- Almacene los respaldos en dos medios diferentes.
- Guarde una copia fuera de las instalaciones.

### Respaldo de base de datos

Ejemplo de comando `mysqldump`:

```bash
mysqldump -u root -p'PASSWORD' hospital_bienes | gzip > /var/backups/sbn_mcp/backup_$(date +%Y%m%d).sql.gz
```

### Respaldo de archivos

Resguarde al menos estas carpetas:

- `uploads/`
- `logs/`
- `vendor/` (opcional, puede regenerarse con Composer)

### Actualización de dependencias

Ejecute en la raíz del proyecto:

```powershell
composer update
```

### Revisión de errores

- Revise `logs/` para errores de PHP o SQL.
- Verifique los permisos de archivos cuando haya problemas de carga.

## 8. Problemas comunes y soluciones

- Error de Composer: "The lock file is not up to date".
  - Solución: ejecutar `composer update`.
- Acceso denegado.
  - Solución: active el usuario o asigne el rol correcto.
- Reporte vacío.
  - Solución: amplíe el rango de fechas o cambie los filtros.
- Error al generar PDF.
  - Solución: verifique la librería TCPDF en `vendor/tecnickcom/tcpdf`.
- Imagen no carga.
  - Solución: asegure permisos de escritura en `uploads/`.

## 9. Glosario

- **Bien Nacional:** activo físico registrado por la institución.
- **Acta:** documento formal que registra un movimiento.
- **Inventario:** listado de bienes con su ubicación y estado.
- **Movimiento:** evento de traslado, incorporación o desincorporación.
- **Auditoría:** registro de operaciones de usuario.
- **Rol:** conjunto de permisos asignados a un usuario.
- **CSRF:** protección contra falsificación de peticiones.
- **CIN:** ubicación física codificada (edificio/piso/departamento/oficina/posición).

## 10. Anexos

### Rutas principales del sistema

- `/login` - Pantalla de acceso.
- `/dashboard` - Panel principal.
- `/bienes` - Inventario de bienes.
- `/bienes/crear` - Crear nuevo bien.
- `/movimientos` - Listado de movimientos.
- `/movimientos/crear` - Crear nueva acta.
- `/reportes` - Menú de reportes.
- `/auditoria` - Auditoría del sistema.
- `/configuracion` - Configuración del sistema.

### Estados de bienes

- Operativo
- Inoperativo
- En resguardo
- En traslado
- Desincorporado

---

Este documento está listo para ser convertido en un manual de Word y servir como referencia técnica y de usuario del sistema SBN_MCP.
