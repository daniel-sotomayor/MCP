-- =============================================================================
-- MIGRACIÓN 001: Verificaciones + Rol Validador
-- =============================================================================
-- Sistema de Gestión de Bienes Nacionales — MCP
-- Fecha: 2026-07-16
--
-- Aplica a: hospital_bienes (MySQL 8.0+ / MariaDB 10.4+)
-- Idempotente: puede ejecutarse múltiples veces sin romper.
--
-- Cambios:
--   1. Crea tabla `verificaciones` (usada por BienController::verificarSemestral)
--   2. Crea tabla `password_reset_codes` (nueva, reemplazo de password_resets)
--      con columna UNIQUE para que funcione el ON DUPLICATE KEY UPDATE
--   3. Inserta rol `validador_inventario` (usado en rutas y AdminController)
--   4. Agrega índice faltante a password_resets para optimizar getDailyAttempts
-- =============================================================================

SET @ts = NOW();

-- -----------------------------------------------------------------------------
-- 1. TABLA: verificaciones (NUEVA)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `verificaciones` (
  `id_verificacion` int(11) NOT NULL AUTO_INCREMENT,
  `bien_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('semestral','anual','extraordinaria') NOT NULL DEFAULT 'semestral',
  `fecha_verificacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_verificacion`),
  KEY `idx_verificaciones_bien` (`bien_id`),
  KEY `idx_verificaciones_usuario` (`usuario_id`),
  KEY `idx_verificaciones_fecha` (`fecha_verificacion`),
  KEY `idx_verificaciones_bien_fecha` (`bien_id`, `fecha_verificacion`),
  CONSTRAINT `verificaciones_ibfk_1` FOREIGN KEY (`bien_id`) REFERENCES `bienes` (`id_bien`) ON DELETE CASCADE,
  CONSTRAINT `verificaciones_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Registro de verificaciones físicas de bienes (LOBIP Art. 47)';

-- -----------------------------------------------------------------------------
-- 2. ÍNDICE COMPUESTO en password_resets para getDailyAttempts
-- -----------------------------------------------------------------------------
-- El índice idx_password_resets_usuario_fecha ya existe en el SQL base,
-- pero verificamos que esté presente por si se ejecuta este script sobre
-- una BD parcialmente migrada.
SET @idx_exists = (
  SELECT COUNT(*)
  FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'password_resets'
    AND INDEX_NAME = 'idx_password_resets_usuario_fecha'
);
SET @sql = IF(@idx_exists = 0,
  'ALTER TABLE password_resets ADD INDEX idx_password_resets_usuario_fecha (usuario_id, fecha_intento)',
  'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- -----------------------------------------------------------------------------
-- 3. ROL: validador_inventario (NUEVO)
-- -----------------------------------------------------------------------------
-- El rol existe en código (rutas, AdminController::assignValidador) pero
-- no en la BD. Si se intenta usar vía la UI, devuelve 400. Esta migración
-- lo crea con permisos limitados: solo puede verificar bienes físicamente.
INSERT INTO `roles` (`nombre`, `descripcion`, `permisos`, `activo`)
SELECT 'validador_inventario',
       'Validador de Inventario — verifica físicamente los bienes y registra semestrales',
       JSON_ARRAY('bienes_ver','verificaciones_crear'),
       1
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM `roles` WHERE `nombre` = 'validador_inventario'
);

-- -----------------------------------------------------------------------------
-- 4. CONFIGURACIÓN: claves de sistema (NUEVAS)
-- -----------------------------------------------------------------------------
-- settings editables por el admin para evitar hardcodear límites
INSERT INTO `configuracion` (`clave`, `valor`, `descripcion`, `tipo_dato`, `editable`) VALUES
  ('password_reset_expiry_minutes', '30', 'Minutos de validez del código de recuperación', 'integer', 1),
  ('password_reset_max_daily', '5', 'Máximo de códigos de recuperación por día', 'integer', 1),
  ('password_reset_code_length', '6', 'Longitud del código de verificación', 'integer', 1),
  ('session_lifetime_minutes', '30', 'Tiempo de inactividad de sesión en minutos', 'integer', 1),
  ('upload_max_size_mb', '5', 'Tamaño máximo de archivos subidos en MB', 'integer', 1),
  ('dashboard_cache_ttl', '300', 'TTL del cache del dashboard en segundos', 'integer', 1)
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- =============================================================================
-- FIN MIGRACIÓN 001
-- =============================================================================
