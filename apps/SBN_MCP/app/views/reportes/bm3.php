<?php
/**
 * Vista: Reporte BM-3 — Movimientos del Período
 *
 * @var array  $movimientos   Lista de movimientos del período.
 * @var string $fecha_desde   Fecha inicio del filtro (Y-m-d).
 * @var string $fecha_hasta   Fecha fin del filtro (Y-m-d).
 * @var string $tipo_filtro   Tipo de movimiento filtrado.
 * @var string $estado_filtro Estado filtrado.
 * @var array  $totales_tipo  Conteo por tipo de movimiento.
 * @var string $base_url      URL base de la aplicación.
 */

require APP_PATH . '/views/partials/sidebar.php';

$colorTipo = fn(string $t): string => match ($t) {
    'incorporacion'    => '#38a169',
    'traslado'         => '#3182ce',
    'desincorporacion' => '#e53e3e',
    default            => '#718096',
};
$colorEstado = fn(string $e): string => match ($e) {
    'aprobado'  => '#38a169',
    'rechazado' => '#e53e3e',
    default     => '#d69e2e',
};
$labelTipo = [
    'incorporacion'    => 'Incorporaciones',
    'traslado'         => 'Traslados',
    'desincorporacion' => 'Desincorporaciones',
];

// Construir query string actual para los botones de exportación
$exportParams = http_build_query(array_filter([
    'fecha_desde' => $fecha_desde,
    'fecha_hasta' => $fecha_hasta,
    'tipo'        => $tipo_filtro,
    'estado'      => $estado_filtro,
]));
$exportBase = $base_url . '/reportes/bm3?' . ($exportParams ? $exportParams . '&' : '');
?>

