<?php
require APP_PATH . '/views/partials/sidebar.php';
?>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1>Control Mural — <?= htmlspecialchars($area['nombre_area']) ?></h1>
            <p style="color:#475569; margin-top:.4rem;">Edificio <?= htmlspecialchars($area['edificio']) ?> · <?= $area['piso'] == -1 ? 'Sótano' : ($area['piso'] == 0 ? 'P.Baja' : 'Piso ' . htmlspecialchars($area['piso'])) ?></p>
        </div>
        <div class="page-actions">
            <a href="<?= $base_url ?>/inventario-mural" class="btn btn-secondary btn-sm">← Volver al Control Mural</a>
            <button type="button" class="btn btn-success btn-sm" onclick="previewReport('excel')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Excel
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="previewReport('csv')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                CSV
            </button>
        </div>
    </div>

    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="display:grid; gap:1rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            <div class="summary-card">
                <h4>Total Bienes</h4>
                <p><strong><?= number_format($totales['total'] ?? 0) ?></strong></p>
            </div>
            <div class="summary-card">
                <h4>Valor Total</h4>
                <p><strong>Bs. <?= number_format($totales['valor_total'] ?? 0, 2, ',', '.') ?></strong></p>
            </div>
            <div class="summary-card">
                <h4>Operativos</h4>
                <p><strong><?= number_format($totales['operativos'] ?? 0) ?></strong></p>
            </div>
            <div class="summary-card">
                <h4>Inoperativos</h4>
                <p><strong><?= number_format($totales['inoperativos'] ?? 0) ?></strong></p>
            </div>
            <div class="summary-card">
                <h4>En Resguardo</h4>
                <p><strong><?= number_format($totales['resguardo'] ?? 0) ?></strong></p>
            </div>
        </div>
    </div>

    <?php if (empty($bienes)): ?>
    <div class="card">
        <div class="card-body">
            <p>No se encontraron bienes para esta área.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Clasif.</th>
                        <th>Cod. Interno</th>
                        <th>Nro. Bien</th>
                        <th>Nombre</th>
                        <th>Serial</th>
                        <th>Oficina</th>
                        <th>Posición</th>
                        <th>Valor</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bienes as $b): ?>
                    <tr>
                        <td><span class="badge badge-sm" style="background:#1e3a5f"><?= htmlspecialchars($b['tipo_codigo']) ?></span></td>
                        <td><?= htmlspecialchars($b['codigo_interno']) ?></td>
                        <td><?= $b['es_sn'] ? 'S/N' : htmlspecialchars($b['nro_bien_ministerio'] ?? '—') ?></td>
                        <td>
                            <div style="font-weight:600;"><?= htmlspecialchars($b['nombre']) ?></div>
                            <?php if (!empty($b['descripcion'])): ?>
                            <div style="font-size:.8rem;color:#64748b; margin-top:.2rem;">
                                <?= htmlspecialchars(mb_substr($b['descripcion'], 0, 80)) ?><?= mb_strlen($b['descripcion']) > 80 ? '…' : '' ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($b['serial'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($b['cin_oficina'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($b['cin_posicion'] ?? '—') ?></td>
                        <td style="text-align:right;">Bs. <?= number_format($b['valor_inicial'] ?? 0, 2, ',', '.') ?></td>
                        <td><span class="badge" style="background:<?= htmlspecialchars($b['estado_color']) ?>"><?= htmlspecialchars($b['estado_nombre']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</main>

<!-- Modal de Previsualización -->
<div id="previewModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6);">
    <div class="modal-content" style="background-color: #ffffff; margin: 2% auto; padding: 0; border: 1px solid #ccc; width: 95%; max-width: 1400px; height: 90vh; display: flex; flex-direction: column; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; border-bottom: 2px solid #e0e0e0; background-color: #f5f5f5;">
            <h2 id="previewModalTitle" style="margin: 0; font-size: 1.5rem; font-weight: 600;">Previsualización de Reporte</h2>
            <button type="button" class="close-button" style="background: none; border: none; color: #999; font-size: 32px; cursor: pointer; padding: 0; width: 32px; height: 32px; line-height: 1;" onclick="closePreviewModal();">×</button>
        </div>
        <div id="previewModalBody" style="flex: 1; overflow-y: auto; overflow-x: auto; padding: 2rem; background-color: #ffffff;">
            <!-- Aquí se cargará la tabla de previsualización -->
        </div>
        <div style="display: flex; gap: 1rem; justify-content: flex-end; padding: 1.5rem; border-top: 2px solid #e0e0e0; background-color: #f5f5f5;">
            <button id="downloadButton" class="btn btn-success" style="padding: 0.75rem 2rem; font-size: 1rem;">Descargar</button>
            <button class="btn btn-secondary" onclick="closePreviewModal()" style="padding: 0.75rem 2rem; font-size: 1rem;">Cancelar</button>
        </div>
    </div>
</div>

<script>
// Funciones para el modal de previsualización
function openPreviewModal(title, content, downloadUrl) {
    document.getElementById('previewModalTitle').innerText = title;
    document.getElementById('previewModalBody').innerHTML = content;
    if (downloadUrl) {
        document.getElementById('downloadButton').style.display = 'inline-block';
        document.getElementById('downloadButton').onclick = () => {
            window.location.href = downloadUrl;
            closePreviewModal();
        };
    } else {
        document.getElementById('downloadButton').style.display = 'none';
    }
    document.getElementById('previewModal').style.display = 'block';
}

function closePreviewModal() {
    document.getElementById('previewModal').style.display = 'none';
    document.getElementById('previewModalBody').innerHTML = '';
}

async function previewReport(type) {
    openPreviewModal('Cargando previsualización...', '<div style="text-align: center; padding: 3rem;"><p style="font-size: 1.1rem;">Obteniendo datos para previsualizar...</p><p style="font-size: 0.95rem; color: #666; margin-top: 1rem;">Por favor espere...</p></div>');

    try {
        const currentPath = window.location.pathname;
        const response = await fetch(currentPath + '?preview=' + type, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.status + ' ' + response.statusText);
        }

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Respuesta no es JSON: ' + contentType);
        }

        const data = await response.json();

        if (data.success && data.headers && data.rows) {
            let tableHtml = '<div style="overflow: auto; background: white;"><table class="data-table" style="width: 100%; border-collapse: collapse; font-size: 0.95rem; background: white; color: #333;">';
            tableHtml += '<thead><tr style="background-color: #2c3e50; font-weight: bold; position: sticky; top: 0; opacity: 1; transform: none;">';
            data.headers.forEach(header => {
                tableHtml += `<th style="padding: 1rem 0.75rem; border: 1px solid #bdc3c7; text-align: left; white-space: nowrap; color: white; font-weight: bold;">${escapeHtml(header)}</th>`;
            });
            tableHtml += '</tr></thead>';
            tableHtml += '<tbody>';
            data.rows.forEach((row, idx) => {
                const bgColor = idx % 2 === 0 ? '#ffffff' : '#ecf0f1';
                tableHtml += `<tr style="background-color: ${bgColor}; color: #333; opacity: 1; transform: none;">`;
                row.forEach(cell => {
                    tableHtml += `<td style="padding: 0.75rem; border: 1px solid #bdc3c7; color: #333;">${escapeHtml(String(cell))}</td>`;
                });
                tableHtml += '</tr>';
            });
            tableHtml += '</tbody>';
            tableHtml += '</table></div>';

            const downloadUrl = currentPath + '?export=' + type;
            openPreviewModal(`Previsualización - Control Mural (${type.toUpperCase()})`, tableHtml, downloadUrl);
        } else {
            const errorMsg = data.error || (data.message ? String(data.message) : 'No se pudieron obtener los datos');
            openPreviewModal('Error', `<div style="color: #c0392b; padding: 2rem; font-size: 1.1rem;"><p>${escapeHtml(errorMsg)}</p></div>`);
        }
    } catch (error) {
        console.error('Error:', error);
        openPreviewModal('Error', `<div style="color: #c0392b; padding: 2rem;"><p style="font-size: 1.1rem;">Error al cargar la previsualización</p><p style="font-size: 0.95rem; color: #666; margin-top: 1rem;">${escapeHtml(error.message)}</p></div>`);
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}
</script>
