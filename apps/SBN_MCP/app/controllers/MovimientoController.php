<?php
/**
 * =============================================================================
 * CONTROLADOR: MOVIMIENTOS Y ACTAS
 * =============================================================================
 * 
 * Gestiona el ciclo de vida de los movimientos de bienes:
 * - Incorporación: Entrada de nuevos bienes al sistema
 * - Traslado: Cambio de ubicación entre áreas
 * - Desincorporación: Baja de bienes (chatarra, donación, etc.)
 * 
 * Flujo de aprobación:
 * 1. Usuario con permiso (gerencia_bn, admin) crea el acta
 * 2. El acta queda en estado "pendiente"
 * 3. Segundo usuario con permisos aprueba/rechaza
 * 4. Al aprobarse, se actualiza la ubicación del bien automáticamente
 * 
 * Seguridad:
 * - Solo administrador y gerencia_bn pueden crear actas
 * - No se puede aprobar un acta propia (separación de funciones)
 * - Todos los cambios se registran en auditoría
 * 
 * @package App\Controllers
 * @author  MCP Development Team
 * @version 1.0.0
 * =============================================================================
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Core\Session;
use App\Helpers\AuditTrait;
use App\Helpers\SecurityHelper;

// Forzar carga de TCPDF si el autoloader tiene problemas
if (file_exists(ROOT_PATH . '/vendor/tecnickcom/tcpdf/tcpdf.php')) {
    require_once ROOT_PATH . '/vendor/tecnickcom/tcpdf/tcpdf.php';
}

class MovimientoController extends Controller
{
    use AuditTrait;
    /** Listado de movimientos con filtros */
    public function index(): void
    {
        $tipo   = $this->getInput('tipo', '');
        $estado = $this->getInput('estado', '');
        $search = $this->getInput('search', '');
        $page   = max(1, (int)$this->getInput('page', '1'));
        $perPage = 20;
        $offset  = ($page - 1) * $perPage;

        $where  = "WHERE 1=1";
        $params = [];

        if (!empty($tipo))   { $where .= " AND m.tipo_movimiento = :tipo";   $params['tipo']   = $tipo; }
        if (!empty($estado)) { $where .= " AND m.estado = :estado";          $params['estado'] = $estado; }
        if (!empty($search)) {
            $where .= " AND (b.nombre LIKE :s1 OR b.codigo_interno LIKE :s2 OR b.nro_bien_ministerio LIKE :s3)";
            $s = "%{$search}%";
            $params['s1'] = $s; $params['s2'] = $s; $params['s3'] = $s;
        }

        $countRow = Database::fetch(
            "SELECT COUNT(*) AS n FROM movimientos m
             JOIN bienes b ON m.bien_id = b.id_bien
             {$where}",
            $params
        );
        $total      = (int)($countRow['n'] ?? 0);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page       = min($page, $totalPages);

        $sql = "SELECT m.id_movimiento, m.tipo_movimiento, m.estado, m.fecha_solicitud, m.motivo,
                    b.id_bien, b.nombre AS bien_nombre, b.codigo_interno, b.nro_bien_ministerio, b.es_sn,
                    ao.nombre_area AS area_origen, ad.nombre_area AS area_destino,
                    us.nombre_completo AS usuario_solicita
                FROM movimientos m
                JOIN bienes b ON m.bien_id = b.id_bien
                LEFT JOIN areas ao ON m.area_origen_id = ao.id_area
                LEFT JOIN areas ad ON m.area_destino_id = ad.id_area
                LEFT JOIN usuarios us ON m.usuario_solicita_id = us.id_usuario
                {$where}
                ORDER BY m.fecha_solicitud DESC
                LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $movimientos = Database::fetchAll($sql, $params);

        $this->title = 'Movimientos y Actas';
        $this->renderWithLayout('movimiento/index', compact(
            'movimientos','tipo','estado','search',
            'page','perPage','total','totalPages'
        ));
    }

    /** Detalle de un movimiento / acta */
    public function show(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }

        $movimiento = Database::fetch(
            "SELECT m.*,
                    b.nombre AS bien_nombre, b.codigo_interno, b.nro_bien_ministerio, b.es_sn,
                    b.descripcion AS bien_descripcion, b.marca, b.modelo,
                    ao.nombre_area AS area_origen, ao.edificio AS edif_origen,
                    ad.nombre_area AS area_destino, ad.edificio AS edif_destino,
                    us.nombre_completo AS usuario_solicita,
                    ua.nombre_completo AS usuario_aprueba
            FROM movimientos m
            JOIN bienes b ON m.bien_id = b.id_bien
            LEFT JOIN areas ao ON m.area_origen_id = ao.id_area
            LEFT JOIN areas ad ON m.area_destino_id = ad.id_area
            LEFT JOIN usuarios us ON m.usuario_solicita_id = us.id_usuario
            LEFT JOIN usuarios ua ON m.usuario_aprueba_id = ua.id_usuario
            WHERE m.id_movimiento = :id",
            ['id' => $id]
        );

        if (!$movimiento) { $this->notFound(); return; }

        $this->title = 'Acta de ' . ucfirst($movimiento['tipo_movimiento']);
        $this->renderWithLayout('movimiento/show', compact('movimiento'));
    }

    /** Formulario nueva acta (solo gerencia_bn / admin) */
    public function create(): void
    {
        $bienes = Database::fetchAll(
            "SELECT b.id_bien, b.nombre, b.codigo_interno, b.nro_bien_ministerio, b.es_sn,
                    b.id_area, a.nombre_area, e.nombre AS estado_nombre
            FROM bienes b
            JOIN estados e ON b.id_estado = e.id_estado
            LEFT JOIN areas a ON b.id_area = a.id_area
            WHERE e.es_baja = FALSE
            ORDER BY b.codigo_interno"
        );

        $areas = Database::fetchAll(
            "SELECT * FROM areas WHERE activa = TRUE ORDER BY edificio, piso, nombre_area"
        );

        $this->title = 'Nueva Acta';
        $this->renderWithLayout('movimiento/create', [
            'csrf_token' => $this->generateCSRFToken(),
            'bienes'     => $bienes,
            'areas'      => $areas,
        ]);
    }

    /** Previsualizar acta sin guardar */
    public function preview(): void
    {
        $tipo    = $this->getInput('tipo_movimiento');
        $bienId  = (int) $this->getInput('bien_id');
        $origenId = $this->getInput('area_origen_id') ?: null;
        $destinoId = $this->getInput('area_destino_id') ?: null;
        $motivo  = trim($this->getInput('motivo'));
        $respCi      = trim($this->getInput('responsable_ci') ?? '');
        $respNombre  = trim($this->getInput('responsable_nombre') ?? '');
        $respApellido = trim($this->getInput('responsable_apellido') ?? '');

        $bien = Database::fetch("SELECT * FROM bienes WHERE id_bien = :id", ['id' => $bienId]);
        if (!$bien) {
            $this->json(['error' => 'Bien no encontrado'], 404);
            return;
        }

        $origen = $origenId ? Database::fetch("SELECT * FROM areas WHERE id_area = :id", ['id' => $origenId]) : null;
        $destino = $destinoId ? Database::fetch("SELECT * FROM areas WHERE id_area = :id", ['id' => $destinoId]) : null;
        $usuario = Database::fetch("SELECT * FROM usuarios WHERE id_usuario = :id", ['id' => Session::get('user_id')]);

        $fecha = date('d/m/Y');
        $hora = date('H:i');
        $nroActa = date('Ymd') . "-" . str_pad((string)$bienId, 4, '0', STR_PAD_LEFT);

        $responsable = [
            'ci'      => $respCi,
            'nombre'  => $respNombre,
            'apellido' => $respApellido,
        ];

        $html = $this->generarHtmlActa($tipo, $bien, $origen, $destino, $usuario, $fecha, $hora, $nroActa, $motivo, $responsable);

        $this->json(['success' => true, 'html' => trim($html)]);
    }

    /**
     * Genera el HTML del acta con el formato institucional oficial.
     * Aplicable para incorporación, desincorporación, traslado y asignación.
     */
    private function generarHtmlActa(
        string $tipo,
        array $bien,
        ?array $origen,
        ?array $destino,
        ?array $usuario,
        string $fecha,
        string $hora,
        string $nroActa,
        string $motivo,
        array $responsableDatos = []
    ): string {
        // Mapeo de títulos según tipo
        $titulos = [
            'incorporacion'    => 'ACTA DE INCORPORACIÓN',
            'desincorporacion' => 'ACTA DE DESINCORPORACIÓN',
            'traslado'         => 'ACTA DE TRASLADO',
            'asignacion'       => 'ACTA DE ASIGNACIÓN',
        ];
        $tituloActa = $titulos[$tipo] ?? 'ACTA DE MOVIMIENTO';

        // Subtítulos para sección 2 según tipo
        $subTitulosBien = [
            'incorporacion'    => 'DATOS DEL BIEN INCORPORADO',
            'desincorporacion' => 'DATOS DEL BIEN DESINCORPORADO',
            'traslado'         => 'DATOS DEL BIEN TRASLADADO',
            'asignacion'       => 'DATOS DEL BIEN ASIGNADO',
        ];
        $subTituloBien = $subTitulosBien[$tipo] ?? 'DATOS DEL BIEN';

        // Icono del título (cruz azul)
        $iconoTitulo = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="11" fill="#1e40af"/>
            <path d="M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z" fill="#fff"/>
        </svg>';

        // Determinar área según tipo
        $areaLabel = 'Área / Servicio:';
        $areaValor = '';
        if ($tipo === 'traslado' && $destino) {
            $areaValor = htmlspecialchars($destino['nombre_area']);
            if (!empty($destino['edificio'])) {
                $areaValor .= ' — ' . htmlspecialchars($destino['edificio']);
            }
        } elseif ($tipo === 'incorporacion' && $origen) {
            $areaValor = htmlspecialchars($origen['nombre_area']);
            if (!empty($origen['edificio'])) {
                $areaValor .= ' — ' . htmlspecialchars($origen['edificio']);
            }
        } elseif ($tipo === 'desincorporacion' && $origen) {
            $areaValor = htmlspecialchars($origen['nombre_area']);
            if (!empty($origen['edificio'])) {
                $areaValor .= ' — ' . htmlspecialchars($origen['edificio']);
            }
        } elseif ($tipo === 'asignacion' && $origen) {
            $areaValor = htmlspecialchars($origen['nombre_area']);
            if (!empty($origen['edificio'])) {
                $areaValor .= ' — ' . htmlspecialchars($origen['edificio']);
            }
        }

        $responsable = $usuario
            ? htmlspecialchars($usuario['nombre_completo'])
            : '________________________';

        // Datos del responsable para asignación directa
        $htmlResponsableEntrega = '';
        if ($tipo === 'asignacion' && !empty($responsableDatos['ci'])) {
            $respCi = htmlspecialchars($responsableDatos['ci']);
            $respNom = htmlspecialchars($responsableDatos['nombre']);
            $respApe = htmlspecialchars($responsableDatos['apellido']);
            $htmlResponsableEntrega = "
            <tr>
                <td colspan='2' style='padding: 6px 0; font-size: 10pt; border-top: 1px solid #e2e8f0; margin-top: 6px;'>
                    <strong>CI del Responsable:</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px;'>{$respCi}</span>
                </td>
            </tr>
            <tr>
                <td colspan='2' style='padding: 6px 0; font-size: 10pt;'>
                    <strong>Nombre y Apellido del Responsable:</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px;'>{$respNom} {$respApe}</span>
                </td>
            </tr>";
        }

        // Motivo / observaciones
        $motivoHtml = nl2br(htmlspecialchars($motivo));

        // Fila de origen/destino para traslados
        $htmlMovimientoExtra = '';
        if ($tipo === 'traslado') {
            $origenNombre = $origen
                ? htmlspecialchars($origen['nombre_area']) . (!empty($origen['edificio']) ? ' — ' . htmlspecialchars($origen['edificio']) : '')
                : 'No especificada';
            $destinoNombre = $destino
                ? htmlspecialchars($destino['nombre_area']) . (!empty($destino['edificio']) ? ' — ' . htmlspecialchars($destino['edificio']) : '')
                : 'No especificada';
            $htmlMovimientoExtra = "
            <tr>
                <td style='padding:8px 12px; font-weight:700; font-size:10pt; width:35%; background:#eff6ff;'>Área de Origen:</td>
                <td style='padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;'>{$origenNombre}</td>
            </tr>
            <tr>
                <td style='padding:8px 12px; font-weight:700; font-size:10pt; background:#eff6ff;'>Área de Destino:</td>
                <td style='padding:8px 12px; font-size:10pt; border:1px solid #bfdbfe;'>{$destinoNombre}</td>
            </tr>";
        }

        $html = "
<div style='font-family: Arial, Helvetica, sans-serif; max-width: 850px; margin: auto; color: #1e293b; background: #fff;'>

    <!-- ENCABEZADO CON ONDA AZUL/ROJA -->
    <div style='position: relative; width: 100%; height: 120px; overflow: hidden;'>
        <img src='" . BASE . "/img/encabezado_at.png' style='width: 100%; height: 120px; object-fit: cover; display: block;' alt='Encabezado'>
    </div>

    <!-- TÍTULO DEL ACTA -->
    <div style='display: flex; justify-content: space-between; align-items: center; padding: 15px 25px; margin-bottom: 5px;'>
        <div style='display: flex; align-items: center; gap: 10px;'>
            {$iconoTitulo}
            <h2 style='margin: 0; font-size: 14pt; color: #1e40af; font-weight: 800; letter-spacing: 0.02em;'>{$tituloActa}</h2>
        </div>
        <div style='border: 1px solid #93c5fd; border-radius: 20px; padding: 6px 18px; background: #eff6ff;'>
            <span style='font-size: 9pt; font-weight: 700; color: #1e40af;'>Nº de Acta:</span>
            <span style='font-size: 9pt; color: #334155; margin-left: 5px;'>{$nroActa}</span>
        </div>
    </div>

    <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
    <div style='margin: 0 25px 15px 25px;'>
        <div style='background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;'>
            1. INFORMACIÓN GENERAL
        </div>
        <div style='border: 2px solid #3b82f6; border-radius: 0 10px 10px 10px; padding: 15px 20px; background: #f8fafc;'>
            <table width='100%' style='border-collapse: collapse;'>
                <tr>
                    <td style='padding: 6px 0; font-size: 10pt; width: 50%;'>
                        <strong>Fecha:</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 120px; display: inline-block;'>{$fecha}</span>
                    </td>
                    <td style='padding: 6px 0; font-size: 10pt; width: 50%;'>
                        <strong>Hora:</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px; min-width: 80px; display: inline-block;'>{$hora}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='padding: 6px 0; font-size: 10pt;'>
                        <strong>{$areaLabel}</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px;'>{$areaValor}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style='padding: 6px 0; font-size: 10pt;'>
                        <strong>Responsable del Registro:</strong> <span style='border-bottom: 1px solid #94a3b8; padding-bottom: 1px;'>{$responsable}</span>
                    </td>
                </tr>
                {$htmlResponsableEntrega}
            </table>
        </div>
    </div>

    <!-- SECCIÓN 2: DATOS DEL BIEN -->
    <div style='margin: 0 25px 15px 25px;'>
        <div style='background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;'>
            2. {$subTituloBien}
        </div>
        <table width='100%' style='border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt;'>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; width: 35%; background: #eff6ff; border: 1px solid #bfdbfe;'>Código / Nº de Bien:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>" . htmlspecialchars($bien['codigo_interno'] ?? '') . "</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Nombre / Descripción:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>" . htmlspecialchars($bien['nombre'] ?? '') . "</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Marca / Modelo:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>" . htmlspecialchars(($bien['marca'] ?? 'N/A') . ' / ' . ($bien['modelo'] ?? 'N/A')) . "</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Serie:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>" . htmlspecialchars($bien['serial'] ?? 'N/A') . "</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Cantidad:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>" . ($bien['cantidad'] ?? 1) . "</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Estado:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>
                    <label style='margin-right: 15px; cursor: default;'>
                        <input type='radio' name='estado_bien' value='nuevo' disabled> Nuevo
                    </label>
                    <label style='margin-right: 15px; cursor: default;'>
                        <input type='radio' name='estado_bien' value='usado' checked disabled> Usado
                    </label>
                    <label style='cursor: default;'>
                        <input type='radio' name='estado_bien' value='reacondicionado' disabled> Reacondicionado
                    </label>
                </td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Fecha de Incorporación:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>{$fecha}</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe;'>Proveedor (si aplica):</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe;'>&nbsp;</td>
            </tr>
            <tr>
                <td style='padding: 8px 12px; font-weight: 700; background: #eff6ff; border: 1px solid #bfdbfe; vertical-align: top;'>Observaciones:</td>
                <td style='padding: 8px 12px; border: 1px solid #bfdbfe; min-height: 60px; vertical-align: top;'>{$motivoHtml}</td>
            </tr>
            {$htmlMovimientoExtra}
        </table>
    </div>

    <!-- SECCIÓN 3: RESPONSABLES -->
    <div style='margin: 20px 25px 15px 25px;'>
        <div style='background: #1e40af; color: #fff; display: inline-block; padding: 5px 18px; border-radius: 5px 5px 0 0; font-size: 10pt; font-weight: 700; letter-spacing: 0.03em;'>
            3. RESPONSABLES
        </div>
        <table width='100%' style='border-collapse: collapse; border: 1px solid #bfdbfe; font-size: 10pt; text-align: center;'>
            <thead>
                <tr style='background: #eff6ff;'>
                    <th style='padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;'>Responsable del Área</th>
                    <th style='padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 34%;'>Encargado de Inventario</th>
                    <th style='padding: 10px; border: 1px solid #bfdbfe; font-weight: 700; color: #1e40af; width: 33%;'>Autorizado por</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;'>
                        <div style='font-size: 9pt; color: #64748b; margin-bottom: 5px;'>Firma: ______________________</div>
                        <div style='font-size: 8pt; color: #94a3b8;'>Nombre: ____________________</div>
                    </td>
                    <td style='padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;'>
                        <div style='font-size: 9pt; color: #64748b; margin-bottom: 5px;'>Firma: ______________________</div>
                        <div style='font-size: 8pt; color: #94a3b8;'>Nombre: ____________________</div>
                    </td>
                    <td style='padding: 15px 10px; border: 1px solid #bfdbfe; vertical-align: bottom; height: 80px;'>
                        <div style='font-size: 9pt; color: #64748b; margin-bottom: 5px;'>Firma: ______________________</div>
                        <div style='font-size: 8pt; color: #94a3b8;'>Nombre: ____________________</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PIE DE PÁGINA -->
    <div style='margin: 25px 25px 10px 25px; text-align: center; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;'>
        Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios
    </div>

</div>";

        return $html;
    }

    /** Guardar acta */
    public function store(): void
    {
        if (!$this->verifyCSRFToken($this->getInput('csrf_token'))) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }

        $tipo    = $this->getInput('tipo_movimiento');
        $bienId  = (int) $this->getInput('bien_id');
        $origen  = $this->getInput('area_origen_id') ?: null;
        $destino = $this->getInput('area_destino_id') ?: null;
        $motivo  = trim($this->getInput('motivo'));
        $contenido = $this->getInput('contenido_acta');
        $respCi      = trim($this->getInput('responsable_ci') ?? '');
        $respNombre  = trim($this->getInput('responsable_nombre') ?? '');
        $respApellido = trim($this->getInput('responsable_apellido') ?? '');

        // Whitelist estricta del tipo de movimiento (evita header injection y datos inválidos)
        $tiposValidos = ['incorporacion', 'traslado', 'desincorporacion', 'asignacion'];
        if (!in_array($tipo, $tiposValidos, true)) {
            $this->json(['errors' => ['tipo_movimiento' => 'Tipo de acta inválido']], 400);
            return;
        }

        // Sanitizar HTML del acta (previene XSS almacenado desde el editor)
        $contenido = SecurityHelper::sanitizeRichHtml($contenido);

        $errores = [];
        if (empty($tipo))   $errores['tipo_movimiento'] = 'El tipo de acta es requerido';
        if (empty($bienId)) $errores['bien_id']         = 'Debe seleccionar un bien';
        if (empty($motivo)) $errores['motivo']          = 'El motivo es requerido';

        if ($tipo === 'traslado' && empty($destino)) {
            $errores['area_destino_id'] = 'El área destino es requerida para traslados';
        }

        if ($tipo === 'asignacion') {
            if (empty($respCi))      $errores['responsable_ci']      = 'La CI del responsable es requerida';
            if (empty($respNombre))  $errores['responsable_nombre']  = 'El nombre del responsable es requerido';
            if (empty($respApellido)) $errores['responsable_apellido'] = 'El apellido del responsable es requerido';
        }

        if (!empty($errores)) {
            $this->json(['errors' => $errores], 400);
            return;
        }

        Database::beginTransaction();
        try {
            // Preparar datos JSON del responsable para asignación
            $responsableData = null;
            if ($tipo === 'asignacion' && !empty($respCi)) {
                $responsableData = json_encode([
                    'ci'       => $respCi,
                    'nombre'   => $respNombre,
                    'apellido' => $respApellido,
                ], JSON_UNESCAPED_UNICODE);
            }

            // Si el solicitante es administrador o gerencia_bn, el acta se aprueba automáticamente.
            // Otros roles crean el acta como pendiente para aprobación por separación de funciones.
            $rol = $_SESSION['rol'] ?? '';
            $autoAprobar = in_array($rol, ['administrador', 'gerencia_bn'], true);
            $estado = $autoAprobar ? 'aprobado' : 'pendiente';

            Database::query(
                "INSERT INTO movimientos
                    (bien_id, tipo_movimiento, area_origen_id, area_destino_id,
                    usuario_solicita_id, usuario_aprueba_id, fecha_aprobacion,
                    motivo, contenido_acta, estado)
                VALUES (:bien, :tipo, :origen, :destino, :solicita, :aprueba, :fecha_aprob,
                    :motivo, :contenido, :estado)",
                [
                    'bien'       => $bienId,
                    'tipo'       => $tipo,
                    'origen'     => $origen,
                    'destino'    => $destino,
                    'solicita'   => Session::get('user_id'),
                    'aprueba'    => $autoAprobar ? Session::get('user_id') : null,
                    'fecha_aprob' => $autoAprobar ? date('Y-m-d H:i:s') : null,
                    'motivo'     => $motivo,
                    'contenido'  => $contenido,
                    'estado'     => $estado,
                ]
            );
            $movId = Database::lastInsertId();

            if ($responsableData !== null) {
                try {
                    Database::query(
                        "UPDATE movimientos SET responsable_datos = :rd WHERE id_movimiento = :id",
                        ['rd' => $responsableData, 'id' => $movId]
                    );
                } catch (\Exception $ignore) {}
            }

            // Si se auto-aprobó, aplicar efectos del movimiento sobre el bien inmediatamente
            if ($autoAprobar) {
                $movData = Database::fetch(
                    "SELECT * FROM movimientos WHERE id_movimiento = :id",
                    ['id' => $movId]
                );
                if ($movData) {
                    $this->aplicarEfectosMovimiento($movData);
                }
            }

            Database::commit();
            $this->logAudit('INSERT', 'movimientos', $movId);

            if ($autoAprobar) {
                Logger::security('acta_autoaprobada', [
                    'movimiento_id' => $movId,
                    'user_id'       => Session::get('user_id'),
                    'tipo'          => $tipo,
                ]);
                $this->json([
                    'success'  => true,
                    'message'  => 'Acta creada y aprobada automáticamente.',
                    'redirect' => '/movimientos/' . $movId,
                ]);
            } else {
                Logger::security('acta_creada_pendiente', [
                    'movimiento_id' => $movId,
                    'user_id'       => Session::get('user_id'),
                    'tipo'          => $tipo,
                ]);
                $this->json([
                    'success'  => true,
                    'message'  => 'Acta registrada correctamente. Quedó en estado pendiente hasta que otro usuario autorizado la apruebe.',
                    'redirect' => '/movimientos/' . $movId,
                ]);
            }
        } catch (\Exception $e) {
            Database::rollBack();
            error_log("Error al registrar acta: " . $e->getMessage());
            $this->json(['error' => 'Error interno al registrar el acta.'], 500);
        }
    }

    /** Descargar en formato Word (HTML wrapper) */
    public function downloadWord(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }
        $mov = Database::fetch(
            "SELECT m.*, b.nombre AS bien_nombre, b.codigo_interno, b.nro_bien_ministerio,
                    b.marca, b.modelo, b.serial, b.cantidad, b.descripcion,
                    ao.nombre_area AS area_origen, ao.edificio AS edif_origen,
                    ad.nombre_area AS area_destino, ad.edificio AS edif_destino,
                    us.nombre_completo AS usuario_solicita
             FROM movimientos m
             JOIN bienes b ON m.bien_id = b.id_bien
             LEFT JOIN areas ao ON m.area_origen_id = ao.id_area
             LEFT JOIN areas ad ON m.area_destino_id = ad.id_area
             LEFT JOIN usuarios us ON m.usuario_solicita_id = us.id_usuario
             WHERE m.id_movimiento = :id",
            ['id' => $id]
        );
        if (!$mov) { $this->notFound(); return; }

        $usuario = $mov['usuario_solicita'] ? ['nombre_completo' => $mov['usuario_solicita']] : null;
        $origen  = $mov['area_origen'] ? ['nombre_area' => $mov['area_origen'], 'edificio' => $mov['edif_origen']] : null;
        $destino = $mov['area_destino'] ? ['nombre_area' => $mov['area_destino'], 'edificio' => $mov['edif_destino']] : null;
        $bien = [
            'nombre' => $mov['bien_nombre'], 'codigo_interno' => $mov['codigo_interno'],
            'marca' => $mov['marca'], 'modelo' => $mov['modelo'],
            'serial' => $mov['serial'], 'cantidad' => $mov['cantidad'],
        ];
        $fecha   = date('d/m/Y', strtotime($mov['fecha_solicitud']));
        $hora    = date('H:i', strtotime($mov['fecha_solicitud']));
        $nroActa = date('Ymd', strtotime($mov['fecha_solicitud'])) . "-" . str_pad((string)$id, 4, '0', STR_PAD_LEFT);

        $responsableDatos = !empty($mov['responsable_datos']) ? json_decode($mov['responsable_datos'], true) : [];

        // Preparar imagen del encabezado como JPG con fondo blanco (para Word)
        $headerImgTag = '';
        $encabezadoPath = ROOT_PATH . '/public/img/encabezado_at.png';
        if (file_exists($encabezadoPath) && function_exists('imagecreatefrompng')) {
            $img = @imagecreatefrompng($encabezadoPath);
            if ($img) {
                $w = imagesx($img);
                $h = imagesy($img);
                $white = imagecreatetruecolor($w, $h);
                imagefill($white, 0, 0, imagecolorallocate($white, 255, 255, 255));
                imagecopy($white, $img, 0, 0, 0, 0, $w, $h);
                ob_start();
                imagejpeg($white, null, 95);
                $jpgData = ob_get_clean();
                imagedestroy($img);
                imagedestroy($white);
                $b64 = base64_encode($jpgData);
                $headerImgTag = '<p style="margin:0;text-align:center;line-height:0;"><img src="data:image/jpeg;base64,' . $b64 . '" width="100%" height="110" style="width:100%;height:110px;object-fit:cover;" /></p>';
            }
        }

        $html = $this->generarHtmlWord($mov['tipo_movimiento'], $bien, $origen, $destino, $usuario, $fecha, $hora, $nroActa, $mov['motivo'], $headerImgTag, $responsableDatos);

        header("Content-Type: application/vnd.ms-word; charset=UTF-8");
        header("Content-Disposition: attachment; filename=\"acta_" . $mov['tipo_movimiento'] . "_{$id}.doc\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        echo $html;
        exit;
    }

    /** Descargar en formato PDF */
    public function downloadPdf(int $id): void
    {
        if ($id <= 0) { $this->notFound(); return; }
        $mov = Database::fetch(
            "SELECT m.*, b.nombre AS bien_nombre, b.codigo_interno, b.nro_bien_ministerio,
                    b.marca, b.modelo, b.serial, b.cantidad, b.descripcion,
                    ao.nombre_area AS area_origen, ao.edificio AS edif_origen,
                    ad.nombre_area AS area_destino, ad.edificio AS edif_destino,
                    us.nombre_completo AS usuario_solicita
             FROM movimientos m
             JOIN bienes b ON m.bien_id = b.id_bien
             LEFT JOIN areas ao ON m.area_origen_id = ao.id_area
             LEFT JOIN areas ad ON m.area_destino_id = ad.id_area
             LEFT JOIN usuarios us ON m.usuario_solicita_id = us.id_usuario
             WHERE m.id_movimiento = :id",
            ['id' => $id]
        );
        if (!$mov) { $this->notFound(); return; }

        $usuario = $mov['usuario_solicita'] ? ['nombre_completo' => $mov['usuario_solicita']] : null;
        $origen  = $mov['area_origen'] ? ['nombre_area' => $mov['area_origen'], 'edificio' => $mov['edif_origen']] : null;
        $destino = $mov['area_destino'] ? ['nombre_area' => $mov['area_destino'], 'edificio' => $mov['edif_destino']] : null;
        $bien = [
            'nombre' => $mov['bien_nombre'], 'codigo_interno' => $mov['codigo_interno'],
            'marca' => $mov['marca'], 'modelo' => $mov['modelo'],
            'serial' => $mov['serial'], 'cantidad' => $mov['cantidad'],
        ];
        $fecha   = date('d/m/Y', strtotime($mov['fecha_solicitud']));
        $hora    = date('H:i', strtotime($mov['fecha_solicitud']));
        $nroActa = date('Ymd', strtotime($mov['fecha_solicitud'])) . "-" . str_pad((string)$id, 4, '0', STR_PAD_LEFT);

        $responsableDatos = !empty($mov['responsable_datos']) ? json_decode($mov['responsable_datos'], true) : [];

        $html = $this->generarHtmlPdf($mov['tipo_movimiento'], $bien, $origen, $destino, $usuario, $fecha, $hora, $nroActa, $mov['motivo'], $responsableDatos);

        try {
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('SBN MCP');
            $pdf->SetAuthor('Maternidad Concepción Palacios');
            $pdf->SetTitle('Acta de ' . ucfirst($mov['tipo_movimiento']) . ' #' . $id);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(15, 10, 15);
            $pdf->SetAutoPageBreak(true, 15);
            $pdf->AddPage();

            // Encabezado como imagen JPG
            $encabezadoPath = ROOT_PATH . '/public/img/encabezado_at.png';
            if (file_exists($encabezadoPath) && function_exists('imagecreatefrompng')) {
                $img = @imagecreatefrompng($encabezadoPath);
                if ($img) {
                    $w = imagesx($img);
                    $h = imagesy($img);
                    $white = imagecreatetruecolor($w, $h);
                    imagefill($white, 0, 0, imagecolorallocate($white, 255, 255, 255));
                    imagecopy($white, $img, 0, 0, 0, 0, $w, $h);
                    ob_start();
                    imagejpeg($white, null, 95);
                    $jpgData = ob_get_clean();
                    imagedestroy($img);
                    imagedestroy($white);
                    $tmpJpg = ROOT_PATH . '/scratch/encabezado_temp.jpg';
                    file_put_contents($tmpJpg, $jpgData);
                    $pdf->Image($tmpJpg, 0, 0, 210);
                    $pdf->Ln(40);
                }
            }

            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Output("acta_" . $mov['tipo_movimiento'] . "_{$id}.pdf", 'D');
            exit;
        } catch (\Exception $e) {
            die("Error al generar PDF: " . $e->getMessage());
        }
    }

    /**
     * HTML para PDF (usa <font size> que TCPDF interpreta en pt).
     */
    private function generarHtmlPdf(
        string $tipo, array $bien, ?array $origen, ?array $destino,
        ?array $usuario, string $fecha, string $hora, string $nroActa, string $motivo,
        array $responsableDatos = []
    ): string {
        $tituloActa = match($tipo) {
            'incorporacion'    => 'ACTA DE INCORPORACIÓN',
            'desincorporacion' => 'ACTA DE DESINCORPORACIÓN',
            'traslado'         => 'ACTA DE TRASLADO',
            'asignacion'       => 'ACTA DE ASIGNACIÓN',
            default            => 'ACTA DE MOVIMIENTO',
        };
        $subTituloBien = match($tipo) {
            'incorporacion'    => 'DATOS DEL BIEN INCORPORADO',
            'desincorporacion' => 'DATOS DEL BIEN DESINCORPORADO',
            'traslado'         => 'DATOS DEL BIEN TRASLADADO',
            'asignacion'       => 'DATOS DEL BIEN ASIGNADO',
            default            => 'DATOS DEL BIEN',
        };

        $areaValor = '';
        $areaData = $destino ?: $origen;
        if ($areaData) {
            $areaValor = htmlspecialchars($areaData['nombre_area']);
            if (!empty($areaData['edificio'])) $areaValor .= ' — ' . htmlspecialchars($areaData['edificio']);
        }
        $responsable = $usuario ? htmlspecialchars($usuario['nombre_completo']) : '________________________';
        $motivoHtml  = nl2br(htmlspecialchars($motivo));

        $rows = '';
        $keys = [
            ['Código / Nº de Bien:', $bien['codigo_interno'] ?? ''],
            ['Nombre / Descripción:', $bien['nombre'] ?? ''],
            ['Marca / Modelo:', ($bien['marca'] ?? 'N/A') . ' / ' . ($bien['modelo'] ?? 'N/A')],
            ['Serie:', $bien['serial'] ?? 'N/A'],
            ['Cantidad:', $bien['cantidad'] ?? 1],
            ['Estado:', 'Usado'],
            ['Fecha de Incorporación:', $fecha],
            ['Proveedor (si aplica):', ''],
            ['Observaciones:', $motivoHtml],
        ];
        foreach ($keys as [$label, $val]) {
            $rows .= "<tr><td bgcolor='#dbeafe' width='35%'><b>" . htmlspecialchars($label) . "</b></td><td>" . htmlspecialchars($val) . "</td></tr>\n";
        }

        if ($tipo === 'traslado') {
            $origenN  = $origen  ? htmlspecialchars($origen['nombre_area'])  . (!empty($origen['edificio'])  ? ' — ' . htmlspecialchars($origen['edificio'])  : '') : 'N/A';
            $destinoN = $destino ? htmlspecialchars($destino['nombre_area']) . (!empty($destino['edificio']) ? ' — ' . htmlspecialchars($destino['edificio']) : '') : 'N/A';
            $rows .= "<tr><td bgcolor='#dbeafe'><b>Área de Origen:</b></td><td>{$origenN}</td></tr>\n";
            $rows .= "<tr><td bgcolor='#dbeafe'><b>Área de Destino:</b></td><td>{$destinoN}</td></tr>\n";
        }

        $htmlRespPdf = '';
        if ($tipo === 'asignacion' && !empty($responsableDatos['ci'])) {
            $respCi  = htmlspecialchars($responsableDatos['ci']);
            $respNom = htmlspecialchars($responsableDatos['nombre']);
            $respApe = htmlspecialchars($responsableDatos['apellido']);
            $htmlRespPdf = "<tr><td colspan='2'><b>CI del Responsable:</b> {$respCi}</td></tr>"
                . "<tr><td colspan='2'><b>Nombre y Apellido del Responsable:</b> {$respNom} {$respApe}</td></tr>";
        }

        return <<<HTML
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="65%"><font size="16"><b>{$tituloActa}</b></font></td>
<td width="35%" align="right"><font size="9"><b>Nº de Acta:</b> {$nroActa}</font></td>
</tr>
</table>

<table cellpadding="1" cellspacing="0" width="100%"><tr><td bgcolor="#1e40af"><font color="white" size="11"><b>&nbsp;1. INFORMACIÓN GENERAL</b></font></td></tr></table>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#3b82f6">
<tr><td width="50%"><b>Fecha:</b> {$fecha}</td><td width="50%"><b>Hora:</b> {$hora}</td></tr>
<tr><td colspan="2"><b>Área / Servicio:</b> {$areaValor}</td></tr>
<tr><td colspan="2"><b>Responsable del Registro:</b> {$responsable}</td></tr>
{$htmlRespPdf}
</table>

<table cellpadding="1" cellspacing="0" width="100%"><tr><td bgcolor="#1e40af"><font color="white" size="11"><b>&nbsp;2. {$subTituloBien}</b></font></td></tr></table>
<table cellpadding="4" cellspacing="0" width="100%" border="1" bordercolor="#999999">
{$rows}
</table>

<table cellpadding="1" cellspacing="0" width="100%"><tr><td bgcolor="#1e40af"><font color="white" size="11"><b>&nbsp;3. RESPONSABLES</b></font></td></tr></table>
<table cellpadding="4" cellspacing="0" width="100%" border="1" bordercolor="#999999">
<tr bgcolor="#dbeafe" align="center"><td width="33%"><b>Resp. del Área</b></td><td width="34%"><b>Encargado de Inventario</b></td><td width="33%"><b>Autorizado por</b></td></tr>
<tr>
<td align="center" valign="bottom" height="50"><font size="9">Firma: ________________<br/>Nombre: ________________</font></td>
<td align="center" valign="bottom" height="50"><font size="9">Firma: ________________<br/>Nombre: ________________</font></td>
<td align="center" valign="bottom" height="50"><font size="9">Firma: ________________<br/>Nombre: ________________</font></td>
</tr>
</table>

<font size="7" color="#999999"><br/>Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios</font>
HTML;
    }

    /**
     * HTML para Word (usa <font face> que Word siempre respeta).
     */
    private function generarHtmlWord(
        string $tipo, array $bien, ?array $origen, ?array $destino,
        ?array $usuario, string $fecha, string $hora, string $nroActa, string $motivo,
        string $headerImgTag = '',
        array $responsableDatos = []
    ): string {
        $tituloActa = match($tipo) {
            'incorporacion'    => 'ACTA DE INCORPORACIÓN',
            'desincorporacion' => 'ACTA DE DESINCORPORACIÓN',
            'traslado'         => 'ACTA DE TRASLADO',
            'asignacion'       => 'ACTA DE ASIGNACIÓN',
            default            => 'ACTA DE MOVIMIENTO',
        };
        $subTituloBien = match($tipo) {
            'incorporacion'    => 'DATOS DEL BIEN INCORPORADO',
            'desincorporacion' => 'DATOS DEL BIEN DESINCORPORADO',
            'traslado'         => 'DATOS DEL BIEN TRASLADADO',
            'asignacion'       => 'DATOS DEL BIEN ASIGNADO',
            default            => 'DATOS DEL BIEN',
        };

        $areaValor = '';
        $areaData = $destino ?: $origen;
        if ($areaData) {
            $areaValor = htmlspecialchars($areaData['nombre_area']);
            if (!empty($areaData['edificio'])) $areaValor .= ' — ' . htmlspecialchars($areaData['edificio']);
        }
        $responsable = $usuario ? htmlspecialchars($usuario['nombre_completo']) : '________________________';
        $motivoHtml  = nl2br(htmlspecialchars($motivo));

        $rows = '';
        $keys = [
            ['Código / Nº de Bien:', $bien['codigo_interno'] ?? ''],
            ['Nombre / Descripción:', $bien['nombre'] ?? ''],
            ['Marca / Modelo:', ($bien['marca'] ?? 'N/A') . ' / ' . ($bien['modelo'] ?? 'N/A')],
            ['Serie:', $bien['serial'] ?? 'N/A'],
            ['Cantidad:', $bien['cantidad'] ?? 1],
            ['Estado:', 'Usado'],
            ['Fecha de Incorporación:', $fecha],
            ['Proveedor (si aplica):', ''],
            ['Observaciones:', $motivoHtml],
        ];
        foreach ($keys as [$label, $val]) {
            $rows .= '<tr><td width="35%" style="background-color:#dbeafe;border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2"><b>' . htmlspecialchars($label) . '</b></font></td><td style="border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2">' . htmlspecialchars($val) . '</font></td></tr>' . "\n";
        }

        if ($tipo === 'traslado') {
            $origenN  = $origen  ? htmlspecialchars($origen['nombre_area'])  . (!empty($origen['edificio'])  ? ' — ' . htmlspecialchars($origen['edificio'])  : '') : 'N/A';
            $destinoN = $destino ? htmlspecialchars($destino['nombre_area']) . (!empty($destino['edificio']) ? ' — ' . htmlspecialchars($destino['edificio']) : '') : 'N/A';
            $rows .= '<tr><td style="background-color:#dbeafe;border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2"><b>Área de Origen:</b></font></td><td style="border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2">' . $origenN . '</font></td></tr>' . "\n";
            $rows .= '<tr><td style="background-color:#dbeafe;border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2"><b>Área de Destino:</b></font></td><td style="border:1px solid #aaa;padding:3pt 6pt;"><font face="Arial" size="2">' . $destinoN . '</font></td></tr>' . "\n";
        }

        // Encabezado azul de sección con font face Arial
        $secH = function (string $num, string $txt): string {
            return '<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:8pt;"><tr><td style="background-color:#1e40af;padding:2pt 6pt;"><font face="Arial" size="3" color="white"><b>' . $num . '. ' . $txt . '</b></font></td></tr></table>';
        };

        $respRows = '';
        if ($tipo === 'asignacion' && !empty($responsableDatos['ci'])) {
            $respCi  = htmlspecialchars($responsableDatos['ci']);
            $respNom = htmlspecialchars($responsableDatos['nombre']);
            $respApe = htmlspecialchars($responsableDatos['apellido']);
            $respRows = '<tr><td colspan="2" style="padding:3pt 6pt;border:none;border-top:1px solid #ccc;"><font size="2"><b>CI del Responsable:</b> ' . $respCi . '</font></td></tr>'
                . '<tr><td colspan="2" style="padding:3pt 6pt;border:none;"><font size="2"><b>Nombre y Apellido del Responsable:</b> ' . $respNom . ' ' . $respApe . '</font></td></tr>';
        }

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { margin: 0; padding: 0; font-family: Arial; }
table { border-collapse: collapse; width: 100%; }
p { margin: 0; }
@page { size: A4 portrait; margin: 10mm 15mm; }
</style>
</head>
<body>

<font face="Arial">

{$headerImgTag}

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="65%"><font size="5" color="#1e40af"><b>{$tituloActa}</b></font></td>
<td width="35%" align="right"><font size="2"><b>Nº de Acta:</b> {$nroActa}</font></td>
</tr>
</table>

{$secH('1', 'INFORMACIÓN GENERAL')}

<table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #3b82f6;">
<tr>
<td width="50%" style="padding:3pt 6pt;border:none;"><font size="2"><b>Fecha:</b> {$fecha}</font></td>
<td width="50%" style="padding:3pt 6pt;border:none;"><font size="2"><b>Hora:</b> {$hora}</font></td>
</tr>
<tr><td colspan="2" style="padding:3pt 6pt;border:none;"><font size="2"><b>Área / Servicio:</b> {$areaValor}</font></td></tr>
<tr><td colspan="2" style="padding:3pt 6pt;border:none;"><font size="2"><b>Responsable del Registro:</b> {$responsable}</font></td></tr>
{$respRows}
</table>

{$secH('2', $subTituloBien)}

<table cellpadding="0" cellspacing="0" width="100%">
{$rows}
</table>

{$secH('3', 'RESPONSABLES')}

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="33%" style="background-color:#dbeafe;text-align:center;border:1px solid #aaa;padding:3pt;"><font face="Arial" size="2"><b>Resp. del Área</b></font></td>
<td width="34%" style="background-color:#dbeafe;text-align:center;border:1px solid #aaa;padding:3pt;"><font face="Arial" size="2"><b>Encargado de Inventario</b></font></td>
<td width="33%" style="background-color:#dbeafe;text-align:center;border:1px solid #aaa;padding:3pt;"><font face="Arial" size="2"><b>Autorizado por</b></font></td>
</tr>
<tr>
<td style="border:1px solid #aaa;padding:12pt 6pt;text-align:center;vertical-align:bottom;"><font face="Arial" size="2">Firma: ________________<br/>Nombre: ________________</font></td>
<td style="border:1px solid #aaa;padding:12pt 6pt;text-align:center;vertical-align:bottom;"><font face="Arial" size="2">Firma: ________________<br/>Nombre: ________________</font></td>
<td style="border:1px solid #aaa;padding:12pt 6pt;text-align:center;vertical-align:bottom;"><font face="Arial" size="2">Firma: ________________<br/>Nombre: ________________</font></td>
</tr>
</table>

<br/>
<p align="center"><font face="Arial" size="1" color="#999999">Documento generado electrónicamente por el Sistema de Gestión de Bienes Nacionales (SBN-MCP) — Maternidad Concepción Palacios</font></p>

</font>
</body>
</html>
HTML;
    }

    /** Aprobar movimiento pendiente (no se puede aprobar un acta propia) */
    public function approve(int $id): void
    {
        if (!$this->verifyCSRFToken($this->getInput('csrf_token'))) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }
        if ($id <= 0) { $this->json(['error' => 'ID inválido'], 400); return; }

        $mov = Database::fetch(
            "SELECT estado, usuario_solicita_id, tipo_movimiento, bien_id, area_destino_id FROM movimientos WHERE id_movimiento = :id",
            ['id' => $id]
        );

        if (!$mov) {
            $this->json(['error' => 'Movimiento no encontrado'], 404);
            return;
        }

        if ($mov['estado'] !== 'pendiente') {
            $this->json(['error' => 'Solo se pueden aprobar movimientos pendientes'], 400);
            return;
        }

        // Separación de funciones: el solicitante no puede aprobar su propia acta
        if ((int) $mov['usuario_solicita_id'] === (int) Session::get('user_id')) {
            Logger::security('intento_autoaprobacion', [
                'movimiento_id' => $id,
                'user_id'       => Session::get('user_id'),
            ]);
            $this->json(['error' => 'No puede aprobar un acta solicitada por usted mismo (separación de funciones)'], 403);
            return;
        }

        Database::beginTransaction();
        try {
            Database::query(
                "UPDATE movimientos SET estado='aprobado', usuario_aprueba_id=:u, fecha_aprobacion=NOW()
                WHERE id_movimiento=:id AND estado='pendiente'",
                ['u' => Session::get('user_id'), 'id' => $id]
            );

            // Aplicar efectos del movimiento sobre el bien (diferidos hasta la aprobación)
            $this->aplicarEfectosMovimiento($mov);

            Database::commit();
            $this->logAudit('UPDATE', 'movimientos', $id);
            Logger::security('acta_aprobada', [
                'movimiento_id' => $id,
                'aprobada_por'  => Session::get('user_id'),
            ]);
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            Database::rollBack();
            Logger::error('Error al aprobar acta', ['error' => $e->getMessage(), 'id' => $id]);
            $this->json(['error' => 'Error interno al aprobar el acta'], 500);
        }
    }

    /** Rechazar movimiento pendiente (no se puede rechazar un acta propia) */
    public function reject(int $id): void
    {
        if (!$this->verifyCSRFToken($this->getInput('csrf_token'))) {
            $this->json(['error' => 'Token de seguridad inválido'], 403);
            return;
        }
        if ($id <= 0) { $this->json(['error' => 'ID inválido'], 400); return; }

        $mov = Database::fetch(
            "SELECT estado, usuario_solicita_id FROM movimientos WHERE id_movimiento = :id",
            ['id' => $id]
        );

        if (!$mov) {
            $this->json(['error' => 'Movimiento no encontrado'], 404);
            return;
        }

        if ($mov['estado'] !== 'pendiente') {
            $this->json(['error' => 'Solo se pueden rechazar movimientos pendientes'], 400);
            return;
        }

        // Separación de funciones: el solicitante no puede rechazar su propia acta
        if ((int) $mov['usuario_solicita_id'] === (int) Session::get('user_id')) {
            Logger::security('intento_autorechazo', [
                'movimiento_id' => $id,
                'user_id'       => Session::get('user_id'),
            ]);
            $this->json(['error' => 'No puede rechazar un acta solicitada por usted mismo (separación de funciones)'], 403);
            return;
        }

        Database::query(
            "UPDATE movimientos SET estado='rechazado', usuario_aprueba_id=:u, fecha_aprobacion=NOW()
            WHERE id_movimiento=:id AND estado='pendiente'",
            ['u' => Session::get('user_id'), 'id' => $id]
        );
        $this->logAudit('UPDATE', 'movimientos', $id);
        $this->json(['success' => true]);
    }

    /**
     * Aplica los efectos de un movimiento aprobado sobre el bien:
     * - Traslado: cambia el área/ubicación del bien.
     * - Desincorporación: pasa el bien a estado "Desincorporado".
     */
    private function aplicarEfectosMovimiento(array $mov): void
    {
        $tipo = $mov['tipo_movimiento'] ?? '';

        if ($tipo === 'traslado' && !empty($mov['area_destino_id'])) {
            $area = Database::fetch("SELECT * FROM areas WHERE id_area = :id", ['id' => $mov['area_destino_id']]);
            Database::query(
                "UPDATE bienes SET id_area=:area,
                    cin_edificio=:edif, cin_piso=:piso, cin_departamento=:depto
                WHERE id_bien=:id",
                [
                    'area'  => $mov['area_destino_id'],
                    'edif'  => $area['edificio'] ?? '',
                    'piso'  => $area['piso'] ?? '',
                    'depto' => $area['nombre_area'] ?? '',
                    'id'    => $mov['bien_id'],
                ]
            );
        }

        if ($tipo === 'desincorporacion' && !empty($mov['bien_id'])) {
            Database::query("UPDATE bienes SET id_estado=5 WHERE id_bien=:id", ['id' => $mov['bien_id']]);
        }
    }

}