<main class="main-content" id="mainContent">

    <div class="page-header">
        <div>
            <h1>Reporte BM-3 — Movimientos del Período</h1>
            <p class="page-subtitle">
                Generado: <?= date('d/m/Y H:i') ?>
                <?php if ($fecha_desde || $fecha_hasta): ?>
                &nbsp;·&nbsp;
                <?= $fecha_desde ? date('d/m/Y', strtotime($fecha_desde)) : '—' ?>
                al
                <?= $fecha_hasta ? date('d/m/Y', strtotime($fecha_hasta)) : 'hoy' ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="page-actions">
            <a href="<?= $exportBase ?>export=pdf" class="btn btn-danger btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                PDF
            </a>
            <button type="button" class="btn btn-success btn-sm" onclick="previewReport('excel')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Excel
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="previewReport('csv')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                CSV
            </button>
            <button onclick="window.print()" class="btn btn-secondary btn-sm">Imprimir</button>
            <a href="<?= $base_url ?>/reportes" class="btn btn-secondary btn-sm">← Reportes</a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card" style="margin-bottom:1.5rem">
        <div class="card-body" style="padding:1rem 1.25rem">
            <form method="GET" role="search">
                <div style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end">
                    <div style="display:flex;flex-direction:column;gap:.25rem;min-width:140px">
                        <label for="f-desde" style="font-size:.8rem;font-weight:600;color:var(--gray-600)">Desde</label>
                        <input type="date" name="fecha_desde" id="f-desde" class="form-input"
                               value="<?= htmlspecialchars($fecha_desde) ?>">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.25rem;min-width:140px">
                        <label for="f-hasta" style="font-size:.8rem;font-weight:600;color:var(--gray-600)">Hasta</label>
                        <input type="date" name="fecha_hasta" id="f-hasta" class="form-input"
                               value="<?= htmlspecialchars($fecha_hasta) ?>">
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.25rem;min-width:160px">
                        <label for="f-tipo" style="font-size:.8rem;font-weight:600;color:var(--gray-600)">Tipo</label>
                        <select name="tipo" id="f-tipo" class="form-select">
                            <option value="">Todos los tipos</option>
                            <?php foreach (['incorporacion', 'traslado', 'desincorporacion'] as $t): ?>
                            <option value="<?= $t ?>" <?= $tipo_filtro === $t ? 'selected' : '' ?>>
                                <?= $labelTipo[$t] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.25rem;min-width:150px">
                        <label for="f-estado" style="font-size:.8rem;font-weight:600;color:var(--gray-600)">Estado</label>
                        <select name="estado" id="f-estado" class="form-select">
                            <option value="">Todos los estados</option>
                            <?php foreach (['pendiente', 'aprobado', 'rechazado'] as $e): ?>
                            <option value="<?= $e ?>" <?= ($estado_filtro ?? '') === $e ? 'selected' : '' ?>>
                                <?= ucfirst($e) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="display:flex;gap:.5rem;padding-bottom:1px">
                        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                        <a href="<?= $base_url ?>/reportes/bm3" class="btn btn-secondary btn-sm">Limpiar</a>
                    </div>
                    <!-- Accesos rápidos -->
                    <div style="display:flex;gap:.4rem;padding-bottom:1px;margin-left:auto">
                        <a href="<?= $base_url ?>/reportes/bm3?fecha_desde=<?= date('Y-m-01') ?>&fecha_hasta=<?= date('Y-m-d') ?>"
                           class="btn btn-sm <?= ($fecha_desde === date('Y-m-01') && $fecha_hasta === date('Y-m-d')) ? 'btn-primary' : 'btn-outline' ?>"
                           style="font-size:.75rem">Este mes</a>
                        <a href="<?= $base_url ?>/reportes/bm3?fecha_desde=<?= date('Y-01-01') ?>&fecha_hasta=<?= date('Y-12-31') ?>"
                           class="btn btn-sm <?= ($fecha_desde === date('Y-01-01')) ? 'btn-primary' : 'btn-outline' ?>"
                           style="font-size:.75rem">Este año</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Métricas -->
    <?php if (!empty($totales_tipo)): ?>
    <div class="metrics-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:1.5rem">
        <?php foreach ($totales_tipo as $tipo => $cant): ?>
        <div class="metric-card">
            <div class="metric-icon" style="background:<?= $colorTipo($tipo) ?>" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="5 9 2 12 5 15"/><polyline points="19 9 22 12 19 15"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                </svg>
            </div>
            <div>
                <span class="metric-value"><?= number_format($cant) ?></span>
                <span class="metric-label"><?= $labelTipo[$tipo] ?? ucfirst($tipo) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="metric-card">
            <div class="metric-icon blue" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>
                </svg>
            </div>
            <div>
                <span class="metric-value"><?= number_format(array_sum($totales_tipo)) ?></span>
                <span class="metric-label">Total</span>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($movimientos)): ?>
    <div class="card">
        <div class="card-body" style="text-align:center;padding:3rem">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" stroke-width="1.5" style="margin-bottom:1rem"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <p style="color:var(--gray-500);margin:0">
                No se encontraron movimientos con los filtros aplicados.
            </p>
            <a href="<?= $base_url ?>/reportes/bm3" class="btn btn-secondary btn-sm" style="margin-top:1rem">Ver todos</a>
        </div>
    </div>
    <?php else: ?>

    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.75rem">
        <input type="search" id="bm3Search" class="form-input"
               placeholder="Buscar por bien, código, área o usuario…"
               style="max-width:380px"
               aria-label="Filtrar tabla">
        <span style="font-size:.82rem;color:var(--gray-500)" id="bm3Count">
            <?= count($movimientos) ?> registro(s)
        </span>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="bm3Table" aria-label="Movimientos del período">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Bien</th>
                        <th scope="col">Código</th>
                        <th scope="col">Área Origen</th>
                        <th scope="col">Área Destino</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Aprobador</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td style="font-size:.8rem;white-space:nowrap">
                        <?= date('d/m/Y H:i', strtotime($m['fecha_solicitud'])) ?>
                    </td>
                    <td>
                        <span class="badge badge-sm" style="background:<?= $colorTipo($m['tipo_movimiento']) ?>">
                            <?= ucfirst($m['tipo_movimiento']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-sm" style="background:<?= $colorEstado($m['estado']) ?>">
                            <?= ucfirst($m['estado']) ?>
                        </span>
                    </td>
                    <td style="font-size:.875rem;font-weight:500">
                        <a href="<?= $base_url ?>/bienes/<?= (int)$m['bien_id'] ?>">
                            <?= htmlspecialchars($m['bien_nombre']) ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= $base_url ?>/movimientos/<?= (int)$m['id_movimiento'] ?>"
                           class="codigo-interno" style="font-size:.78rem">
                            <?= htmlspecialchars($m['codigo_interno'] ?? '—') ?>
                        </a>
                    </td>
                    <td style="font-size:.82rem"><?= htmlspecialchars($m['area_origen'] ?? '—') ?></td>
                    <td style="font-size:.82rem"><?= htmlspecialchars($m['area_destino'] ?? '—') ?></td>
                    <td style="font-size:.82rem"><?= htmlspecialchars($m['usuario_solicita'] ?? '—') ?></td>
                    <td style="font-size:.82rem"><?= htmlspecialchars($m['usuario_aprueba'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background:var(--gray-50);font-weight:700">
                        <td colspan="9" style="padding:.75rem 1rem;font-size:.85rem">
                            Total: <?= number_format(count($movimientos)) ?> movimiento(s)
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php endif; ?>

</main>

<style>
.page-subtitle { font-size:.85rem; color:var(--gray-500); margin-top:.2rem; }
.btn-outline { border:1px solid var(--gray-300); background:transparent; color:var(--gray-600); }
.btn-outline:hover { background:var(--gray-100); }
</style>

<script>
const bm3Search = document.getElementById('bm3Search');
const bm3Count  = document.getElementById('bm3Count');
if (bm3Search) {
    bm3Search.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        let visible = 0;
        document.querySelectorAll('#bm3Table tbody tr').forEach(function (row) {
            const show = row.textContent.toLowerCase().includes(q);
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (bm3Count) bm3Count.textContent = visible + ' registro(s)';
    });
}
</script>

<!-- Modal de Previsualización -->
<div id="previewModal" style="display:none;position:fixed;z-index:1000;inset:0;background:rgba(0,0,0,.6)">
    <div style="background:#fff;margin:2% auto;width:95%;max-width:1400px;height:90vh;display:flex;flex-direction:column;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.3)">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:1.25rem 1.5rem;border-bottom:2px solid #e0e0e0;background:#f5f5f5">
            <h2 id="previewModalTitle" style="margin:0;font-size:1.25rem;font-weight:600"></h2>
            <button type="button" style="background:none;border:none;font-size:28px;cursor:pointer;color:#999;line-height:1" onclick="closePreviewModal()">×</button>
        </div>
        <div id="previewModalBody" style="flex:1;overflow:auto;padding:1.5rem;background:#fff"></div>
        <div style="display:flex;gap:1rem;justify-content:flex-end;padding:1.25rem 1.5rem;border-top:2px solid #e0e0e0;background:#f5f5f5">
            <button id="downloadButton" class="btn btn-success" style="padding:.6rem 1.5rem">Descargar</button>
            <button class="btn btn-secondary" onclick="closePreviewModal()" style="padding:.6rem 1.5rem">Cancelar</button>
        </div>
    </div>
</div>

<script>
function openPreviewModal(title, content, downloadUrl) {
    document.getElementById('previewModalTitle').innerText = title;
    document.getElementById('previewModalBody').innerHTML = content;
    const btn = document.getElementById('downloadButton');
    if (downloadUrl) {
        btn.style.display = 'inline-block';
        btn.onclick = () => { window.location.href = downloadUrl; closePreviewModal(); };
    } else {
        btn.style.display = 'none';
    }
    document.getElementById('previewModal').style.display = 'block';
}
function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
    document.getElementById('previewModalBody').innerHTML = '';
}
async function previewReport(type) {
    openPreviewModal('Cargando…', '<div style="text-align:center;padding:3rem;color:#666">Obteniendo datos…</div>');
    try {
        const sep = window.location.search ? '&' : '?';
        const res = await fetch(window.location.pathname + window.location.search + sep + 'preview=' + type, {
            credentials: 'include', headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (data.success && data.headers && data.rows) {
            let html = '<div style="overflow:auto"><table style="width:100%;border-collapse:collapse;font-size:.9rem">';
            html += '<thead><tr style="background:#2c3e50;color:#fff">';
            data.headers.forEach(h => { html += `<th style="padding:.75rem;border:1px solid #bdc3c7;white-space:nowrap">${escapeHtml(h)}</th>`; });
            html += '</tr></thead><tbody>';
            data.rows.forEach((row, i) => {
                html += `<tr style="background:${i%2===0?'#fff':'#ecf0f1'}">`;
                row.forEach(c => { html += `<td style="padding:.6rem .75rem;border:1px solid #bdc3c7">${escapeHtml(String(c))}</td>`; });
                html += '</tr>';
            });
            html += '</tbody></table></div>';
            const sep2 = window.location.search ? '&' : '?';
            openPreviewModal('BM-3 — ' + type.toUpperCase(), html,
                window.location.pathname + window.location.search + sep2 + 'export=' + type);
        } else {
            openPreviewModal('Error', `<p style="color:#c0392b;padding:2rem">${escapeHtml(data.error || 'Sin datos')}</p>`);
        }
    } catch (e) {
        openPreviewModal('Error', `<p style="color:#c0392b;padding:2rem">${escapeHtml(e.message)}</p>`);
    }
}
function escapeHtml(t) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(t));
    return d.innerHTML;
}
</script>
