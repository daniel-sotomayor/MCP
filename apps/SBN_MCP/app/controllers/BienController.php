<?php
/**
 * =============================================================================
 * CONTROLADOR: BIENES NACIONALES
 * =============================================================================
 * 
 * Gestiona el ciclo de vida completo de los bienes nacionales:
 * - Listado con filtros avanzados (búsqueda, tipo, estado, área, edificio)
 * - Creación con generación automática de códigos (Código Interno Pub. 9)
 * - Visualización detallada con historial de movimientos
 * - Edición con control de permisos por rol
 * 
 * Adaptado a requerimientos MCP - Reunión 23/03/2026
 * 
 * Códigos de clasificación implementados (Publicación 9):
 * - 01: Equipos de Oficina
 * - 02: Del alojamiento
 * - 03: Material de Construcción
 * - 04: Vehículos
 * - 05: Telecomunicaciones
 * - 06: Bienes Hospitalarios
 * - 07: Equipos Científicos
 * - 08: Cultural y Artístico
 * - 13: Equipos de Procesamiento de Datos
 * 
 * @package App\Controllers
 * @author  MCP Development Team
 * @version 1.1.0
 * =============================================================================
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;
use App\Helpers\AuditTrait;
use App\Helpers\EmailHelper;
use App\Core\Logger;

class BienController extends Controller
{
    use AuditTrait;
    public function index(): void
    {
        $search   = $this->sanitizeInput($this->getInput('search', ''));
        $estado   = filter_var($this->getInput('estado', ''), FILTER_VALIDATE_INT);
        $area     = filter_var($this->getInput('area', ''), FILTER_VALIDATE_INT);
        $tipo     = filter_var($this->getInput('tipo', ''), FILTER_VALIDATE_INT);
        $edificio = $this->sanitizeInput($this->getInput('edificio', ''));
        $page     = max(1, filter_var($this->getInput('page', '1'), FILTER_VALIDATE_INT) ?: 1);
        $perPage  = 20;
        $offset   = ($page - 1) * $perPage;

        $where  = "WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (b.codigo_sudebip LIKE :s1 OR b.nombre LIKE :s2
                    OR b.nro_bien_ministerio LIKE :s3 OR b.serial LIKE :s4
                    OR b.marca LIKE :s5 OR b.descripcion LIKE :s6 OR b.codigo_interno LIKE :s7)";
            $s = "%{$search}%";
            $params['s1'] = $s; $params['s2'] = $s; $params['s3'] = $s;
            $params['s4'] = $s; $params['s5'] = $s; $params['s6'] = $s; $params['s7'] = $s;
        }
        if ($estado !== false && $estado > 0)   { $where .= " AND b.id_estado = :estado";    $params['estado']   = $estado; }
        if ($area !== false && $area > 0)       { $where .= " AND b.id_area = :area";        $params['area']     = $area; }
        if ($tipo !== false && $tipo > 0)       { $where .= " AND b.id_tipo = :tipo";        $params['tipo']     = $tipo; }
        if (!empty($edificio))                  { $where .= " AND a.edificio = :edificio";   $params['edificio'] = $edificio; }

        $countRow = Database::fetch(
            "SELECT COUNT(*) AS n FROM bienes b
             LEFT JOIN areas a ON b.id_area = a.id_area
             LEFT JOIN tipos_bien tb ON b.id_tipo = tb.id_tipo
             {$where}",
            $params
        );
        $total     = (int)($countRow['n'] ?? 0);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page       = min($page, $totalPages);

        $sql = "SELECT b.id_bien, b.codigo_sudebip, b.codigo_interno, b.nro_bien_ministerio,
                       b.es_sn, b.nombre, b.descripcion, b.marca, b.modelo, b.cantidad,
                       b.valor_inicial, b.cin_edificio, b.cin_piso, b.cin_departamento,
                       b.cin_oficina, b.cin_posicion,
                       e.nombre AS estado_nombre, e.color AS estado_color,
                       a.nombre_area, a.edificio, a.piso,
                       tb.codigo AS tipo_codigo, tb.nombre AS tipo_nombre,
                       COALESCE(b.responsable_nombre, u.nombre_completo) AS responsable_nombre,
                       CONCAT_WS('/', b.cin_edificio, b.cin_piso, b.cin_departamento, b.cin_oficina, b.cin_posicion) AS cin_completo
                FROM bienes b
                JOIN estados e ON b.id_estado = e.id_estado
                LEFT JOIN areas a ON b.id_area = a.id_area
                LEFT JOIN tipos_bien tb ON b.id_tipo = tb.id_tipo
                LEFT JOIN usuarios u ON b.responsable_id = u.id_usuario
                {$where}
                ORDER BY tb.codigo, b.nro_bien_ministerio, b.nombre
                LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $bienes   = Database::fetchAll($sql, $params);
        $estados  = Database::fetchAll("SELECT * FROM estados ORDER BY id_estado");
        $areas    = Database::fetchAll("SELECT * FROM areas WHERE activa = TRUE ORDER BY edificio, piso, nombre_area");
        $tipos    = Database::fetchAll("SELECT * FROM tipos_bien WHERE activo = TRUE ORDER BY codigo");
        $edificios = Database::fetchAll("SELECT DISTINCT edificio FROM areas WHERE activa = TRUE ORDER BY edificio");

        $this->title = 'Inventario de Bienes Nacionales';
        $this->renderWithLayout('bien/index', compact(
            'bienes','estados','areas','tipos','edificios',
            'search','estado','area','tipo','edificio',
            'page','perPage','total','totalPages'
        ));
    }

    public function create(): void
    {
        $estados  = Database::fetchAll("SELECT * FROM estados WHERE es_baja = FALSE ORDER BY id_estado");
        $areas    = Database::fetchAll("SELECT * FROM areas WHERE activa = TRUE ORDER BY edificio, piso, nombre_area");
        $tipos    = Database::fetchAll("SELECT * FROM tipos_bien WHERE activo = TRUE ORDER BY codigo");
        $personal = Database::fetchAll(
            "SELECT id_usuario, CONCAT(primer_nombre,' ',primer_apellido) AS nombre_completo, cargo
             FROM usuarios WHERE activo = TRUE ORDER BY primer_apellido"
        );

        $puedeAsignarNro = in_array(Session::get('rol'), ['administrador', 'gerencia_bn']);

        $this->title = 'Registrar Nuevo Bien';
        $this->renderWithLayout('bien/create', [
            'csrf_token'       => $this->generateCSRFToken(),
            'estados'          => $estados,
            'areas'            => $areas,
            'tipos'            => $tipos,
            'personal'         => $personal,
            'puedeAsignarNro'  => $puedeAsignarNro,
        ]);
    }

    public function store(): void
    {
        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }

        $errors = $this->validateBienData($this->getInputs());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
            return;
        }

        try {
            $esSn = $this->getInput('es_sn') === '1';
            $nroBien = $esSn ? 'S/N' : trim($this->getInput('nro_bien_ministerio'));

            $area = Database::fetch("SELECT edificio, piso, nombre_area FROM areas WHERE id_area = :id", ['id' => $this->getInput('id_area')]);

            $codigoInterno = $this->generarCodigoInterno(
                $this->getInput('id_tipo'),
                $this->getInput('id_area'),
                $area
            );

            Database::beginTransaction();

            $sql = "INSERT INTO bienes (
                        codigo_sudebip, codigo_interno, codigo_ministerio, nro_bien_ministerio, es_sn,
                        serial, nombre, descripcion, marca, modelo, color, anio_fabricacion,
                        numero_factura, fecha_adquisicion, valor_inicial, valor_residual,
                        vida_util_anos, id_estado, id_tipo, id_area, responsable_id,
                        responsable_nombre, responsable_cedula, cantidad, observaciones, condicion_inicial,
                        cin_edificio, cin_piso, cin_departamento, cin_oficina, cin_posicion
                    ) VALUES (
                        :codigo_sudebip, :codigo_interno, :codigo_ministerio, :nro_bien_ministerio, :es_sn,
                        :serial, :nombre, :descripcion, :marca, :modelo, :color, :anio,
                        :numero_factura, :fecha_adquisicion, :valor_inicial, :valor_residual,
                        :vida_util, :id_estado, :id_tipo, :id_area, :responsable_id,
                        :responsable_nombre, :responsable_cedula, :cantidad, :observaciones, :condicion_inicial,
                        :cin_edificio, :cin_piso, :cin_departamento, :cin_oficina, :cin_posicion
                    )";

            $params = [
                'codigo_sudebip'      => $this->getInput('codigo_sudebip') ?: $this->generarCodigoSudebip(),
                'codigo_interno'      => $codigoInterno,
                'codigo_ministerio'   => $this->getInput('codigo_ministerio'),
                'nro_bien_ministerio' => $nroBien,
                'es_sn'               => $esSn ? 1 : 0,
                'serial'              => $this->getInput('serial'),
                'nombre'              => trim($this->getInput('nombre')),
                'descripcion'         => trim($this->getInput('descripcion')),
                'marca'               => $this->getInput('marca'),
                'modelo'              => $this->getInput('modelo'),
                'color'               => $this->getInput('color'),
                'anio'                => $this->getInput('anio_fabricacion') ?: null,
                'numero_factura'      => $this->getInput('numero_factura'),
                'fecha_adquisicion'   => $this->getInput('fecha_adquisicion') ?: null,
                'valor_inicial'       => $this->getInput('valor_inicial') ?: 0,
                'valor_residual'      => $this->getInput('valor_residual') ?: 0,
                'vida_util'           => $this->getInput('vida_util_anos') ?: 10,
                'id_estado'           => $this->getInput('id_estado') ?: 1,
                'id_tipo'             => $this->getInput('id_tipo'),
                'id_area'             => $this->getInput('id_area'),
                'responsable_id'      => $this->getInput('responsable_id') ?: null,
                'responsable_nombre'  => $this->getInput('responsable_nombre') ?: null,
                'responsable_cedula'  => $this->getInput('responsable_cedula') ?: null,
                'cantidad'            => $this->getInput('cantidad') ?: 1,
                'observaciones'       => $this->getInput('observaciones'),
                'condicion_inicial'   => $this->getInput('condicion_inicial'),
                'cin_edificio'        => $area['edificio'] ?? $this->getInput('cin_edificio'),
                'cin_piso'            => $area['piso'] ?? $this->getInput('cin_piso'),
                'cin_departamento'    => $area['nombre_area'] ?? $this->getInput('cin_departamento'),
                'cin_oficina'         => $this->getInput('cin_oficina'),
                'cin_posicion'        => $this->getInput('cin_posicion'),
            ];

            Database::query($sql, $params);
            $bienId = Database::lastInsertId();

            if (!empty($_FILES['imagen']['tmp_name'])) {
                $imgPath = $this->uploadImagen($_FILES['imagen'], $bienId);
                if ($imgPath) {
                    Database::query("UPDATE bienes SET imagen_path=:p WHERE id_bien=:id",
                        ['p' => $imgPath, 'id' => $bienId]);
                }
            }

            if (!empty($_FILES['responsable_foto']['tmp_name'])) {
                $fotoPath = $this->uploadResponsableFoto($_FILES['responsable_foto'], $bienId);
                if ($fotoPath) {
                    Database::query("UPDATE bienes SET responsable_foto_path=:p WHERE id_bien=:id",
                        ['p' => $fotoPath, 'id' => $bienId]);
                }
            }

            $this->logAudit('INSERT', 'bienes', $bienId);
            $this->registrarMovimiento($bienId, 'incorporacion', null, (int)$this->getInput('id_area'));

            // Notificar a gerencia y administrador que hay un nuevo bien para asignar Nro. de Bien
            $bienNombre = trim($this->getInput('nombre'));
            $bienCodigo = $codigoInterno;
            $registrador = Session::get('nombre_completo') ?? Session::get('username', 'Un usuario');

            \App\Controllers\NotificationController::notifyRole(
                'gerencia_bn',
                'warning',
                'Nuevo Bien Registrado — Asignar Nro. de Bien',
                "{$registrador} registró el bien \"{$bienNombre}\" (Código: {$bienCodigo}). Debe asignarle un Número de Bien.",
                "/bienes/{$bienId}"
            );
            \App\Controllers\NotificationController::notifyRole(
                'administrador',
                'warning',
                'Nuevo Bien Registrado — Asignar Nro. de Bien',
                "{$registrador} registró el bien \"{$bienNombre}\" (Código: {$bienCodigo}). Debe asignarle un Número de Bien.",
                "/bienes/{$bienId}"
            );

            Database::commit();

            // Notificar a gerencia y administrador
            $bienNombre = trim($this->getInput('nombre'));
            $registrador = Session::get('nombre_completo') ?? Session::get('username', 'Un usuario');
            \App\Controllers\NotificationController::notifyRole(
                'gerencia_bn', 'warning',
                'Nuevo Bien — Asignar Nro. de Bien',
                "{$registrador} registró \"{$bienNombre}\" (Código: {$codigoInterno}). Asigne Número de Bien.",
                "/bienes/{$bienId}"
            );
            \App\Controllers\NotificationController::notifyRole(
                'administrador', 'warning',
                'Nuevo Bien — Asignar Nro. de Bien',
                "{$registrador} registró \"{$bienNombre}\" (Código: {$codigoInterno}). Asigne Número de Bien.",
                "/bienes/{$bienId}"
            );

            $this->json([
                'success'  => true,
                'message'  => 'Bien registrado correctamente',
                'redirect' => '/bienes',
            ]);
        } catch (\Exception $e) {
            try {
                Database::rollBack();
            } catch (\Exception $rollbackException) {
                // Ignorar error de rollback si no hay transacción activa
            }
            
            \App\Core\Logger::error('Error al guardar bien', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id'   => Session::get('user_id'),
            ]);
            $this->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function show(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }
        $bien = Database::fetch(
            "SELECT b.*,
                    e.nombre AS estado_nombre, e.color AS estado_color,
                    a.nombre_area, a.edificio, a.piso,
                    tb.codigo AS tipo_codigo, tb.nombre AS tipo_nombre,
                    COALESCE(b.responsable_nombre, u.nombre_completo) AS responsable_nombre,
                    CONCAT_WS(' / ', b.cin_edificio, b.cin_piso, b.cin_departamento, b.cin_oficina, b.cin_posicion) AS cin_completo
             FROM bienes b
             JOIN estados e ON b.id_estado = e.id_estado
             LEFT JOIN areas a ON b.id_area = a.id_area
             LEFT JOIN tipos_bien tb ON b.id_tipo = tb.id_tipo
             LEFT JOIN usuarios u ON b.responsable_id = u.id_usuario
             WHERE b.id_bien = :id",
            ['id' => $id]
        );

        if (!$bien) { $this->notFound(); return; }

        $historial = Database::fetchAll(
            "SELECT m.*, m.tipo_movimiento, m.estado, m.fecha_solicitud, m.motivo,
                    ao.nombre_area AS area_origen, ad.nombre_area AS area_destino,
                    us.nombre_completo AS usuario_solicita,
                    ua.nombre_completo AS usuario_aprueba
             FROM movimientos m
             LEFT JOIN areas ao ON m.area_origen_id = ao.id_area
             LEFT JOIN areas ad ON m.area_destino_id = ad.id_area
             LEFT JOIN usuarios us ON m.usuario_solicita_id = us.id_usuario
             LEFT JOIN usuarios ua ON m.usuario_aprueba_id = ua.id_usuario
             WHERE m.bien_id = :id ORDER BY m.fecha_solicitud DESC",
            ['id' => $id]
        );

        $this->title = 'Bien: ' . htmlspecialchars($bien['nombre']);
        $this->renderWithLayout('bien/show', compact('bien', 'historial') + ['csrf_token' => $this->generateCSRFToken()]);
    }

    public function edit(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }
        $bien = Database::fetch("SELECT * FROM bienes WHERE id_bien = :id", ['id' => $id]);
        if (!$bien) { $this->notFound(); return; }

        $estados  = Database::fetchAll("SELECT * FROM estados ORDER BY id_estado");
        $areas    = Database::fetchAll("SELECT * FROM areas WHERE activa = TRUE ORDER BY edificio, piso, nombre_area");
        $tipos    = Database::fetchAll("SELECT * FROM tipos_bien WHERE activo = TRUE ORDER BY codigo");
        $personal = Database::fetchAll(
            "SELECT id_usuario, CONCAT(primer_nombre,' ',primer_apellido) AS nombre_completo, cargo
             FROM usuarios WHERE activo = TRUE ORDER BY primer_apellido"
        );

        $this->title = 'Editar Bien';
        $this->renderWithLayout('bien/edit', [
            'csrf_token' => $this->generateCSRFToken(),
            'bien'       => $bien,
            'estados'    => $estados,
            'areas'      => $areas,
            'tipos'      => $tipos,
            'personal'   => $personal,
        ]);
    }

    public function update(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }

        $bien = Database::fetch("SELECT id_bien FROM bienes WHERE id_bien = :id", ['id' => $id]);
        if (!$bien) { $this->notFound(); return; }

        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }

        $esSn    = $this->getInput('es_sn') === '1';
        $nroBien = $esSn ? 'S/N' : trim($this->getInput('nro_bien_ministerio'));
        $idArea  = (int) $this->getInput('id_area');
        $idEstado = (int) $this->getInput('id_estado');
        $idTipo  = (int) $this->getInput('id_tipo');
        $area    = $idArea > 0
            ? Database::fetch('SELECT edificio, piso, nombre_area FROM areas WHERE id_area = :id', ['id' => $idArea])
            : null;

        Database::query(
            "UPDATE bienes SET
                nro_bien_ministerio = :nro, es_sn = :es_sn,
                nombre = :nombre, descripcion = :descripcion,
                marca = :marca, modelo = :modelo, color = :color,
                serial = :serial, cantidad = :cantidad,
                valor_inicial = :valor_inicial, valor_residual = :valor_residual,
                fecha_adquisicion = :fecha_adquisicion,
                id_estado = :id_estado, id_tipo = :id_tipo,
                id_area = :id_area, responsable_id = :responsable_id,
                responsable_nombre = :responsable_nombre,
                responsable_cedula = :responsable_cedula,
                cin_edificio = :cin_edificio, cin_piso = :cin_piso,
                cin_departamento = :cin_departamento, cin_oficina = :cin_oficina,
                cin_posicion = :cin_posicion,
                observaciones = :observaciones
             WHERE id_bien = :id",
            [
                'nro'              => $nroBien,
                'es_sn'            => $esSn ? 1 : 0,
                'nombre'           => trim($this->getInput('nombre')),
                'descripcion'      => trim($this->getInput('descripcion')),
                'marca'            => $this->getInput('marca'),
                'modelo'           => $this->getInput('modelo'),
                'color'            => $this->getInput('color'),
                'serial'           => $this->getInput('serial'),
                'cantidad'         => (int)($this->getInput('cantidad') ?: 1),
                'valor_inicial'    => (float)($this->getInput('valor_inicial') ?: 0),
                'valor_residual'   => (float)($this->getInput('valor_residual') ?: 0),
                'fecha_adquisicion'=> $this->getInput('fecha_adquisicion') ?: null,
                'id_estado'        => $idEstado,
                'id_tipo'          => $idTipo,
                'id_area'          => $idArea,
                'responsable_id'   => ($this->getInput('responsable_id') ?: null),
                'responsable_nombre' => $this->getInput('responsable_nombre') ?: null,
                'responsable_cedula' => $this->getInput('responsable_cedula') ?: null,
                'cin_edificio'     => $area['edificio'] ?? '',
                'cin_piso'         => $area['piso'] ?? '',
                'cin_departamento' => $area['nombre_area'] ?? '',
                'cin_oficina'      => $this->getInput('cin_oficina'),
                'cin_posicion'     => $this->getInput('cin_posicion'),
                'observaciones'    => $this->getInput('observaciones'),
                'id'               => $id,
            ]
        );

        if (!empty($_FILES['imagen']['tmp_name'])) {
            $imgPath = $this->uploadImagen($_FILES['imagen'], $id);
            if ($imgPath) {
                Database::query("UPDATE bienes SET imagen_path=:p WHERE id_bien=:id",
                    ['p' => $imgPath, 'id' => $id]);
            }
        }

        if (!empty($_FILES['responsable_foto']['tmp_name'])) {
            $fotoPath = $this->uploadResponsableFoto($_FILES['responsable_foto'], $id);
            if ($fotoPath) {
                Database::query("UPDATE bienes SET responsable_foto_path=:p WHERE id_bien=:id",
                    ['p' => $fotoPath, 'id' => $id]);
            }
        }

        $this->logAudit('UPDATE', 'bienes', $id);
        $this->json(['success' => true, 'redirect' => '/bienes/' . $id]);
    }

    public function destroy(int $id): void
    {
        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
            return;
        }
        if ($id <= 0) { $this->json(['error' => 'ID inválido'], 400); return; }

        $bien = Database::fetch("SELECT id_bien, id_area FROM bienes WHERE id_bien = :id", ['id' => $id]);
        if (!$bien) {
            $this->json(['error' => 'Bien no encontrado'], 404);
            return;
        }

        Database::beginTransaction();
        try {
            // NO se cambia id_estado aquí: la desincorporación queda PENDIENTE
            // y sus efectos (id_estado=5) se aplican al aprobarse el movimiento
            // por un usuario distinto del solicitante (anti auto-aprobación).
            Database::query(
                "INSERT INTO movimientos (bien_id, tipo_movimiento, area_origen_id,
                 usuario_solicita_id, usuario_aprueba_id, fecha_aprobacion, motivo, estado)
                 VALUES (:bien, 'desincorporacion', :area, :user, NULL, NULL, :motivo, 'pendiente')",
                [
                    'bien'   => $id,
                    'area'   => $bien['id_area'],
                    'user'   => Session::get('user_id'),
                    'motivo' => 'Desincorporación del bien',
                ]
            );

            $this->logAudit('DELETE', 'bienes', $id);
            Database::commit();
            $this->json([
                'success' => true,
                'message' => 'Desincorporación registrada en estado pendiente. Espera aprobación de otro usuario autorizado.',
            ]);
        } catch (\Exception $e) {
            Database::rollBack();
            \App\Core\Logger::error('Error al desincorporar bien', [
                'exception' => $e->getMessage(),
                'bien_id'   => $id,
            ]);
            $this->json(['error' => 'Error al desincorporar el bien'], 500);
        }
    }

    /**
     * Validar si un número de bien ya existe (AJAX)
     */
    public function validarNumero(): void
    {
        $nro = $this->sanitizeInput($this->getInput('nro', ''));
        
        if (empty($nro) || $nro === 'S/N') {
            $this->json(['existe' => false]);
            return;
        }
        
        $existe = Database::fetch(
            "SELECT id_bien FROM bienes WHERE nro_bien_ministerio = :nro AND nro_bien_ministerio != 'S/N'",
            ['nro' => $nro]
        );
        
        $this->json(['existe' => !empty($existe)]);
    }

    /**
     * Validar si un serial ya existe (AJAX)
     */
    public function validarSerial(): void
    {
        $serial = $this->sanitizeInput($this->getInput('serial', ''));
        
        if (empty($serial) || strlen($serial) < 3) {
            $this->json(['existe' => false]);
            return;
        }
        
        $existe = Database::fetch(
            "SELECT id_bien FROM bienes WHERE serial = :serial",
            ['serial' => $serial]
        );
        
        $this->json(['existe' => !empty($existe)]);
    }

    /**
     * Validar si un código de ministerio ya existe (AJAX)
     */
    public function validarCodigoMinisterio(): void
    {
        $codigo = $this->sanitizeInput($this->getInput('codigo', ''));
        
        if (empty($codigo)) {
            $this->json(['existe' => false]);
            return;
        }
        
        $existe = Database::fetch(
            "SELECT id_bien FROM bienes WHERE codigo_ministerio = :codigo",
            ['codigo' => $codigo]
        );
        
        $this->json(['existe' => !empty($existe)]);
    }

    /**
     * Registra una verificación semestral del bien (solo roles autorizados).
     * POST /bienes/:id/verificar-semestral
     */
    public function verificarSemestral(int $id): void
    {
        // Validar ID
        $id = $this->validateRouteId($id);

        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
            return;
        }

        $bien = Database::fetch('SELECT id_bien, nombre FROM bienes WHERE id_bien = :id', ['id' => $id]);
        if (!$bien) { $this->notFound(); return; }

        try {
            Database::beginTransaction();

            Database::query(
                'INSERT INTO verificaciones (bien_id, usuario_id, tipo, observaciones) VALUES (:bien, :user, :tipo, :obs)',
                [
                    'bien' => $id,
                    'user' => (int) Session::get('user_id'),
                    'tipo' => 'semestral',
                    'obs'  => $this->getInput('observaciones', ''),
                ]
            );

            $verId = Database::lastInsertId();
            $this->logAudit('VERIFY', 'verificaciones', $verId, null, ['bien_id' => $id]);

            Database::commit();

            // Notificar por correo al contacto de control si está configurado
            $contact = $_ENV['MAIL_CONTACTO'] ?? 'bienes@mcp.gob.ve';
            $subject = 'Verificación semestral realizada — Bien #' . ($bien['nro_bien_ministerio'] ?? $bien['id_bien']);
            $body    = "Se ha registrado una verificación semestral del bien: " . htmlspecialchars($bien['nombre']) . " (ID: {$id}).";
            try {
                EmailHelper::sendNotification($contact, $subject, nl2br($body), $body);
            } catch (\Throwable $e) {
                Logger::error('Error enviando notificación de verificación', ['error' => $e->getMessage()]);
            }

            Session::setFlash('success', 'Verificación semestral registrada');
            $this->redirect('/bienes/' . $id);
        } catch (\Exception $e) {
            try { Database::rollBack(); } catch (\Exception $ignore) {}
            Logger::error('Error registrando verificación semestral', ['error' => $e->getMessage(), 'bien_id' => $id]);
            Session::setFlash('error', 'Error interno al registrar verificación');
            $this->redirect('/bienes/' . $id);
        }
    }

    /** Mostrar verificaciones de un bien */
    public function verificaciones(int $id): void
    {
        $id = $this->validateRouteId($id);
        $bien = Database::fetch('SELECT id_bien, nombre, nro_bien_ministerio FROM bienes WHERE id_bien = :id', ['id' => $id]);
        if (!$bien) { $this->notFound(); return; }

        $verifs = Database::fetchAll(
            'SELECT v.*, CONCAT(u.primer_nombre, " ", u.primer_apellido) AS usuario_nombre
             FROM verificaciones v
             LEFT JOIN usuarios u ON v.usuario_id = u.id_usuario
             WHERE v.bien_id = :id
             ORDER BY v.fecha_verificacion DESC',
            ['id' => $id]
        );

        $this->title = 'Verificaciones - ' . htmlspecialchars($bien['nombre']);
        $this->renderWithLayout('bien/verificaciones', compact('bien', 'verifs') + ['verificaciones' => $verifs]);
    }

    // ─── Helpers privados ────────────────────────────────────────────────────

    private function uploadImagen(array $file, int $bienId): ?string
    {
        // Validate file upload
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        // Check file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Verify MIME type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($realMime, $allowed, true)) {
            return null;
        }

        // Double-check with getimagesize
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return null;
        }

        // Generate secure filename
        $ext = match ($realMime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };
        
        // Use secure random filename to prevent path traversal
        $name = 'bien_' . $bienId . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dir  = dirname(__DIR__, 2) . '/uploads/bienes/';
        
        // Create directory with proper permissions
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return null;
            }
        }

        // Create .htaccess for security
        $htaccess = $dir . '.htaccess';
        if (!file_exists($htaccess)) {
            $htaccessContent = "RemoveHandler .php .phtml .php3 .php4 .php5 .phtml .pl .py .jsp .asp .sh .cgi\n";
            $htaccessContent .= "php_flag engine off\n";
            $htaccessContent .= "Options -ExecCGI\n";
            $htaccessContent .= "AddType text/plain .php .phtml .php3 .php4 .php5 .phtml .pl .py .jsp .asp .sh .cgi\n";
            file_put_contents($htaccess, $htaccessContent);
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
            return 'bienes/' . $name;
        }
        
        return null;
    }

    private function uploadResponsableFoto(array $file, int $bienId): ?string
    {
        // Validate file upload
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        // Check file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Verify MIME type using finfo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $realMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($realMime, $allowed, true)) {
            return null;
        }

        // Double-check with getimagesize
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return null;
        }

        // Generate secure filename
        $ext = match ($realMime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };
        
        $name = 'responsable_' . $bienId . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dir  = dirname(__DIR__, 2) . '/uploads/responsables/';
        
        // Create directory with proper permissions
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return null;
            }
        }

        // Create .htaccess for security
        $htaccess = $dir . '.htaccess';
        if (!file_exists($htaccess)) {
            $htaccessContent = "RemoveHandler .php .phtml .php3 .php4 .php5 .phtml .pl .py .jsp .asp .sh .cgi\n";
            $htaccessContent .= "php_flag engine off\n";
            $htaccessContent .= "Options -ExecCGI\n";
            $htaccessContent .= "AddType text/plain .php .phtml .php3 .php4 .php5 .phtml .pl .py .jsp .asp .sh .cgi\n";
            file_put_contents($htaccess, $htaccessContent);
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $dir . $name)) {
            return 'responsables/' . $name;
        }
        
        return null;
    }

    /**
     * Genera código SUDEBIP único usando FOR UPDATE para evitar race conditions.
     * Debe llamarse dentro de una transacción activa.
     * Formato: BN-YYYY-NNNNNN
     */
    private function generarCodigoSudebip(): string
    {
        $year = date('Y');
        $row = Database::fetch(
            "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(codigo_sudebip, '-', -1) AS UNSIGNED)), 0) + 1 AS seq
             FROM bienes WHERE codigo_sudebip LIKE :p FOR UPDATE",
            ['p' => "BN-{$year}-%"]
        );
        return sprintf('BN-%s-%06d', $year, $row['seq'] ?? 1);
    }

    /**
     * Genera código interno (Publicación 9): TIPO-EDIFICIO-PISO-SEQ
     */
    private function generarCodigoInterno(string $idTipo, string $idArea, ?array $area = null): string
    {
        $tipo = Database::fetch("SELECT codigo FROM tipos_bien WHERE id_tipo = :id", ['id' => $idTipo]);

        if ($area === null) {
            $area = Database::fetch("SELECT edificio, piso FROM areas WHERE id_area = :id", ['id' => $idArea]);
        }

        $prefijo = ($tipo['codigo'] ?? '00') . '-' .
                   strtoupper(substr($area['edificio'] ?? 'X', 0, 3)) . '-' .
                   ($area['piso'] ?? '0');

        $row = Database::fetch(
            "SELECT COUNT(*) + 1 AS seq FROM bienes WHERE codigo_interno LIKE :p",
            ['p' => $prefijo . '-%']
        );
        return $prefijo . '-' . sprintf('%04d', $row['seq'] ?? 1);
    }

    private function validateBienData(array $data): array
    {
        $errors = [];
        
        // Nombre: requerido
        $nombre = trim($data['nombre'] ?? '');
        if (empty($nombre)) {
            $errors['nombre'] = 'El nombre del bien es obligatorio';
        } elseif (strlen($nombre) < 3) {
            $errors['nombre'] = 'El nombre debe tener al menos 3 caracteres';
        }
        
        // Clasificación: requerida
        if (empty($data['id_tipo'])) {
            $errors['id_tipo'] = 'Debe seleccionar una clasificación';
        }
        
        // Área: requerida
        if (empty($data['id_area'])) {
            $errors['id_area'] = 'Debe seleccionar un área';
        }
        
        // Estado: requerido
        if (empty($data['id_estado'])) {
            $errors['id_estado'] = 'Debe seleccionar un estado';
        }
        
        // Nro. de Bien: validar formato si se proporciona
        $esSn = !empty($data['es_sn']) && $data['es_sn'] === '1';
        if (!$esSn && !empty($data['nro_bien_ministerio'])) {
            $nroBien = trim($data['nro_bien_ministerio']);
            // Formato: 6 a 10 caracteres alfanuméricos
            if (!preg_match('/^[A-Za-z0-9]{6,10}$/', $nroBien)) {
                $errors['nro_bien_ministerio'] = 'Formato inválido. Debe ser 6 a 10 caracteres alfanuméricos (letras y números).';
            } else {
                // Verificar duplicados
                $existe = Database::fetch(
                    "SELECT id_bien FROM bienes WHERE nro_bien_ministerio = :nro AND nro_bien_ministerio != 'S/N'",
                    ['nro' => $nroBien]
                );
                if ($existe) {
                    $errors['nro_bien_ministerio'] = 'Este número de bien ya está registrado';
                }
            }
        }

        // Código Ministerio: validar duplicados si se ingresa
        if (!empty($data['codigo_ministerio'])) {
            $codigo = trim($data['codigo_ministerio']);
            $existeCodigo = Database::fetch(
                "SELECT id_bien FROM bienes WHERE codigo_ministerio = :codigo",
                ['codigo' => $codigo]
            );
            if ($existeCodigo) {
                $errors['codigo_ministerio'] = 'Este código de ministerio ya está registrado';
            }
        }

        // Serial: validar duplicados si se ingresa
        if (!empty($data['serial'])) {
            $serial = trim($data['serial']);
            $existeSerial = Database::fetch(
                "SELECT id_bien FROM bienes WHERE serial = :serial",
                ['serial' => $serial]
            );
            if ($existeSerial) {
                $errors['serial'] = 'Este serial ya está registrado en otro bien';
            }
        }

        return $errors;
    }

    private function registrarMovimiento(int $bienId, string $tipo, ?int $origen, ?int $destino): void
    {
        Database::query(
            "INSERT INTO movimientos
                (bien_id, tipo_movimiento, area_origen_id, area_destino_id,
                 usuario_solicita_id, usuario_aprueba_id, fecha_aprobacion, motivo, estado)
             VALUES (:bien, :tipo, :origen, :destino, :usuario, NULL, NULL, :motivo, 'pendiente')",
            [
                'bien'    => $bienId,
                'tipo'    => $tipo,
                'origen'  => $origen,
                'destino' => $destino,
                'usuario' => Session::get('user_id'),
                 'motivo'  => 'Incorporación inicial del bien',
            ]
        );
    }

    // ── EXPORTACIÓN A EXCEL ──────────────────────────────────────────────────

    public function exportExcel(): void
    {
        $bienes = Database::fetchAll(
            "SELECT b.*, tb.codigo AS tipo_codigo, tb.nombre AS tipo_nombre,
                    e.nombre AS estado_nombre,
                    CONCAT_WS(' / ', a.edificio, a.piso, a.nombre_area) AS area_completa
             FROM bienes b
             LEFT JOIN tipos_bien tb ON b.id_tipo = tb.id_tipo
             LEFT JOIN estados e ON b.id_estado = e.id_estado
             LEFT JOIN areas a ON b.id_area = a.id_area
             ORDER BY b.codigo_sudebip"
        );

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventario');

        $headers = [
            'Código SUDEBIP', 'Código Interno', 'Cód. Ministerio', 'Nro. Bien Min.',
            'Nombre', 'Descripción', 'Serial', 'Marca', 'Modelo', 'Color',
            'Año Fabricación', 'Cantidad', 'Condición Inicial', 'Clasificación',
            'Estado', 'Área / Ubicación', 'CIN Edificio', 'CIN Piso',
            'CIN Departamento', 'CIN Oficina', 'CIN Posición',
            'Nro. Factura', 'Fecha Adquisición', 'Valor Inicial (Bs.)',
            'Valor Residual (Bs.)', 'Vida Útil (años)', 'Responsable', 'Cédula Resp.',
            'Observaciones'
        ];

        $col = 1;
        foreach ($headers as $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('1E3A5F');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        $row = 2;
        foreach ($bienes as $b) {
            $sheet->setCellValue('A' . $row, $b['codigo_sudebip'] ?? '');
            $sheet->setCellValue('B' . $row, $b['codigo_interno'] ?? '');
            $sheet->setCellValue('C' . $row, $b['codigo_ministerio'] ?? '');
            $sheet->setCellValue('D' . $row, $b['nro_bien_ministerio'] ?? '');
            $sheet->setCellValue('E' . $row, $b['nombre'] ?? '');
            $sheet->setCellValue('F' . $row, $b['descripcion'] ?? '');
            $sheet->setCellValue('G' . $row, $b['serial'] ?? '');
            $sheet->setCellValue('H' . $row, $b['marca'] ?? '');
            $sheet->setCellValue('I' . $row, $b['modelo'] ?? '');
            $sheet->setCellValue('J' . $row, $b['color'] ?? '');
            $sheet->setCellValue('K' . $row, $b['anio_fabricacion'] ?? '');
            $sheet->setCellValue('L' . $row, $b['cantidad'] ?? 1);
            $sheet->setCellValue('M' . $row, $b['condicion_inicial'] ?? '');
            $sheet->setCellValue('N' . $row, ($b['tipo_codigo'] ?? '') . ' - ' . ($b['tipo_nombre'] ?? ''));
            $sheet->setCellValue('O' . $row, $b['estado_nombre'] ?? '');
            $sheet->setCellValue('P' . $row, $b['area_completa'] ?? '');
            $sheet->setCellValue('Q' . $row, $b['cin_edificio'] ?? '');
            $sheet->setCellValue('R' . $row, $b['cin_piso'] ?? '');
            $sheet->setCellValue('S' . $row, $b['cin_departamento'] ?? '');
            $sheet->setCellValue('T' . $row, $b['cin_oficina'] ?? '');
            $sheet->setCellValue('U' . $row, $b['cin_posicion'] ?? '');
            $sheet->setCellValue('V' . $row, $b['numero_factura'] ?? '');
            $sheet->setCellValue('W' . $row, $b['fecha_adquisicion'] ?? '');
            $sheet->setCellValue('X' . $row, $b['valor_inicial'] ?? 0);
            $sheet->setCellValue('Y' . $row, $b['valor_residual'] ?? 0);
            $sheet->setCellValue('Z' . $row, $b['vida_util_anos'] ?? 10);
            $sheet->setCellValue('AA' . $row, $b['responsable_nombre'] ?? '');
            $sheet->setCellValue('AB' . $row, $b['responsable_cedula'] ?? '');
            $sheet->setCellValue('AC' . $row, $b['observaciones'] ?? '');
            $row++;
        }

        for ($i = 1; $i <= count($headers); $i++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="inventario_' . date('Ymd_His') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ── PLANTILLA PARA IMPORTACIÓN ───────────────────────────────────────────

    public function downloadTemplate(): void
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Plantilla Importación');

        $tipos = Database::fetchAll("SELECT codigo, nombre FROM tipos_bien WHERE activo = TRUE ORDER BY codigo");
        $estados = Database::fetchAll("SELECT id_estado, nombre FROM estados ORDER BY id_estado");
        $areas = Database::fetchAll("SELECT id_area, CONCAT(edificio,' / ',piso,' / ',nombre_area) AS nombre FROM areas WHERE activa = TRUE ORDER BY edificio, piso, nombre_area");

        $headers = [
            'Cód. Ministerio*', 'Nro. Bien Min.', 'Nombre*', 'Descripción',
            'Serial', 'Marca', 'Modelo', 'Color', 'Año Fabricación',
            'Cantidad', 'Condición Inicial', 'Cód. Clasificación*', 'Estado*',
            'Área (ID)*', 'CIN Edificio', 'CIN Piso', 'CIN Departamento',
            'CIN Oficina', 'CIN Posición', 'Nro. Factura', 'Fecha Adquisición',
            'Valor Inicial (Bs.)', 'Vida Útil (años)', 'Responsable Nombre',
            'Responsable Cédula', 'Observaciones'
        ];

        $col = 1;
        foreach ($headers as $header) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($cell)->getFill()->getStartColor()->setRGB('1E3A5F');
            $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FFFFFF');
            $col++;
        }

        $row = 2;
        foreach ($tipos as $t) {
            $sheet->setCellValue('L' . $row, $t['codigo'] . ' - ' . $t['nombre']);
            $row++;
        }
        $row = 2;
        foreach ($estados as $e) {
            $sheet->setCellValue('M' . $row, $e['id_estado'] . ' - ' . $e['nombre']);
            $row++;
        }
        $row = 2;
        foreach ($areas as $a) {
            $sheet->setCellValue('N' . $row, $a['id_area'] . ' - ' . $a['nombre']);
            $row++;
        }

        $refSheet = $spreadsheet->createSheet('Referencias');
        $refSheet->setCellValue('A1', 'Códigos de Clasificación (columna L)');
        $refSheet->getStyle('A1')->getFont()->setBold(true);
        $r = 2;
        foreach ($tipos as $t) {
            $refSheet->setCellValue('A' . $r, $t['codigo']);
            $refSheet->setCellValue('B' . $r, $t['nombre']);
            $r++;
        }
        $r++;
        $refSheet->setCellValue('A' . $r, 'Estados (columna M)');
        $refSheet->getStyle('A' . $r)->getFont()->setBold(true);
        $r++;
        foreach ($estados as $e) {
            $refSheet->setCellValue('A' . $r, $e['id_estado']);
            $refSheet->setCellValue('B' . $r, $e['nombre']);
            $r++;
        }
        $r++;
        $refSheet->setCellValue('A' . $r, 'Áreas (columna N)');
        $refSheet->getStyle('A' . $r)->getFont()->setBold(true);
        $r++;
        foreach ($areas as $a) {
            $refSheet->setCellValue('A' . $r, $a['id_area']);
            $refSheet->setCellValue('B' . $r, $a['nombre']);
            $r++;
        }

        $refSheet->getColumnDimension('A')->setAutoSize(true);
        $refSheet->getColumnDimension('B')->setAutoSize(true);

        for ($i = 1; $i <= count($headers); $i++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="plantilla_importacion_bienes.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // ── IMPORTACIÓN MASIVA ───────────────────────────────────────────────────

    public function importExcel(): void
    {
        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }

        if (empty($_FILES['archivo_excel']['tmp_name'])) {
            $this->json(['error' => 'No se proporcionó ningún archivo'], 400);
            return;
        }

        $file = $_FILES['archivo_excel'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls'])) {
            $this->json(['error' => 'Formato no válido. Use archivos .xlsx o .xls'], 400);
            return;
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            $this->json(['error' => 'El archivo excede 10 MB'], 400);
            return;
        }

        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) < 2) {
                $this->json(['error' => 'El archivo está vacío o no tiene datos'], 400);
                return;
            }

            $tiposMap = [];
            $tipos = Database::fetchAll("SELECT id_tipo, codigo FROM tipos_bien WHERE activo = TRUE");
            foreach ($tipos as $t) {
                $code = trim(explode(' - ', $t['codigo'])[0]);
                $tiposMap[$code] = $t['id_tipo'];
                $tiposMap[strtolower($code)] = $t['id_tipo'];
            }

            $estadosMap = [];
            $estados = Database::fetchAll("SELECT id_estado, nombre FROM estados");
            foreach ($estados as $e) {
                $estadosMap[strtolower(trim($e['nombre']))] = $e['id_estado'];
                $estadosMap[(string)$e['id_estado']] = $e['id_estado'];
            }

            $areasMap = [];
            $areas = Database::fetchAll("SELECT id_area FROM areas WHERE activa = TRUE");
            foreach ($areas as $a) {
                $areasMap[(string)$a['id_area']] = $a['id_area'];
            }

            $insertados = 0;
            $actualizados = 0;
            $errores = [];
            $headers = array_map('strtolower', array_map('trim', $rows[0]));
            unset($rows[0]);

            Database::beginTransaction();

            foreach ($rows as $idx => $row) {
                $linea = $idx + 2;
                $data = array_combine($headers, $row);

                $codMinisterio = trim($data['cód. ministerio*'] ?? $data['cód. ministerio'] ?? $data['codigo_ministerio'] ?? '');
                $nombre = trim($data['nombre*'] ?? $data['nombre'] ?? '');

                if (empty($codMinisterio) || empty($nombre)) {
                    $errores[] = "Línea {$linea}: Código ministerio y Nombre son obligatorios";
                    continue;
                }

                $tipoRaw = trim($data['cód. clasificación*'] ?? $data['cód. clasificación'] ?? $data['clasificacion'] ?? '');
                $tipoCode = explode(' - ', $tipoRaw)[0];
                $idTipo = $tiposMap[$tipoCode] ?? $tiposMap[strtolower($tipoCode)] ?? null;

                $estadoRaw = trim($data['estado*'] ?? $data['estado'] ?? '');
                $estadoId = $estadosMap[strtolower($estadoRaw)] ?? $estadosMap[trim($estadoRaw)] ?? null;

                $areaRaw = trim($data['área (id)*'] ?? $data['area (id)'] ?? $data['area'] ?? '');
                $areaId = $areasMap[trim(explode(' - ', $areaRaw)[0])] ?? null;

                if (!$idTipo) {
                    $errores[] = "Línea {$linea}: Clasificación no válida '{$tipoRaw}'";
                    continue;
                }
                if (!$estadoId) {
                    $errores[] = "Línea {$linea}: Estado no válido '{$estadoRaw}'";
                    continue;
                }
                if (!$areaId) {
                    $errores[] = "Línea {$linea}: Área no válida '{$areaRaw}'";
                    continue;
                }

                $existe = Database::fetch(
                    "SELECT id_bien FROM bienes WHERE codigo_ministerio = :cod",
                    ['cod' => $codMinisterio]
                );

                $nroBien = trim($data['nro. bien min.'] ?? $data['nro_bien_ministerio'] ?? '');
                $esSn = ($nroBien === 'S/N' || $nroBien === '');
                $nroFinal = $esSn ? 'S/N' : $nroBien;

                $valorInicial = floatval($data['valor inicial (bs.)'] ?? $data['valor_inicial'] ?? 0);
                $vidaUtil = intval($data['vida útil (años)'] ?? $data['vida_util_anos'] ?? 10);

                $params = [
                    'cod_min'          => $codMinisterio,
                    'nro_bien'         => $nroFinal,
                    'es_sn'            => $esSn ? 1 : 0,
                    'nombre'           => $nombre,
                    'descripcion'      => trim($data['descripción'] ?? $data['descripcion'] ?? ''),
                    'serial'           => trim($data['serial'] ?? ''),
                    'marca'            => trim($data['marca'] ?? ''),
                    'modelo'           => trim($data['modelo'] ?? ''),
                    'color'            => trim($data['color'] ?? ''),
                    'anio_fab'         => !empty($data['año fabricación']) ? intval($data['año fabricación']) : null,
                    'cantidad'         => !empty($data['cantidad']) ? intval($data['cantidad']) : 1,
                    'condicion'        => trim($data['condición inicial'] ?? $data['condicion_inicial'] ?? '') ?: null,
                    'id_tipo'          => $idTipo,
                    'id_estado'        => $estadoId,
                    'id_area'          => $areaId,
                    'nro_factura'      => trim($data['nro. factura'] ?? $data['numero_factura'] ?? ''),
                    'fecha_adq'        => trim($data['fecha adquisición'] ?? $data['fecha_adquisicion'] ?? '') ?: null,
                    'valor_inicial'    => $valorInicial,
                    'valor_residual'   => floatval($data['valor residual'] ?? $data['valor_residual'] ?? 0),
                    'vida_util'        => $vidaUtil,
                    'responsable_nom'  => trim($data['responsable nombre'] ?? $data['responsable_nombre'] ?? ''),
                    'responsable_ced'  => trim($data['responsable cédula'] ?? $data['responsable_cedula'] ?? ''),
                    'observaciones'    => trim($data['observaciones'] ?? ''),
                    'cin_edificio'     => trim($data['cin edificio'] ?? $data['cin_edificio'] ?? ''),
                    'cin_piso'         => trim($data['cin piso'] ?? $data['cin_piso'] ?? ''),
                    'cin_departamento' => trim($data['cin departamento'] ?? $data['cin_departamento'] ?? ''),
                    'cin_oficina'      => trim($data['cin oficina'] ?? $data['cin_oficina'] ?? ''),
                    'cin_posicion'     => trim($data['cin posición'] ?? $data['cin_posicion'] ?? ''),
                ];

                if ($existe) {
                    Database::query(
                        "UPDATE bienes SET
                            nro_bien_ministerio=:nro, es_sn=:es_sn, nombre=:nom, descripcion=:desc,
                            serial=:serial, marca=:marca, modelo=:modelo, color=:color,
                            anio_fabricacion=:anio, cantidad=:cant, condicion_inicial=:cond,
                            id_tipo=:tipo, id_estado=:estado, id_area=:area,
                            nro_factura=:fact, fecha_adquisicion=:fecha, valor_inicial=:val,
                            valor_residual=:vres, vida_util_anos=:vida,
                            responsable_nombre=:rnom, responsable_cedula=:rced,
                            observaciones=:obs, cin_edificio=:cie, cin_piso=:cip,
                            cin_departamento=:cid, cin_oficina=:cio, cin_posicion=:cipos
                         WHERE codigo_ministerio=:cod_min",
                        [
                            'nro'     => $params['nro_bien'],
                            'es_sn'   => $params['es_sn'],
                            'nom'     => $params['nombre'],
                            'desc'    => $params['descripcion'],
                            'serial'  => $params['serial'],
                            'marca'   => $params['marca'],
                            'modelo'  => $params['modelo'],
                            'color'   => $params['color'],
                            'anio'    => $params['anio_fab'],
                            'cant'    => $params['cantidad'],
                            'cond'    => $params['condicion'],
                            'tipo'    => $params['id_tipo'],
                            'estado'  => $params['id_estado'],
                            'area'    => $params['id_area'],
                            'fact'    => $params['nro_factura'],
                            'fecha'   => $params['fecha_adq'],
                            'val'     => $params['valor_inicial'],
                            'vres'    => $params['valor_residual'],
                            'vida'    => $params['vida_util'],
                            'rnom'    => $params['responsable_nom'],
                            'rced'    => $params['responsable_ced'],
                            'obs'     => $params['observaciones'],
                            'cie'     => $params['cin_edificio'],
                            'cip'     => $params['cin_piso'],
                            'cid'     => $params['cin_departamento'],
                            'cio'     => $params['cin_oficina'],
                            'cipos'   => $params['cin_posicion'],
                            'cod_min' => $params['cod_min'],
                        ]
                    );
                    $actualizados++;
                } else {
                    $area = Database::fetch("SELECT edificio, piso, nombre_area FROM areas WHERE id_area = :id", ['id' => $areaId]);
                    $codigoInterno = $this->generarCodigoInterno(
                        $params['id_tipo'], $params['id_area'], $area
                    );

                    Database::query(
                        "INSERT INTO bienes (
                            codigo_sudebip, codigo_interno, codigo_ministerio, nro_bien_ministerio, es_sn,
                            serial, nombre, descripcion, marca, modelo, color, anio_fabricacion,
                            numero_factura, fecha_adquisicion, valor_inicial, valor_residual,
                            vida_util_anos, id_estado, id_tipo, id_area,
                            responsable_nombre, responsable_cedula, cantidad, observaciones, condicion_inicial,
                            cin_edificio, cin_piso, cin_departamento, cin_oficina, cin_posicion
                        ) VALUES (
                            :codigo_sudebip, :codigo_interno, :codigo_ministerio, :nro_bien_ministerio, :es_sn,
                            :serial, :nombre, :descripcion, :marca, :modelo, :color, :anio_fabricacion,
                            :numero_factura, :fecha_adquisicion, :valor_inicial, :valor_residual,
                            :vida_util_anos, :id_estado, :id_tipo, :id_area,
                            :responsable_nombre, :responsable_cedula, :cantidad, :observaciones, :condicion_inicial,
                            :cin_edificio, :cin_piso, :cin_departamento, :cin_oficina, :cin_posicion
                        )",
                        [
                            'codigo_sudebip'      => $this->generarCodigoSudebip(),
                            'codigo_interno'      => $codigoInterno,
                            'codigo_ministerio'   => $params['cod_min'],
                            'nro_bien_ministerio' => $params['nro_bien'],
                            'es_sn'               => $params['es_sn'],
                            'serial'              => $params['serial'],
                            'nombre'              => $params['nombre'],
                            'descripcion'         => $params['descripcion'],
                            'marca'               => $params['marca'],
                            'modelo'              => $params['modelo'],
                            'color'               => $params['color'],
                            'anio_fabricacion'    => $params['anio_fab'],
                            'numero_factura'      => $params['nro_factura'],
                            'fecha_adquisicion'   => $params['fecha_adq'],
                            'valor_inicial'       => $params['valor_inicial'],
                            'valor_residual'      => $params['valor_residual'],
                            'vida_util_anos'      => $params['vida_util'],
                            'id_estado'           => $params['id_estado'],
                            'id_tipo'             => $params['id_tipo'],
                            'id_area'             => $params['id_area'],
                            'responsable_nombre'  => $params['responsable_nom'],
                            'responsable_cedula'  => $params['responsable_ced'],
                            'cantidad'            => $params['cantidad'],
                            'observaciones'       => $params['observaciones'],
                            'condicion_inicial'   => $params['condicion'],
                            'cin_edificio'        => $params['cin_edificio'],
                            'cin_piso'            => $params['cin_piso'],
                            'cin_departamento'    => $params['cin_departamento'],
                            'cin_oficina'         => $params['cin_oficina'],
                            'cin_posicion'        => $params['cin_posicion'],
                        ]
                    );
                    $bienId = Database::lastInsertId();
                    $this->registrarMovimiento($bienId, 'incorporacion', null, $areaId);
                    $insertados++;
                }
            }

            Database::commit();

            $this->logAudit('IMPORT', 'bienes', 0, [
                'insertados' => $insertados,
                'actualizados' => $actualizados,
                'errores' => count($errores),
            ]);

            $this->json([
                'success'    => true,
                'insertados' => $insertados,
                'actualizados' => $actualizados,
                'errores'    => $errores,
                'total'      => $insertados + $actualizados + count($errores),
            ]);

        } catch (\Exception $e) {
            Database::rollBack();
            Logger::error('import_excel_error', ['message' => $e->getMessage()]);
            $this->json(['error' => 'Error al procesar el archivo: ' . $e->getMessage()], 500);
        }
    }

}
