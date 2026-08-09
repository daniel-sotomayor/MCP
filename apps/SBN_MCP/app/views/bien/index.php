<?php
/**
 * =============================================================================
 * VISTA: INVENTARIO DE BIENES NACIONALES
 * =============================================================================
 * 
 * Muestra el listado completo de bienes del sistema con:
 * - Filtros avanzados (búsqueda, tipo, estado, edificio, área)
 * - Tabla con información detallada de cada bien
 * - Paginación con preservación de filtros
 * - Acciones según rol del usuario (ver, editar)
 * 
 * Variables esperadas desde el controlador:
 * @var array  $bienes       Listado de bienes paginados
 * @var int    $total        Total de bienes encontrados
 * @var int    $page         Página actual
 * @var int    $totalPages   Total de páginas
 * @var array  $tipos        Tipos de bienes para filtro
 * @var array  $estados      Estados de bienes para filtro
 * @var array  $edificios    Edificios únicos para filtro
 * @var array  $areas        Áreas para filtro
 * @var string $search       Término de búsqueda actual
 * @var int    $tipo         Filtro de tipo seleccionado
 * @var int    $estado       Filtro de estado seleccionado
 * @var string $edificio     Filtro de edificio seleccionado
 * @var int    $area         Filtro de área seleccionado
 * @var string $base_url     URL base para enlaces
 * =============================================================================
 */

// Incluir sidebar de navegación
require APP_PATH . '/views/partials/sidebar.php';
?>

