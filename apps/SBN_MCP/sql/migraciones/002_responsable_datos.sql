-- Migración 002: Agregar columna responsable_datos a movimientos
-- (antes era un script PHP expuesto en public/migrate_responsable.php; se eliminó)
-- Idempotente: falla/salta si la columna ya existe.

ALTER TABLE `movimientos` ADD COLUMN `responsable_datos` TEXT NULL AFTER `contenido_acta`;