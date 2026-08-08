<?php
/**
 * Vista: Configuración del Sistema
 *
 * @var array  $params     Parámetros del sistema
 * @var string $csrf_token Token CSRF
 * @var string $base_url   URL base
 */

require APP_PATH . '/views/partials/sidebar.php';

// Organizar parámetros por sección
$emailKeys = ['mail_from_email','mail_from_name','mail_host','mail_port','mail_username','mail_password','mail_encryption'];
$appKeys   = ['app_name','app_url'];
$segKeys   = ['session_timeout','login_max_attempts'];

$paramsMap = [];
foreach ($params as $p) { $paramsMap[$p['clave']] = $p; }
?>
<main class="main-content">
    <div class="page-header">
        <h1>Configuración del Sistema</h1>
    </div>

    <div style="max-width:780px">
        <div id="form-alert" style="display:none;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;font-size:.9rem"></div>

        <form id="form-config" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <!-- ═══ CORREO ELECTRÓNICO ═══ -->
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.5rem"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        Correo Electrónico (SMTP)
                    </h3>
                </div>
                <div class="card-body">
                    <p style="font-size:.85rem;color:#718096;margin-bottom:1.25rem">
                        Configuración del correo institucional o Google para enviar notificaciones y códigos de recuperación de contraseña.
                    </p>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <!-- mail_from_email -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Correo Remitente *</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Dirección del correo que envía las notificaciones</small>
                            <input type="email" name="config[mail_from_email]"
                                value="<?= htmlspecialchars($paramsMap['mail_from_email']['valor'] ?? '') ?>"
                                class="form-control" placeholder="sistema@mcp.gob.ve">
                        </div>
                        <!-- mail_from_name -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Nombre Remitente</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Aparece como remitente del correo</small>
                            <input type="text" name="config[mail_from_name]"
                                value="<?= htmlspecialchars($paramsMap['mail_from_name']['valor'] ?? '') ?>"
                                class="form-control" placeholder="Sistema Bienes Nacionales">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
                        <!-- mail_host -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Servidor SMTP</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Servidor de salida de correo</small>
                            <input type="text" name="config[mail_host]"
                                value="<?= htmlspecialchars($paramsMap['mail_host']['valor'] ?? '') ?>"
                                class="form-control" placeholder="smtp.gmail.com">
                        </div>
                        <!-- mail_port -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Puerto SMTP</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Puerto del servidor (587 para TLS, 465 para SSL)</small>
                            <input type="number" name="config[mail_port]"
                                value="<?= htmlspecialchars($paramsMap['mail_port']['valor'] ?? '587') ?>"
                                class="form-control" min="1" max="65535">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem">
                        <!-- mail_username -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Usuario SMTP *</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Correo completo (ej: usuario@gmail.com)</small>
                            <input type="email" name="config[mail_username]"
                                value="<?= htmlspecialchars($paramsMap['mail_username']['valor'] ?? '') ?>"
                                class="form-control" placeholder="correo@gmail.com">
                        </div>
                        <!-- mail_password -->
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Contraseña SMTP *</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Contraseña o contraseña de aplicación (Google)</small>
                            <input type="password" name="config[mail_password]"
                                value="<?= htmlspecialchars($paramsMap['mail_password']['valor'] ?? '') ?>"
                                class="form-control" placeholder="••••••••">
                        </div>
                    </div>

                    <div style="margin-top:1rem">
                        <!-- mail_encryption -->
                        <div class="form-group" style="max-width:200px">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Cifrado SMTP</label>
                            <select name="config[mail_encryption]" class="form-control">
                                <option value="tls" <?= ($paramsMap['mail_encryption']['valor'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (recomendado)</option>
                                <option value="ssl" <?= ($paramsMap['mail_encryption']['valor'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ DATOS DEL SISTEMA ═══ -->
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.5rem"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Datos del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Nombre del Sistema</label>
                            <input type="text" name="config[app_name]"
                                value="<?= htmlspecialchars($paramsMap['app_name']['valor'] ?? '') ?>"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">URL Pública</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">URL completa donde está accesible el sistema</small>
                            <input type="url" name="config[app_url]"
                                value="<?= htmlspecialchars($paramsMap['app_url']['valor'] ?? '') ?>"
                                class="form-control" placeholder="https://mcp.gob.ve/sbn">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ SEGURIDAD ═══ -->
            <div class="card" style="margin-bottom:1.5rem">
                <div class="card-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:.5rem"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Seguridad
                    </h3>
                </div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Timeout de Sesión (min)</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Minutos de inactividad antes de cerrar sesión</small>
                            <input type="number" name="config[session_timeout]"
                                value="<?= htmlspecialchars($paramsMap['session_timeout']['valor'] ?? '10') ?>"
                                class="form-control" min="1" max="60">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;font-size:.85rem">Máx. Intentos de Login</label>
                            <small style="display:block;color:#718096;margin-bottom:.3rem">Intentos fallidos antes de bloquear temporalmente</small>
                            <input type="number" name="config[login_max_attempts]"
                                value="<?= htmlspecialchars($paramsMap['login_max_attempts']['valor'] ?? '5') ?>"
                                class="form-control" min="1" max="20">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:1rem;margin-top:1.5rem">
                <button type="submit" id="btn-save" class="btn btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:.3rem"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</main>

<script>
'use strict';
document.getElementById('form-config').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-save');
    const origText = btn.innerHTML;
    btn.disabled = true; btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite;margin-right:.3rem"><circle cx="12" cy="12" r="10" stroke-dasharray="30 60"/></svg> Guardando...';
    const al = document.getElementById('form-alert');
    try {
        const res  = await fetch('<?= $base_url ?>/configuracion', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: new URLSearchParams(new FormData(this)).toString()
        });
        const data = await res.json();
        al.textContent = data.message || (data.error || 'Error');
        al.style.cssText = 'display:block;padding:.75rem 1rem;border-radius:6px;font-size:.9rem;background:' + (data.success ? '#f0fff4;border:1px solid #9ae6b4;color:#276749' : '#fff5f5;border:1px solid #fed7d7;color:#c53030');
        if (data.success && window.Toast) Toast.success(data.message);
    } catch(err) {
        al.textContent = 'Error de conexión';
        al.style.cssText = 'display:block;padding:.75rem 1rem;border-radius:6px;font-size:.9rem;background:#fff5f5;border:1px solid #fed7d7;color:#c53030';
    }
    btn.disabled = false; btn.innerHTML = origText;
});
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