<!-- Contenido principal de la página -->
<main class="main-content">
    
    <!-- Encabezado de página con título y acciones -->
    <div class="page-header">
        <h1>Inventario de Bienes Nacionales</h1>
        <div class="page-actions">
            <!-- Botón de acceso a reportes -->
            <a href="<?= $base_url ?>/reportes" class="btn btn-secondary btn-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Reportes
            </a>

            <?php if (in_array($_SESSION['rol']??'', ['administrador','gerencia_bn','controlador_inventario'])): ?>
            <a href="<?= $base_url ?>/bienes/exportar-excel" class="btn btn-secondary btn-sm" title="Exportar inventario completo a Excel">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exportar Excel
            </a>
            <?php endif; ?>

            <?php if (in_array($_SESSION['rol']??'', ['administrador','gerencia_bn'])): ?>
            <a href="<?= $base_url ?>/bienes/plantilla-importacion" class="btn btn-secondary btn-sm" title="Descargar plantilla para importar bienes">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Plantilla
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('modalImportar').style.display='flex'" title="Importar bienes desde Excel">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Importar Excel
            </button>
            <?php endif; ?>

            <!-- Botón de registro (solo para roles con permiso) -->
            <?php if (in_array($_SESSION['rol']??'', ['administrador','gerencia_bn','registrador'])): ?>
            <a href="<?= $base_url ?>/bienes/nuevo" class="btn btn-success btn-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Registrar Bien
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Importar Excel -->
    <div id="modalImportar" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,.6); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:var(--surface,#fff); border-radius:12px; padding:2rem; max-width:520px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,.3);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h3 style="margin:0; color:var(--text-primary,#1e293b);">Importar Inventario desde Excel</h3>
                <button onclick="this.closest('#modalImportar').style.display='none'" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--text-secondary,#94a3b8);">&times;</button>
            </div>
            <p style="color:var(--text-secondary,#64748b); font-size:.9rem; margin-bottom:1rem;">
                Suba un archivo <strong>.xlsx</strong> con los bienes a importar. Descargue la plantilla primero para conocer el formato requerido.
            </p>
            <form id="formImportar" enctype="multipart/form-data" style="margin-bottom:1rem;">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?? '' ?>">
                <div style="border:2px dashed var(--border,#cbd5e1); border-radius:8px; padding:2rem; text-align:center; cursor:pointer; transition:border-color .2s;" onclick="document.getElementById('fileInput').click()" onmouseover="this.style.borderColor='var(--primary,#3b82f6)'" onmouseout="this.style.borderColor='var(--border,#cbd5e1)'">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary,#94a3b8)" stroke-width="1.5" style="margin-bottom:.5rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    <p style="margin:0; color:var(--text-secondary,#64748b);" id="fileLabel">Haga clic para seleccionar archivo .xlsx</p>
                    <input type="file" id="fileInput" name="archivo_excel" accept=".xlsx,.xls" style="display:none" onchange="document.getElementById('fileLabel').textContent=this.files[0]?.name||'Haga clic para seleccionar archivo .xlsx'">
                </div>
            </form>
            <div id="importResult" style="display:none; margin-top:1rem; padding:1rem; border-radius:8px; font-size:.9rem;"></div>
            <div style="display:flex; gap:.5rem; justify-content:flex-end; margin-top:1rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('#modalImportar').style.display='none'">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnImportar" onclick="ejecutarImportacion()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Importar
                </button>
            </div>
        </div>
    </div>

    <!-- ==========================================================================
        SECCIÓN DE FILTROS
        ========================================================================== -->
    <div class="filters-card">
        <form method="GET" action="">
            <div class="filters-grid">

                <!-- Campo de búsqueda general (ocupa 2 columnas) -->
                <div class="filter-item" style="grid-column: span 2;">
                    <label class="filter-label">Buscar</label>
                    <input type="text" name="search" class="form-control" 
                        placeholder="Nombre, código, Nro. Bien, serial..." 
                        value="<?= htmlspecialchars($search) ?>">
                </div>

                <!-- Filtro por tipo de bien -->
                <div class="filter-item">
                    <label class="filter-label">Clasificación</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($tipos as $t): ?>
                        <option value="<?= $t['id_tipo'] ?>" <?= $tipo == $t['id_tipo'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['codigo'] . ' - ' . $t['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por estado del bien -->
                <div class="filter-item">
                    <label class="filter-label">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($estados as $e): ?>
                        <option value="<?= $e['id_estado'] ?>" <?= $estado == $e['id_estado'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Filtro por edificio -->
                <div class="filter-item">
                    <label class="filter-label">Edificio</label>
                    <select name="edificio" class="form-control">
                        <option value="">Todos</option>
                        <?php foreach ($edificios as $ed): ?>
                        <option value="<?= htmlspecialchars($ed['edificio']) ?>" <?= $edificio === $ed['edificio'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ed['edificio']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por área específica -->
                <div class="filter-item">
                    <label class="filter-label">Área</label>
                    <select name="area" class="form-control">
                        <option value="">Todas</option>
                        <?php foreach ($areas as $a): ?>
                        <option value="<?= $a['id_area'] ?>" <?= $area == $a['id_area'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['edificio'] . ' P' . $a['piso'] . ' - ' . $a['nombre_area']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Botones de acción para filtros -->
                <div class="filter-item filter-actions">
                    <label class="filter-label">&nbsp;</label>
                    <div class="filter-buttons">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="<?= $base_url ?>/bienes" class="btn btn-secondary">Limpiar</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ==========================================================================
        TABLA DE INVENTARIO
        ========================================================================== -->
    <div class="card" style="margin-top:1.5rem;">
        <!-- Encabezado con contador de resultados -->
        <div class="card-header">
            <h3 style="font-size:0.875rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
                <?= number_format($total) ?> BIEN(ES) ENCONTRADO(S)
                <?php if (!empty($search) || !empty($estado) || !empty($area) || !empty($tipo) || !empty($edificio)): ?>
                    <span style="font-weight:400;color:#718096"> — filtrado</span>
                <?php endif; ?>
            </h3>
        </div>

        <!-- Contenedor responsive de la tabla -->
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Clasif.</th>
                        <th>Cód. Interno</th>
                        <th>Nro. Bien Min.</th>
                        <th>Nombre y Descripción</th>
                        <th>C.I.N</th>
                        <th>Cant.</th>
                        <th>Valor Unit.</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($bienes)): ?>
                    <!-- Mensaje cuando no hay resultados -->
                    <tr class="empty-row"><td colspan="9">No se encontraron bienes</td></tr>
                <?php else: foreach ($bienes as $b): ?>
                    <tr>
                        <!-- Código de clasificación con badge -->
                        <td>
                            <span class="badge badge-sm" style="background:#1e3a5f" title="<?= htmlspecialchars($b['tipo_nombre'] ?? '') ?>">
                                <?= htmlspecialchars($b['tipo_codigo'] ?? '—') ?>
                            </span>
                        </td>

                        <!-- Código interno del bien -->
                        <td><span class="codigo-interno"><?= htmlspecialchars($b['codigo_interno'] ?? '') ?></span></td>
                        
                        <!-- Número de bien del ministerio o S/N -->
                        <td>
                            <?php if ($b['es_sn']): ?>
                                <em style="color:#a0aec0;font-size:.8rem">S/N</em>
                            <?php else: ?>
                                <code style="font-size:.8rem"><?= htmlspecialchars($b['nro_bien_ministerio'] ?? '') ?></code>
                            <?php endif; ?>
                        </td>

                        <!-- Nombre del bien con descripción truncada -->
                        <td>
                            <div style="font-weight:600;font-size:.875rem"><?= htmlspecialchars($b['nombre']) ?></div>
                            <?php if (!empty($b['descripcion'])): ?>
                            <div style="font-size:.75rem;color:#718096;margin-top:.1rem">
                                <?= htmlspecialchars(mb_substr($b['descripcion'], 0, 60)) ?><?= mb_strlen($b['descripcion']) > 60 ? '…' : '' ?>
                            </div>
                            <?php endif; ?>
                        </td>

                        <!-- Código interno de navegación (CIN) -->
                        <td>
                            <?php if (!empty($b['cin_completo'])): ?>
                                <span class="cin-code" title="Edificio/Piso/Depto/Oficina/Pos">
                                    <?= htmlspecialchars($b['cin_completo']) ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#a0aec0;font-size:.8rem">—</span>
                            <?php endif; ?>
                        </td>

                        <!-- Cantidad -->
                        <td style="text-align:center"><?= (int)($b['cantidad'] ?? 1) ?></td>

                        <!-- Valor inicial formateado -->
                        <td style="text-align:right;font-size:.85rem">
                            <?= $b['valor_inicial'] > 0 ? 'Bs. ' . number_format($b['valor_inicial'], 2) : '<span style="color:#a0aec0">—</span>' ?>
                        </td>

                        <!-- Badge de estado con color dinámico -->
                        <td>
                            <span class="badge" style="background:<?= htmlspecialchars($b['estado_color']) ?>">
                                <?= htmlspecialchars($b['estado_nombre']) ?>
                            </span>
                        </td>

                        <!-- Columna de acciones disponibles -->
                        <td>
                            <div style="display:flex;gap:.3rem">
                                <!-- Ver detalle: disponible para todos -->
                                <a href="<?= $base_url ?>/bienes/<?= $b['id_bien'] ?>" 
                                class="btn btn-sm btn-secondary btn-icon" title="Ver detalle">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>

                                <?php
                                // Verificar permisos para editar
                                // Pueden editar: administrador, gerencia_bn, controlador_inventario
                                $rol = $_SESSION['rol'] ?? '';
                                if (in_array($rol, ['administrador','gerencia_bn','controlador_inventario'])):
                                ?>
                                <!-- Editar: solo usuarios con permisos -->
                                <a href="<?= $base_url ?>/bienes/<?= $b['id_bien'] ?>/editar" 
                                class="btn btn-sm btn-warning btn-icon" title="Editar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==========================================================================
        PAGINACIÓN
        ==========================================================================
        Mantiene los filtros activos al navegar entre páginas mediante
        parámetros GET. Genera enlaces dinámicos con los filtros actuales.
        ========================================================================== -->
    <?php if ($totalPages > 1): ?>
    <?php
    // Construir query string con filtros actuales para preservarlos al paginar
    $qp = array_filter(compact('search','estado','area','tipo','edificio'));
    $qs = http_build_query($qp);
    $qs = $qs ? '&' . $qs : '';
    ?>
    <div style="display:flex;justify-content:center;align-items:center;gap:.5rem;margin-top:1.5rem;flex-wrap:wrap">
        <!-- Botón página anterior -->
        <?php if ($page > 1): ?>
        <a href="?page=<?= $page-1 ?><?= $qs ?>" class="btn btn-secondary btn-sm">‹ Anterior</a>
        <?php endif; ?>
        
        <!-- Números de página (mostrando rango de -2 a +2 de la actual) -->
        <?php for ($p = max(1,$page-2); $p <= min($totalPages,$page+2); $p++): ?>
        <a href="?page=<?= $p ?><?= $qs ?>"
        class="btn btn-sm <?= $p === $page ? 'btn-primary' : 'btn-secondary' ?>">
            <?= $p ?>
        </a>
        <?php endfor; ?>
        
        <!-- Botón página siguiente -->
        <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page+1 ?><?= $qs ?>" class="btn btn-secondary btn-sm">Siguiente ›</a>
        <?php endif; ?>
        
        <!-- Indicador de página actual -->
        <span style="color:#718096;font-size:.8rem;margin-left:.5rem">
            Página <?= $page ?> de <?= $totalPages ?>
        </span>
    </div>
    <?php endif; ?>
</main>

<script>
function ejecutarImportacion() {
    var form = document.getElementById('formImportar');
    var fileInput = document.getElementById('fileInput');
    var resultDiv = document.getElementById('importResult');
    var btn = document.getElementById('btnImportar');

    if (!fileInput.files.length) {
        resultDiv.style.display = 'block';
        resultDiv.style.background = '#fef2f2';
        resultDiv.style.color = '#991b1b';
        resultDiv.textContent = 'Seleccione un archivo antes de importar.';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Importando...';
    resultDiv.style.display = 'none';

    var fd = new FormData(form);

    fetch('<?= $base_url ?>/bienes/importar-excel', {
        method: 'POST',
        body: fd
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        resultDiv.style.display = 'block';
        if (data.success) {
            resultDiv.style.background = '#f0fdf4';
            resultDiv.style.color = '#166534';
            var msg = 'Importación completada.<br>';
            msg += '<strong>' + data.insertados + '</strong> insertados, ';
            msg += '<strong>' + data.actualizados + '</strong> actualizados.';
            if (data.errores.length > 0) {
                msg += '<br><br>Errores:<br><ul style="margin:0;padding-left:1.2rem;">';
                data.errores.forEach(function(e) { msg += '<li>' + e + '</li>'; });
                msg += '</ul>';
            }
            resultDiv.innerHTML = msg;
            setTimeout(function() { location.reload(); }, 2500);
        } else {
            resultDiv.style.background = '#fef2f2';
            resultDiv.style.color = '#991b1b';
            resultDiv.textContent = data.error || 'Error al importar';
        }
    })
    .catch(function() {
        resultDiv.style.display = 'block';
        resultDiv.style.background = '#fef2f2';
        resultDiv.style.color = '#991b1b';
        resultDiv.textContent = 'Error de conexión con el servidor.';
    })
    .finally(function() {
        btn.disabled = false;
        btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Importar';
    });
}
</script>
