<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña — MCP</title>
    <link rel="icon" type="image/svg+xml" href="<?= $base_url ?? '' ?>/img/favicon.svg">
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/auth.css">
</head>
<body>

<div class="auth-bg">
    <div class="auth-bg-orb"></div>
    <div class="auth-bg-orb"></div>
    <div class="auth-bg-orb"></div>
</div>

<main class="auth-card" role="main">

    <header class="auth-header">
        <div class="auth-logo-wrap">
            <img src="<?= $base_url ?? '' ?>/img/logo-mcp.svg"
                 alt="Maternidad Concepción Palacios" width="44" height="44">
        </div>
        <h1>Cambio de Contraseña Requerido</h1>
        <p>Por seguridad, establezca una nueva contraseña antes de continuar.</p>
    </header>

    <form id="cambiarClaveForm" novalidate aria-label="Formulario de cambio de contraseña">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">

        <div class="auth-field">
            <label for="nueva_clave">Nueva contraseña</label>
            <div class="password-wrapper">
                <input type="password" id="nueva_clave" name="nueva_clave"
                       class="auth-input" placeholder="Mínimo 8 caracteres"
                       required autocomplete="off"
                       aria-required="true" aria-describedby="nueva_clave-error pwd-strength-label">
                <button type="button" class="toggle-pwd" aria-label="Mostrar contraseña" aria-pressed="false"
                        data-target="nueva_clave">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <span class="form-error" id="nueva_clave-error" role="alert" aria-live="polite"></span>

            <div class="pwd-strength" aria-live="polite">
                <div class="pwd-strength-bar">
                    <div class="pwd-strength-fill" id="strengthFill"></div>
                </div>
                <span class="pwd-strength-label" id="pwd-strength-label"></span>
            </div>

            <ul class="pwd-rules" id="pwdRules" aria-label="Requisitos de contraseña">
                <li id="rule-len"     class="rule">Mínimo 8 caracteres</li>
                <li id="rule-upper"   class="rule">Al menos una mayúscula (A–Z)</li>
                <li id="rule-lower"   class="rule">Al menos una minúscula (a–z)</li>
                <li id="rule-num"     class="rule">Al menos un número (0–9)</li>
                <li id="rule-special" class="rule">Al menos un carácter especial (!@#$…)</li>
            </ul>
        </div>

        <div class="auth-field">
            <label for="confirmar_clave">Confirmar contraseña</label>
            <div class="password-wrapper">
                <input type="password" id="confirmar_clave" name="confirmar_clave"
                       class="auth-input" placeholder="Repita la contraseña"
                       required autocomplete="off"
                       aria-required="true" aria-describedby="confirmar_clave-error">
                <button type="button" class="toggle-pwd" aria-label="Mostrar confirmación" aria-pressed="false"
                        data-target="confirmar_clave">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <span class="form-error" id="confirmar_clave-error" role="alert" aria-live="polite"></span>
        </div>

        <button type="submit" class="auth-btn" id="btnGuardar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span class="btn-text">Guardar Nueva Contraseña</span>
            <span class="auth-spinner" aria-hidden="true"></span>
        </button>

        <div id="formMessage" class="auth-message" role="alert" aria-live="assertive"></div>

    </form>

    <footer class="auth-footer">
        <p>Sistema de Gestión de Bienes Nacionales<br>Maternidad Concepción Palacios</p>
    </footer>

</main>

<script>
'use strict';
(function () {
    const BASE     = '<?= ($base_url ?? '') ?>';
    const form     = document.getElementById('cambiarClaveForm');
    const btn      = document.getElementById('btnGuardar');
    const msgDiv   = document.getElementById('formMessage');
    const pwdInput = document.getElementById('nueva_clave');
    const cfmInput = document.getElementById('confirmar_clave');

    document.querySelectorAll('.toggle-pwd').forEach(function (b) {
        b.addEventListener('click', function () {
            const t = document.getElementById(this.dataset.target);
            const vis = t.type === 'text';
            t.type = vis ? 'password' : 'text';
            this.setAttribute('aria-pressed', String(!vis));
        });
    });

    const RULES = {
        'rule-len':     v => v.length >= 8,
        'rule-upper':   v => /[A-Z]/.test(v),
        'rule-lower':   v => /[a-z]/.test(v),
        'rule-num':     v => /[0-9]/.test(v),
        'rule-special': v => /[\W_]/.test(v),
    };
    const LABELS  = ['', 'Muy débil', 'Débil', 'Aceptable', 'Buena', 'Fuerte'];
    const CLASSES = ['', 'weak', 'weak', 'fair', 'good', 'strong'];

    pwdInput.addEventListener('input', function () {
        const v = this.value;
        let score = 0;
        Object.entries(RULES).forEach(([id, fn]) => {
            const ok = fn(v);
            document.getElementById(id).className = 'rule ' + (v.length === 0 ? '' : ok ? 'ok' : 'bad');
            if (ok) score++;
        });
        document.getElementById('strengthFill').className  = 'pwd-strength-fill ' + (v.length ? CLASSES[score] : '');
        document.getElementById('pwd-strength-label').textContent = v.length ? LABELS[score] : '';
        document.getElementById('nueva_clave-error').textContent = '';
        this.classList.remove('is-invalid');
    });

    cfmInput.addEventListener('input', function () {
        document.getElementById('confirmar_clave-error').textContent = '';
        this.classList.remove('is-invalid');
    });

    function setLoading(on) {
        btn.disabled = on;
        btn.classList.toggle('loading', on);
    }
    function showMsg(text, type) {
        msgDiv.textContent = text;
        msgDiv.className   = 'auth-message ' + type;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        ['nueva_clave','confirmar_clave'].forEach(id => {
            document.getElementById(id+'-error').textContent = '';
            document.getElementById(id).classList.remove('is-invalid');
        });
        msgDiv.className = 'auth-message';

        const pwd = pwdInput.value;
        const cfm = cfmInput.value;
        let valid = true;

        if (Object.values(RULES).some(fn => !fn(pwd))) {
            document.getElementById('nueva_clave-error').textContent = 'La contraseña no cumple todos los requisitos';
            pwdInput.classList.add('is-invalid');
            valid = false;
        }
        if (pwd !== cfm) {
            document.getElementById('confirmar_clave-error').textContent = 'Las contraseñas no coinciden';
            cfmInput.classList.add('is-invalid');
            valid = false;
        }
        if (!valid) return;

        setLoading(true);
        try {
            const res  = await fetch(BASE + '/cambiar-clave', { method:'POST', body: new FormData(this) });
            const data = await res.json();
            if (data.success) {
                showMsg(data.message || 'Contraseña actualizada correctamente.', 'success');
                setTimeout(function () { window.location.href = BASE + (data.redirect || '/dashboard'); }, 1200);
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([k, v]) => {
                    var errEl = document.getElementById(k + '-error');
                    var input = document.getElementById(k);
                    if (errEl) errEl.textContent = v;
                    if (input) input.classList.add('is-invalid');
                });
                setLoading(false);
            } else {
                showMsg(data.error || 'Error al cambiar la contraseña.', 'error');
                setLoading(false);
            }
        } catch (err) {
            showMsg('Error de conexión. Intente nuevamente.', 'error');
            setLoading(false);
        }
    });
})();
</script>
</body>
</html>
