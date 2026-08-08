# FASE III: DESARROLLO DEL SISTEMA Y MANUALES DE USUARIO Y TÉCNICO

---

**Sistema de Gestión de Bienes Nacionales de la Maternidad Concepción Palacios (SBN_MCP)**

**Universidad Politécnica Territorial de Caracas Mariscal Sucre (UPTECMS)**

**Fecha de generación:** 04 de agosto de 2026

---

## Tabla de Contenidos

1. [Descripción General del Producto Tecnológico](#1-descripción-general-del-producto-tecnológico)
2. [Manual de Usuario](#2-manual-de-usuario)
   - 2.1 [Introducción y Propósito](#21-introducción-y-propósito)
   - 2.2 [Niveles de Acceso y Roles de Usuario](#22-niveles-de-acceso-y-roles-de-usuario)
   - 2.3 [Módulos del Sistema y Guía Operativa](#23-módulos-del-sistema-y-guía-operativa)
   - 2.4 [Módulo de Auditoría Total y Seguridad](#24-módulo-de-auditoría-total-y-seguridad)
3. [Manual Técnico y de Instalación / Despliegue](#3-manual-técnico-y-de-instalación--despliegue)
   - 3.1 [Arquitectura del Sistema](#31-arquitectura-del-sistema)
   - 3.2 [Modelo de Datos y Esquema de la Base de Datos](#32-modelo-de-datos-y-esquema-de-la-base-de-datos)
   - 3.3 [Requerimientos del Sistema](#33-requerimientos-del-sistema)
   - 3.4 [Guía de Instalación Paso a Paso](#34-guía-de-instalación-paso-a-paso)
   - 3.5 [Políticas de Seguridad e Integridad de Datos](#35-políticas-de-seguridad-e-integridad-de-datos)
   - 3.6 [Plan de Respaldos y Mantenimiento](#36-plan-de-respaldos-y-mantenimiento)
4. [Anexos](#4-anexos)

---

# 1. DESCRIPCIÓN GENERAL DEL PRODUCTO TECNOLÓGICO

El **Sistema de Gestión de Bienes Nacionales de la Maternidad Concepción Palacios (SBN MCP)** es una aplicación web concebida para la automatización, control patrimonial, seguimiento de movimientos y resguardo de activos fijos del Departamento de Bienes Nacionales de la Maternidad Concepción Palacios.

La plataforma ha sido desarrollada bajo una arquitectura cliente-servidor utilizando tecnologías estándar abiertas:

| Componente | Tecnología |
|------------|------------|
| Backend | PHP 8.4+ |
| Base de Datos | MySQL / MariaDB 8.0 |
| Frontend | HTML5, CSS3, JavaScript (ES6) |
| Patrón de Diseño | Modelo-Vista-Controlador (MVC) |

### Módulos Principales

- Autenticación e inicio de sesión
- Gestión de usuarios y roles
- Inventario de bienes
- Registro y edición de bienes
- Movimientos, traslados y actas
- Desincorporaciones
- Reportes fiscales BM-1, BM-2, BM-3 y BM-4
- Auditoría y bitácora
- Configuración del sistema

### Objetivos del Sistema

- Controlar y registrar bienes nacionales de la institución.
- Dar trazabilidad a cambios de ubicación, responsables y estados.
- Generar reportes oficiales y archivos exportables.
- Asegurar integridad y seguridad de la información.
- Facilitar la supervisión y auditoría de los procesos.

---

# 2. MANUAL DE USUARIO

## 2.1. Introducción y Propósito

El presente Manual de Usuario tiene como finalidad guiar al personal administrativo, técnico y gerencial del Departamento de Bienes Nacionales de la Maternidad Concepción Palacios en la correcta navegación y operación del sistema SBN MCP.

## 2.2. Niveles de Acceso y Roles de Usuario

El sistema implementa un esquema de **Control de Acceso Basado en Roles (RBAC)**, contemplando cinco (5) perfiles jerárquicos:

### Tabla de Roles y Permisos

| Rol | Módulos Accesibles | Acciones Permitidas |
|-----|-------------------|---------------------|
| **Administrador** | Todos los módulos (Acceso Total) | Gestión de usuarios, auditoría, configuración del sistema, bienes, movimientos y reportes. |
| **Gerencia** | Bienes, Movimientos, Reportes, Consultas | Aprobación de movimientos, generación e impresión de planillas oficiales (BM), consultas avanzadas. |
| **Registrador** | Módulo de Bienes, Incorporaciones | Registro de nuevos activos, carga de fotos, actualización de datos técnicos de bienes. |
| **Controlador** | Módulo de Consultas y Reportes (Solo Lectura) | Búsqueda de bienes, exportación de reportes PDF, verificación de inventario sin permisos de edición. |
| **Validador** | Módulo de Consultas y Verificación | Verificación de consistencia de datos durante auditoría y conciliación patrimonial. |

### Descripción Detallada por Rol

1. **Administrador:** Acceso total a la configuración del sistema, gestión de usuarios, asignación de roles, gestión de base de datos y auditoría completa.

2. **Gerencia:** Acceso a consultas avanzadas, aprobación de solicitudes de traslado/desincorporación, emisión de reportes ejecutivos (BM-1, BM-2, BM-3, BM-4) y monitoreo de indicadores.

3. **Registrador:** Facultad para la incorporación de nuevos activos, carga de datos con Codificación Triple (CIN, formato interno e identificador MPPS) y digitalización de soportes.

4. **Controlador:** Encargado del seguimiento físico, verificación de inventarios murales, aplicación de protocolos de Control Moral y actualización de ubicación geográfica interna.

5. **Validador:** Responsable de verificar la consistencia de datos durante los procesos de auditoría, conciliación patrimonial y revisión previa a la desincorporación final de activos.

## 2.3. Módulos del Sistema y Guía Operativa

### A. Autenticación e Inicio de Sesión

1. Ingrese a la dirección web del sistema a través del navegador institucional.
2. Introduzca su correo electrónico/usuario institucional y contraseña asignada.
3. El sistema verificará las credenciales encriptadas mediante bcrypt y validará la sesión.
4. En caso de tres (3) intentos fallidos consecutivos, la cuenta se bloqueará temporalmente por razones de seguridad.

#### Políticas de Seguridad de Acceso

- Las contraseñas deben contener al menos 12 caracteres, mezclando mayúsculas, minúsculas, números y símbolos.
- Tras 5 intentos fallidos consecutivos, la cuenta se bloqueará temporalmente por 15 minutos.
- Queda estrictamente prohibido compartir credenciales de acceso.
- Cada acción realizada queda registrada en la bitácora de auditoría.

#### Cambio de Contraseña en Primer Ingreso

- Obligatorio para usuarios nuevos o con clave temporal.
- La contraseña se guarda con hash bcrypt.
- Después del cambio, el usuario accede al panel.

#### Recuperación de Contraseña

1. Hacer clic en "¿Olvidaste tu contraseña?".
2. Ingresar correo electrónico.
3. El sistema envía un código de verificación.
4. Ingresar el código y nueva contraseña.
5. El sistema actualiza la contraseña y habilita el acceso.

#### Requisitos de Contraseña

- Mínimo 12 caracteres.
- Al menos una mayúscula.
- Al menos una minúscula.
- Al menos un número.
- Al menos un carácter especial.

---

### B. Dashboard / Panel Principal

Una vez autenticado, el usuario visualiza el panel de control personalizado según su rol:

- **Resumen cuantitativo** de activos registrados, en tránsito, operativos, en mantenimiento y desincorporados.
- **Accesos directos** a las funciones más frecuentes.
- **Panel de alertas dinámicas** (traslados pendientes, solicitudes de revisión, auditorías).

---

### C. Módulo de Gestión de Bienes Nacionales (CRUD)

#### Registro de Activos (Incorporación)

1. Seleccione la opción **"Bienes > Nuevo Registro"**.
2. Complete el formulario ingresando la **Codificación Triple**:
   - **Código CIN** (Codificación de Inventario Nacional - Contraloría General de la República).
   - **Código de Control Interno MCP.**
   - **Código de Identificación MPPS.**
3. Ingrese la descripción detallada, marca, modelo, número de serie, costo de adquisición, estado físico y departamento/área asignada.
4. Adjunte la imagen del activo y el acta o factura digitalizada (formatos PDF, JPG, PNG).
5. Haga clic en **"Guardar Registro"**. El sistema asignará un identificador único y registrará el evento en la bitácora de auditoría.

#### Campos del Formulario de Registro

| Campo | Descripción | Obligatorio |
|-------|-------------|-------------|
| Código CIN | Codificación Inventario Nacional | Sí |
| Código MCP | Código Interno Maternidad | Sí |
| Código MPPS | Código Ministerio Salud | No |
| Descripción | Descripción detallada del bien | Sí |
| Marca | Marca del fabricante | No |
| Modelo | Modelo del equipo | No |
| Serial | Número de serie de fábrica | No |
| Estado Físico | Excelente, Bueno, Regular, Deficiente, Inoperativo | Sí |
| Condición | Activo, En traslado, En mantenimiento, Desincorporado | Sí |
| Costo de Adquisición | Valor en bolívares | Sí |
| Departamento | Ubicación actual | Sí |
| Imagen | Fotografía del activo | No |

#### Consulta y Búsqueda Filtrada

1. Acceda a **"Bienes > Catálogo General"**.
2. Utilice la barra de búsqueda rápida o aplique filtros avanzados por:
   - Rango de fechas
   - Estado físico
   - Ubicación
   - Categoría
3. Exporte los resultados a formatos PDF o CSV.

#### Edición y Actualización de Ficha Patrimonial

1. Ubique el bien en el catálogo y presione el botón **"Editar"**.
2. Modifique únicamente los campos permitidos según su nivel de usuario.
3. Guarde los cambios especificando la justificación de la modificación.

---

### D. Módulo de Movimientos y Traslados

#### Solicitar un Traslado

1. Seleccione **"Movimientos > Solicitar Traslado"**.
2. Seleccione el activo mediante su código patrimonial o escaneo de ficha.
3. Indique el departamento de origen, departamento de destino, motivo del movimiento y funcionario custodio saliente y entrante.
4. El sistema generará una solicitud en estado **"Pendiente de Validación"**.
5. Una vez aprobada por la Gerencia, la ubicación del bien se actualizará automáticamente y se emitirá el **Acta Movimiento (Formato BM-3)**.

#### Tipos de Movimientos

| Tipo | Descripción |
|------|-------------|
| **Incorporación** | Ingreso formal de un bien al inventario. |
| **Traslado** | Cambio de ubicación entre áreas. |
| **Desincorporación** | Baja definitiva de un bien (chatarra, donación, baja administrativa). |

#### Creación de Actas

1. Seleccione **Nueva Acta**.
2. Elija el bien.
3. Seleccione el tipo de movimiento.
4. Indique área de origen y, si aplica, área de destino.
5. Ingrese motivo y justificación.
6. Previsualice el acta.
7. Guarde.

El acta incluye:
- Identificación del bien
- Tipo de movimiento
- Áreas de origen/destino
- Motivo
- Firma del solicitante

---

### E. Módulo de Desincorporaciones (Formato BM-2)

1. Inicie la solicitud en **"Bienes > Desincorporación"**.
2. Indique la causa técnica u operativa:
   - Obsolescencia
   - Daño irreparable
   - Chatarreo
   - Pérdida
3. Adjunte el informe técnico del área correspondiente.
4. Al recibir la validación del perfil Validador y la firma digital de Gerencia, el activo cambiará a estado **"Desincorporado"**, conservando la trazabilidad histórica dentro de la base de datos sin eliminación física del registro.

---

### F. Módulo de Reportes e Informes Fiscales

El sistema genera automáticamente los formatos estandarizados por la **Publicación N.º 9 de la Contraloría General de la República**:

| Formato | Descripción | Contenido |
|---------|-------------|-----------|
| **BM-1** | Inventario de Bienes Activos | Listado consolidado de activos fijos por dependencia. |
| **BM-2** | Bienes Desincorporados | Relación de bienes retirados del servicio. |
| **BM-3** | Movimientos del Período | Historial de traslados y cambios de responsabilidad. |
| **BM-4** | Resumen Ejecutivo | Consolidado financiero e inventario general patrimonial. |

#### Para Generar un Reporte

1. Navegue a **"Reportes > Fiscales"**.
2. Seleccione el tipo de formato (BM-1, BM-2, BM-3, BM-4) y el período contable/fecha de corte.
3. Presione **"Generar PDF"** o **"Exportar CSV"**.

---

## 2.4. Módulo de Auditoría Total y Seguridad

El módulo de auditoría opera de manera transparente para los usuarios operativos y registra cada acción dentro de la plataforma:

| Campo | Descripción |
|-------|-------------|
| Usuario | Nombre del usuario y rol |
| Acción | Tipo de acción realizada (Inserción, Modificación, Eliminación Lógica, Consulta, Exportación) |
| Fecha/Hora | Fecha y hora exacta de la operación |
| IP | Dirección IP de origen |
| Datos | Valores previos y valores nuevos en caso de actualización de registros |

### Consultar Auditoría

- Filtre por acción, usuario, IP o tabla.
- Revise el historial y los detalles de cada operación.

---

# 3. MANUAL TÉCNICO Y DE INSTALACIÓN / DESPLIEGUE

## 3.1. Arquitectura del Sistema

El sistema SBN MCP está diseñado bajo una **arquitectura multicapa desacoplada** orientada a la web:

### Capas del Sistema

| Capa | Tecnología | Función |
|------|------------|---------|
| **Presentación (Frontend)** | HTML5 semántico, CSS3 responsivo (Tailwind/Bootstrap), JavaScript ES6 | Interfaz de usuario, validaciones reactivas, llamadas asíncronas AJAX |
| **Aplicación (Backend)** | PHP 7.4+ / 8.x con patrón MVC | Lógica de negocio, controladores, ruteo |
| **Datos (Database)** | MySQL 8.0 / MariaDB 10.4+ | Motor relacional con transacciones ACID, integridad referencial |

### Estructura de Directorios del Proyecto

```
SBN_MCP/
├── config/             # Archivos de configuración general
├── src/
│   ├── Controllers/    # Controladores (Auth, Bienes, Movimientos, Reportes, Usuarios)
│   ├── Models/         # Modelos de datos y consultas SQL
│   └── Views/          # Vistas HTML con plantillas
├── public/             # Punto de entrada público (index.php, CSS, JS, imágenes)
├── sql/                # Scripts de creación y datos iniciales
├── uploads/            # Directorio de almacenamiento de imágenes
├── logs/               # Registros de errores del servidor
├── vendor/             # Librerías gestionadas por Composer
├── .env                # Variables de entorno del servidor
└── composer.json       # Configuración de dependencias de Composer
```

---

## 3.2. Modelo de Datos y Esquema de la Base de Datos

### Diagrama Entidad-Relación (Relaciones Principales)

```
usuarios (1) ------ (N) bitacora_auditoria
roles (1) ------ (N) usuarios
departamentos (1) ------ (N) bienes_nacionales
bienes_nacionales (1) ------ (N) movimientos_bienes
bienes_nacionales (1) ------ (N) desincorporaciones
categorias_bienes (1) ------ (N) bienes_nacionales
```

### Estructura SQL de Tablas Principales

#### Tabla de Roles

```sql
CREATE TABLE roles (
  id_rol INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol VARCHAR(50) NOT NULL UNIQUE,
  descripcion TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabla de Usuarios

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

#### Tabla de Departamentos / Ubicaciones

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

#### Tabla de Bienes Nacionales (Codificación Triple)

```sql
CREATE TABLE bienes_nacionales (
  id_bien INT AUTO_INCREMENT PRIMARY KEY,
  codigo_cin VARCHAR(50) NOT NULL UNIQUE COMMENT 'Codificacion Inventario Nacional',
  codigo_mcp VARCHAR(50) NOT NULL UNIQUE COMMENT 'Codigo Interno Maternidad',
  codigo_mpps VARCHAR(50) NULL COMMENT 'Codigo Ministerio Salud',
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

#### Tabla de Bitácora de Auditoría

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

### Diccionario de Datos - Tabla bienes

| Campo | Tipo de Dato | Nulo | Clave | Descripción |
|-------|--------------|------|-------|-------------|
| id_bien | INT AUTO_INCREMENT | NO | PRI | Identificador único del bien |
| codigo_cin | VARCHAR(50) | NO | UNI | Código oficial CIN |
| codigo_mcp | VARCHAR(50) | NO | UNI | Código interno Maternidad |
| codigo_mpps | VARCHAR(50) | SI | - | Código Ministerio Salud |
| descripcion_bien | TEXT | NO | - | Nombre y descripción del activo |
| marca | VARCHAR(100) | SI | - | Marca del fabricante |
| modelo | VARCHAR(100) | SI | - | Modelo del equipo |
| serial_fabrica | VARCHAR(100) | SI | - | Número de serie |
| estado_fisico | ENUM | NO | - | Estado del bien |
| condicion_bien | ENUM | NO | - | Condición actual |
| costo_adquisicion | DECIMAL(15,2) | NO | - | Valor de adquisición |
| id_departamento | INT | NO | FK | Ubicación actual |
| id_registrador | INT | NO | FK | Usuario que registró |
| imagen_bien | VARCHAR(255) | SI | - | Ruta de imagen |

---

## 3.3. Requerimientos del Sistema

### Requisitos Mínimos de Hardware

| Componente | Mínimo | Recomendado |
|------------|--------|-------------|
| Procesador | CPU Dual Core 2.0 GHz | Quad Core 2.5 GHz+ |
| Memoria RAM | 4 GB | 8 GB |
| Almacenamiento | 20 GB SSD/HDD | 50 GB SSD |
| Red | Fast Ethernet 10/100/1000 Mbps | Gigabit Ethernet |

### Requisitos de Software

| Componente | Versión Requerida |
|------------|-------------------|
| Sistema Operativo | GNU/Linux (Ubuntu 20.04 LTS / Debian 11 / CentOS) o Windows Server 2016/2019/10/11 |
| Servidor Web | Apache HTTP Server 2.4.x o Nginx 1.18+ |
| Lenguaje | PHP 7.4 o PHP 8.1+ |
| Extensiones PHP | pdo_mysql, mbstring, openssl, gd, curl, json, zip |
| Base de Datos | MySQL Server 8.0+ o MariaDB 10.4+ |
| Navegador | Google Chrome, Mozilla Firefox, Microsoft Edge o Brave (versiones actualizadas) |

---

## 3.4. Guía de Instalación Paso a Paso

### Opción A: Despliegue en Entorno Local / Servidor XAMPP (Windows/Linux)

#### 1. Descarga e Instalación del Stack

- Descargue e instale XAMPP con PHP 7.4 o superior y MySQL.
- Inicie los servicios de **Apache** y **MySQL** desde el Control Panel de XAMPP.

#### 2. Clonación / Copia de Archivos del Proyecto

Copie la carpeta raíz del proyecto `sbn_mcp` dentro del directorio de publicación del servidor:

| Sistema Operativo | Ruta |
|-------------------|------|
| Windows | `C:\xampp\htdocs\sbn_mcp\` |
| Linux | `/var/www/html/sbn_mcp/` |

#### 3. Configuración de la Base de Datos

1. Abra el navegador e ingrese a `http://localhost/phpmyadmin/`.
2. Cree una nueva base de datos denominada `sbn_mcp_db` con cotejo `utf8mb4_unicode_ci`.
3. Seleccione la base de datos e importe el archivo de scripts SQL ubicado en `database/schema_sbn_mcp.sql`.

#### 4. Configuración de Variables del Sistema

Abra el archivo de configuración situado en `config/database.php` o `.env` y configure las credenciales de acceso:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sbn_mcp_db');
define('DB_USER', 'root');
define('DB_PASS', ''); // Asigne la contraseña del entorno
define('DB_CHARSET', 'utf8mb4');
define('BASE_URL', 'http://localhost/sbn_mcp/');
define('APP_NAME', 'Sistema de Bienes Nacionales - MCP');
```

#### 5. Instalación de Dependencias (Composer)

Abra la consola de comandos en la carpeta del proyecto y ejecute:

```powershell
cd C:\xampp\htdocs\SBN_MCP
composer update
```

#### 6. Ajuste de Permisos en Archivos y Carpetas (Linux/Unix)

Otorgue permisos de lectura, escritura y ejecución a las carpetas de almacenamiento y subida de archivos:

```bash
sudo chown -R www-data:www-data /var/www/html/sbn_mcp
sudo chmod -R 755 /var/www/html/sbn_mcp
sudo chmod -R 777 /var/www/html/sbn_mcp/uploads/
```

#### 7. Verificación de la Instalación

1. Abra su navegador web e ingrese a `http://localhost/sbn_mcp/`.
2. Inicie sesión con las credenciales por defecto del usuario Administrador:

| Campo | Valor |
|-------|-------|
| Usuario | `admin@mcp.gob.ve` |
| Contraseña temporal | `AdminSBN2026*` |

> **Nota Obligatoria:** El sistema exigirá el cambio inmediato de contraseña en el primer inicio de sesión.

---

## 3.5. Políticas de Seguridad e Integridad de Datos

### Medidas de Seguridad Implementadas

| Medida | Descripción |
|--------|-------------|
| **Inyección SQL** | Todas las peticiones a la base de datos están parametrizadas utilizando Sentencias Preparadas mediante PHP Data Objects (PDO). |
| **Protección CSRF** | Generación e inserción de tokens aleatorios criptográficos en cada formulario POST. |
| **Cifrado de Credenciales** | Las contraseñas de los usuarios se procesan mediante `password_hash()` utilizando el algoritmo bcrypt con factor de coste ajustado. |
| **Prevención XSS** | Sanitización de variables de entrada y escape estricto de salidas HTML mediante `htmlspecialchars()`. |

---

## 3.6. Plan de Respaldos y Mantenimiento

### Estrategia 3-2-1

Para garantizar la continuidad operativa y el resguardo del patrimonio de información institucional:

| Número | Descripción |
|--------|-------------|
| **3** | Se conservan tres (3) copias de seguridad de la base de datos y archivos subidos. |
| **2** | En dos (2) medios de almacenamiento diferentes (disco local del servidor y almacenamiento externo). |
| **1** | Teniendo una (1) copia resguardada fuera de las instalaciones (servidor secundario o backup cifrado). |

### Automatización de Backups MySQL

Script de volcado periódico mediante `mysqldump` ejecutado diariamente vía Cron Job en Linux o Tarea Programada en Windows.

**Ejemplo de Cronjob diario a las 11:59 PM (Linux):**

```bash
59 23 * * * mysqldump -u root -p'PASSWORD' sbn_mcp_db | gzip > /var/backups/sbn_mcp/backup_$(date +\%Y\%m\%d).sql.gz
```

**Comando manual de respaldo:**

```bash
mysqldump -u root -p'PASSWORD' hospital_bienes > backup_SBN_MCP.sql
```

### Respaldo de Archivos

Resguarde al menos estas carpetas:

- `uploads/` - Imágenes y documentos de bienes
- `logs/` - Registros de errores
- `vendor/` - Opcional, puede regenerarse con Composer

### Actualización de Dependencias

```powershell
composer update
```

---

# 4. ANEXOS

## Rutas Principales del Sistema

| Ruta | Descripción |
|------|-------------|
| `/login` | Pantalla de acceso |
| `/dashboard` | Panel principal |
| `/bienes` | Inventario de bienes |
| `/bienes/crear` | Crear nuevo bien |
| `/movimientos` | Listado de movimientos |
| `/movimientos/crear` | Crear nueva acta |
| `/reportes` | Menú de reportes |
| `/auditoria` | Auditoría del sistema |
| `/configuracion` | Configuración del sistema |

## Estados de Bienes

| Estado | Descripción |
|--------|-------------|
| Operativo | Bien en funcionamiento normal |
| Inoperativo | Bien fuera de servicio |
| En Resguardo | Bien bajo resguardo temporal |
| En Traslado | Bien en proceso de movimiento |
| Desincorporado | Bien dado de baja definitiva |

## Problemas Comunes y Soluciones

| Problema | Causa | Solución |
|----------|-------|----------|
| Error de Composer: "The lock file is not up to date" | Dependencias desactualizadas | Ejecutar `composer update` |
| Acceso denegado | Usuario inactivo o sin rol | Activar usuario o asignar rol correcto |
| Reporte vacío | Rango de fechas o filtros sin datos | Ampliar el período o cambiar filtros |
| Error en generación de PDF | Falta la librería TCPDF | Ejecutar `composer install` y verificar `vendor/tecnickcom/tcpdf` |
| Imagen no carga en bien | Carpeta uploads/ sin permisos | Otorgar permisos de escritura en `uploads/` |

## Glosario

| Término | Definición |
|---------|------------|
| **Bien Nacional** | Activo físico registrado por la institución. |
| **Acta** | Documento formal que registra un movimiento. |
| **Inventario** | Listado de bienes con su ubicación y estado. |
| **Movimiento** | Evento de traslado, incorporación o desincorporación. |
| **Auditoría** | Registro de todas las operaciones de usuario. |
| **Rol** | Conjunto de permisos asignados a un usuario. |
| **CSRF** | Protección contra falsificación de peticiones. |
| **CIN** | Ubicación física codificada (edificio/piso/departamento/oficina/posición). |
| **RBAC** | Control de Acceso Basado en Roles. |
| **MVC** | Patrón de diseño Modelo-Vista-Controlador. |
| **PDO** | PHP Data Objects - interfaz para acceder a bases de datos. |
| **bcrypt** | Algoritmo de hash de contraseñas. |

---

**Documento elaborado por la Universidad Politécnica Territorial de Caracas Mariscal Sucre (UPTECMS)**

**Proyecto para la Maternidad Concepción Palacios**

**Fecha:** 04 de agosto de 2026
