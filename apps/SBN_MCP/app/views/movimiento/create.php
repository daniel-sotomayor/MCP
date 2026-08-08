<?php require APP_PATH . '/views/partials/sidebar.php'; ?>

<main class="main-content">
    <div class="page-header">
        <h1>Generar Nueva Acta de Movimiento</h1>
        <a href="<?= $base_url ?>/movimientos" class="btn btn-secondary btn-sm">← Volver al listado</a>
    </div>

    <div class="form-card">
        <form id="formMovimiento" action="<?= $base_url ?>/movimientos" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <div class="form-section">
                <h3 class="form-section-title">Datos del Movimiento</h3>
                
                <div class="form-grid">
                    <!-- Selección de Bien + Tipo en la misma fila -->
                    <div class="form-group" style="position:relative;">
                        <label>Bien Nacional <span class="req">*</span></label>
                        <input type="text" id="bien_search" class="form-control" placeholder="Código, nombre o serial..." autocomplete="off" required>
                        <div id="bien_dropdown" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:100; background:var(--surface,#fff); border:1px solid var(--border,#ccc); border-radius:0 0 6px 6px; max-height:200px; overflow-y:auto; box-shadow:0 4px 12px rgba(0,0,0,.15);"></div>
                        <input type="hidden" name="bien_id" id="bien_id">
                        <input type="hidden" name="area_origen_id" id="area_origen_id">
                        <span class="form-error" id="bien_id-error"></span>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Acta <span class="req">*</span></label>
                        <select name="tipo_movimiento" id="tipo_movimiento" class="form-control" required>
                            <option value="">Seleccione tipo...</option>
                            <option value="traslado">Traslado (Cambio de Ubicación)</option>
                            <option value="desincorporacion">Desincorporación (Baja)</option>
                            <option value="asignacion">Asignación Directa</option>
                        </select>
                        <span class="form-error" id="tipo_movimiento-error"></span>
                    </div>

                    <!-- Área Destino (Solo para traslados) -->
                    <div class="form-group full" id="div_destino" style="display:none;">
                        <label>Área de Destino (Ubicación C.I.N) <span class="req">*</span></label>
                        <select name="area_destino_id" class="form-control">
                            <option value="">Seleccione ubicación destino...</option>
                            <?php foreach ($areas as $a): ?>
                                <option value="<?= $a['id_area'] ?>">
                                    <?= htmlspecialchars($a['edificio']) ?> / <?= htmlspecialchars($a['piso']) ?> / <?= htmlspecialchars($a['nombre_area']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-error" id="area_destino_id-error"></span>
                    </div>

                    <!-- Datos del Responsable (Solo para asignación) - 3 campos en fila -->
                    <div id="div_responsable" class="form-group full" style="display:none;">
                        <label style="margin-bottom:4px; font-size:0.8rem; color:#94a3b8;">Datos del Responsable (quien recibe el bien)</label>
                        <div class="form-grid" style="gap:0.75rem; margin-top:2px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label>CI <span class="req">*</span></label>
                                <input type="text" name="responsable_ci" id="responsable_ci" class="form-control" placeholder="Cédula">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Nombre(s) <span class="req">*</span></label>
                                <input type="text" name="responsable_nombre" id="responsable_nombre" class="form-control" placeholder="Nombre">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label>Apellido(s) <span class="req">*</span></label>
                                <input type="text" name="responsable_apellido" id="responsable_apellido" class="form-control" placeholder="Apellido">
                            </div>
                        </div>
                    </div>

                    <!-- Motivo Justificado -->
                    <div class="form-group full">
                        <label>Motivo del Movimiento <span class="req">*</span></label>
                        <textarea name="motivo" class="form-control" rows="2" placeholder="Describa la razón técnica del cambio..." required></textarea>
                        <span class="form-error" id="motivo-error"></span>
                    </div>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-primary" id="previewBtn">
                    👁 Previsualizar y Editar Acta
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display:none">
                    ✓ Registrar Movimiento y Guardar Acta
                </button>
            </div>
            
            <div id="formMessage" class="message"></div>
        </form>
    </div>

    <!-- Modal de Previsualización -->
    <div id="previewModal" class="modal" style="display:none">
        <div class="modal-content" style="max-width:950px; width:98%; max-height:95vh; display:flex; flex-direction:column; padding:0;">
            <div class="modal-header" style="padding:1rem 1.5rem; flex-shrink:0;">
                <h3 style="margin:0; font-size:1.1rem;">Previsualización del Acta</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body" style="flex:1; overflow-y:auto; padding:1rem 1.5rem;">
                <p style="font-size:0.8rem; color:#64748b; margin:0 0 0.75rem 0;">Puede editar el contenido del acta antes de guardarla definitivamente.</p>
                <div id="acta_editor" contenteditable="true" style="width:100%; min-height:500px; padding:0; font-family: Arial, Helvetica, sans-serif; font-size:0.9rem; line-height:1.5; border:1px solid #e2e8f0; border-radius:8px; overflow-y:auto; outline:none; background:#fff;"></div>
            </div>
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:0.75rem; padding:0.75rem 1.5rem; border-top:1px solid #e2e8f0; flex-shrink:0;">
                <span style="flex:1; font-size:0.75rem; color:#d69e2e; align-self:center;">
                    El acta se guardará en estado PENDIENTE y deberá ser aprobada por otro usuario autorizado.
                </span>
                <button type="button" class="btn btn-secondary" id="closePreview">Volver</button>
                <button type="button" class="btn btn-success" id="confirmSave">Confirmar y Guardar Acta</button>
            </div>
        </div>
    </div>
</main>

<style>
.modal {
    position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
    background-color: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center;
    padding: 10px;
}
.modal-content {
    background-color: #fff; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    transition: background-color 0.3s;
}
[data-theme="dark"] .modal-content {
    background-color: var(--surface, #1e293b);
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
    border: 1px solid var(--border, #334155);
}
.modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; }
[data-theme="dark"] .modal-header {
    border-bottom-color: var(--border, #334155);
    color: var(--text-main, #f1f5f9);
}
[data-theme="dark"] .modal-header h3 {
    color: var(--text-main, #f1f5f9);
}
.close-modal { font-size: 1.5rem; cursor: pointer; color: #94a3b8; line-height: 1; }
.close-modal:hover { color: #1e293b; }
[data-theme="dark"] .close-modal:hover { color: var(--text-main, #f1f5f9); }

/* Editor del acta — respeta el tema */
#acta_editor {
    background: #fff;
    color: #333;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
}
[data-theme="dark"] #acta_editor {
    background: #1a2540;
    color: var(--text-main, #f1f5f9);
    border-color: var(--border, #334155);
}
#acta_editor tr {
    opacity: 1 !important;
    transform: none !important;
}
</style>

<script>
const BASE = '<?= $base_url ?>';

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formMovimiento');
    const selectTipo = document.getElementById('tipo_movimiento');
    const divDestino = document.getElementById('div_destino');
    const bienSearch = document.getElementById('bien_search');
    const bienIdHidden = document.getElementById('bien_id');
    const areaOrigenHidden = document.getElementById('area_origen_id');
    const previewBtn = document.getElementById('previewBtn');
    const previewModal = document.getElementById('previewModal');
    const actaEditor = document.getElementById('acta_editor');
    const confirmSave = document.getElementById('confirmSave');

    // Mapa de bienes para búsqueda flexible
    const bienesArr = [];
    <?php foreach ($bienes as $b):
    ?>
    bienesArr.push({
        id: <?= (int)$b['id_bien'] ?>,
        areaId: <?= (int)($b['id_area'] ?? 0) ?>,
        codigo: <?= json_encode($b['codigo_interno'], JSON_HEX_APOS | JSON_HEX_TAG) ?>,
        nombre: <?= json_encode($b['nombre'], JSON_HEX_APOS | JSON_HEX_TAG) ?>,
        serial: <?= json_encode($b['serial'] ?? '', JSON_HEX_APOS | JSON_HEX_TAG) ?>,
        estado: <?= json_encode($b['estado_nombre'] ?? '', JSON_HEX_APOS | JSON_HEX_TAG) ?>
    });
    <?php endforeach; ?>

    const bienDropdown = document.getElementById('bien_dropdown');

    function buscarBienes(query) {
        const q = query.trim().toLowerCase();
        if (q.length < 2) { bienDropdown.style.display = 'none'; return; }
        const matches = bienesArr.filter(b => {
            const cod = (b.codigo || '').toLowerCase();
            const nom = (b.nombre || '').toLowerCase();
            const ser = (b.serial || '').toLowerCase();
            return cod.includes(q) || nom.includes(q) || ser.includes(q);
        });
        if (matches.length === 0) { bienDropdown.style.display = 'none'; return; }

        // Si hay UNA sola coincidencia, auto-seleccionar sin que el usuario haga click
        if (matches.length === 1) {
            const b = matches[0];
            bienIdHidden.value = b.id;
            areaOrigenHidden.value = b.areaId;
            const texto = b.codigo + ' — ' + b.nombre;
            bienSearch.value = texto;
            bienSeleccionadoId = String(b.id);
            bienSeleccionadoTexto = texto;
            bienSearch.style.borderColor = '';
            bienDropdown.style.display = 'none';
            return;
        }

        bienDropdown.innerHTML = matches.map(b =>
            '<div data-id="' + b.id + '" data-area="' + b.areaId + '" data-codigo="' + b.codigo + '" data-nombre="' + b.nombre + '" data-estado="' + b.estado + '" style="padding:8px 12px; cursor:pointer; border-bottom:1px solid #eee; font-size:0.85rem;" onmouseover="this.style.background=\'#eff6ff\'" onmouseout="this.style.background=\'\'">' +
            '<b>' + b.codigo + '</b> — ' + b.nombre + ' <span style="color:#94a3b8; font-size:0.75rem;">(' + b.estado + ')</span>' +
            '</div>'
        ).join('');
        bienDropdown.style.display = 'block';
    }

    let bienSeleccionadoId = '';
    let bienSeleccionadoTexto = '';

    bienSearch.addEventListener('input', function() {
        buscarBienes(this.value);
        // Si el usuario borró o cambió el texto seleccionado, limpiar el id
        if (this.value.trim() !== bienSeleccionadoTexto) {
            bienIdHidden.value = '';
            areaOrigenHidden.value = '';
            bienSeleccionadoId = '';
        } else {
            bienIdHidden.value = bienSeleccionadoId;
        }
    });

    bienSearch.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) buscarBienes(this.value);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#bien_search') && !e.target.closest('#bien_dropdown')) {
            bienDropdown.style.display = 'none';
        }
    });

    bienDropdown.addEventListener('click', function(e) {
        const item = e.target.closest('[data-id]');
        if (!item) return;
        bienIdHidden.value = item.dataset.id;
        areaOrigenHidden.value = item.dataset.area;
        const texto = item.dataset.codigo + ' — ' + item.dataset.nombre;
        bienSearch.value = texto;
        bienSeleccionadoId = item.dataset.id;
        bienSeleccionadoTexto = texto;
        bienSearch.style.borderColor = '';
        bienDropdown.style.display = 'none';
    });

    // Prevenir envío normal del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
    });

    // Mostrar/Ocultar destino y responsable según tipo
    selectTipo.addEventListener('change', function() {
        divDestino.style.display = (this.value === 'traslado') ? 'block' : 'none';
        document.getElementById('div_responsable').style.display = (this.value === 'asignacion') ? 'block' : 'none';
    });

    // Previsualizar Acta
    previewBtn.addEventListener('click', async function() {
        const formData = new FormData(form);
        
        if (!formData.get('bien_id')) {
            alert('Debe seleccionar un bien válido de la lista.');
            bienSearch.focus();
            return;
        }
        if (!formData.get('tipo_movimiento') || !formData.get('motivo')) {
            alert('Por favor complete todos los campos obligatorios antes de previsualizar.');
            return;
        }

        if (formData.get('tipo_movimiento') === 'asignacion') {
            if (!formData.get('responsable_ci') || !formData.get('responsable_nombre') || !formData.get('responsable_apellido')) {
                alert('Para asignación directa, los datos del responsable (CI, Nombre, Apellido) son obligatorios.');
                return;
            }
        }

        previewBtn.disabled = true;
        previewBtn.textContent = 'Generando...';

        try {
            const res = await fetch(BASE + '/movimientos/previsualizar', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                actaEditor.innerHTML = data.html;
                previewModal.style.display = 'flex';
            } else {
                alert(data.error || 'Error al generar previsualización');
            }
        } catch (error) {
            alert('Error de conexión');
        } finally {
            previewBtn.disabled = false;
            previewBtn.textContent = '👁 Previsualizar y Editar Acta';
        }
    });

    // Cerrar Modal
    document.querySelector('.close-modal').onclick = () => previewModal.style.display = 'none';
    document.getElementById('closePreview').onclick = () => previewModal.style.display = 'none';

    // Guardar Acta Final
    confirmSave.addEventListener('click', async function() {
        const formData = new FormData(form);
        formData.append('contenido_acta', actaEditor.innerHTML);

        confirmSave.disabled = true;
        confirmSave.textContent = 'Guardando...';

        try {
            const res = await fetch(BASE + '/movimientos', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                window.location.href = BASE + data.redirect;
            } else {
                let msg = data.error || '';
                if (data.errors) {
                    msg = Object.values(data.errors).join('\n');
                }
                alert(msg || 'Error al guardar');
                confirmSave.disabled = false;
                confirmSave.textContent = 'Confirmar y Guardar Acta';
            }
        } catch (error) {
            alert('Error de conexión');
            confirmSave.disabled = false;
            confirmSave.textContent = 'Confirmar y Guardar Acta';
        }
    });
});
</script>