-- Migración 003: Índices de rendimiento
-- Agrega índices compuestos para consultas frecuentes del dashboard, SSE y reportes

-- Notificaciones: SSE polling query (usuario_id + leida + id_notificacion)
CREATE INDEX idx_notificaciones_sse ON notificaciones (usuario_id, leida, id_notificacion);

-- Movimientos: filtros por estado + ORDER BY fecha_solicitud
CREATE INDEX idx_movimientos_estado_fecha ON movimientos (estado, fecha_solicitud);
CREATE INDEX idx_movimientos_tipo_fecha ON movimientos (tipo_movimiento, fecha_solicitud);

-- Bienes: búsquedas por código_interno (prefix matching)
CREATE INDEX idx_bienes_codigo_interno ON bienes (codigo_interno);

-- Bienes: filtros del dashboard
CREATE INDEX idx_bienes_estado_area ON bienes (id_estado, id_area);
