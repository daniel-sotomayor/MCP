<?php
/**
 * =============================================================================
 * LAYOUT PRINCIPAL: FOOTER
 * =============================================================================
 * 
 * Este archivo cierra la estructura HTML iniciada en header.php.
 * Contiene:
 * - Cierre de contenedores principales según el tipo de página
 * - Footer público (solo para páginas no autenticadas)
 * - Sistema de notificaciones Toast (JavaScript)
 * - Gestión del tema claro/oscuro
 * - Animaciones de entrada al hacer scroll
 * - Cierre de tags HTML (body, html)
 * 
 * Variables esperadas:
 * @var bool $is_app Indica si es página interna del sistema
 * =============================================================================
 */
?>

    <?php if(isset($is_app)): ?>
        <!-- =====================================================================
            CIERRE DEL LAYOUT DE APLICACIÓN
            =====================================================================
            Se cierra .main-content (abierto en sidebar.php) y .app-layout
            (abierto en header.php). El sidebar se incluye antes del main-content.
            ===================================================================== -->
            </div> <!-- .main-content -->
        </div> <!-- .app-layout -->
    <?php else: ?>
        <!-- =====================================================================
            FOOTER PÚBLICO
            =====================================================================
            Se muestra solo en páginas públicas (landing, login, etc.)
            No aparece en el sistema interno (is_app)
            ===================================================================== -->
        </main>
        <footer class="public-footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-info">
                        <h3>Maternidad Concepción Palacios</h3>
                        <p>Sistema de Gestión de Bienes Nacionales. Control y transparencia para nuestra institución.</p>
                    </div>
                    <div class="footer-copy">
                        <p>&copy; <?= date('Y') ?> MCP. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <!-- =========================================================================
        SCRIPTS JAVASCRIPT GLOBALES
        =========================================================================
        Todo el JavaScript se ejecuta en modo estricto ('use strict') para
        evitar errores comunes y mejorar el rendimiento.
        ========================================================================= -->
    <script>
    'use strict';

    /**
     * =============================================================================
     * SISTEMA DE NOTIFICACIONES TOAST
     * =============================================================================
     * 
     * Muestra notificaciones temporales en la esquina superior derecha.
     * Tipos disponibles: success, error, warning, info
     * 
     * Uso:
     *   Toast.success('Operación completada');
     *   Toast.error('Ha ocurrido un error');
     *   Toast.warning('Advertencia importante');
     *   Toast.info('Información relevante');
     * 
     * Las notificaciones desaparecen automáticamente después de 4 segundos.
     * =============================================================================
     */
    const Toast = (() => {
        // Contenedor donde se inyectarán las notificaciones
        const container = document.getElementById('toastContainer');
        
        /**
         * Muestra una notificación toast
         * @param {string} message - Texto a mostrar
         * @param {string} type - Tipo de notificación (success, error, warning, info)
         */
        function show(message, type = 'info') {
            if (!container) return;
            
            // Crear elemento toast
            const t = document.createElement('div');
            t.className = `toast toast-${type} slide-in-right`;
            
            // Iconos SVG para cada tipo de notificación
            const icons = {
                success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
                error: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
                warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                info: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
            };

            // HTML interno del toast
            t.innerHTML = `
                <div class="toast-icon">${icons[type] || icons.info}</div>
                <div class="toast-body">${message}</div>
                <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
            `;

            // Añadir al contenedor
            container.appendChild(t);
            
            // Auto-eliminar después de 4 segundos con animación de salida
            setTimeout(() => {
                t.classList.replace('slide-in-right', 'fade-out');
                setTimeout(() => t.remove(), 500);
            }, 4000);
        }

        // API pública del sistema de Toast
        return {
            show,
            success: m => show(m, 'success'),
            error: m => show(m, 'error'),
            warning: m => show(m, 'warning'),
            info: m => show(m, 'info')
        };
    })();
    // Exponer globalmente para uso en otras partes del sistema
    window.Toast = Toast;

    /**
     * =============================================================================
     * INICIALIZACIÓN DE UI AL CARGAR EL DOM
     * =============================================================================
     * 
     * Se ejecuta cuando el DOM está completamente cargado.
     * Configura:
     * - Toggle del sidebar en móvil
     * - Cambio de tema claro/oscuro
     * - Animaciones de entrada al hacer scroll
     * =============================================================================
     */
    /**
     * =============================================================================
     * CIERRE DE SESIÓN POR INACTIVIDAD (10 MINUTOS)
     * =============================================================================
     * 
     * Si el usuario no interactúa con la página (mouse, teclado, clic, scroll,
     * toque), la sesión se cerrará automáticamente:
     *  - A los 9 minutos se muestra una advertencia.
     *  - A los 10 minutos se redirige al login (el servidor también valida esto).
     *  - Cualquier actividad reinicia el contador.
     * 
     * El servidor aplica la misma regla (Session::IDLE_TIMEOUT), por lo que
     * esta es una capa de UX adicional a la seguridad real del backend.
     * =============================================================================
     */
    (function () {
        <?php if (isset($is_app)): ?>
        const IDLE_LIMIT = 10 * 60 * 1000;      // 10 minutos
        const WARN_AT   = 9 * 60 * 1000;       // advertencia a los 9 min
        const BASE      = <?= json_encode($base_url ?? '') ?>;
        let idleTimer   = null;
        let warnTimer   = null;
        let warned      = false;

        function expirarSesion() {
            if (warned) return;
            warned = true;
            if (window.Toast) {
                Toast.error('Su sesión expiró por inactividad. Será redirigido al inicio de sesión.');
            }
            setTimeout(() => { window.location.href = BASE + '/login?expired=1'; }, 1200);
        }

        function reiniciarTemporizador() {
            clearTimeout(idleTimer);
            clearTimeout(warnTimer);
            warned = false;
            // Ocultar aviso si existe
            const aviso = document.getElementById('idleWarning');
            if (aviso) aviso.remove();
            idleTimer = setTimeout(expirarSesion, IDLE_LIMIT);
            warnTimer = setTimeout(() => {
                if (warned) return;
                if (window.Toast) {
                    Toast.warning('Por inactividad, su sesión se cerrará en 1 minuto. Mueva el mouse o presione una tecla para continuar.');
                }
            }, WARN_AT);
        }

        ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(evt => {
            document.addEventListener(evt, reiniciarTemporizador, { passive: true });
        });
        reiniciarTemporizador();

        // ── Logout al cerrar pestaña (beforeunload + beacon) ──
        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon(BASE + '/logout-beacon');
        });
        <?php endif; ?>
    })();

    // =========================================================================
    // INICIALIZACIÓN DE UI AL CARGAR EL DOM
    // =========================================================================
    document.addEventListener('DOMContentLoaded', () => {
        // Elementos del DOM para manipulación
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const btnToggle = document.getElementById('hamburgerBtn');
        const themeToggle = document.getElementById('themeToggle');
        
        // -------------------------------------------------------------------------
        // CONTROL DEL SIDEBAR (MÓVIL)
        // -------------------------------------------------------------------------
        // Abrir/cerrar sidebar al hacer clic en el botón hamburguesa
        if (btnToggle && sidebar) {
            btnToggle.addEventListener('click', () => {
                sidebar.classList.toggle('mobile-open');
                if (overlay) overlay.classList.toggle('visible');
            });
        }

        // Cerrar sidebar al hacer clic en el overlay (fuera del sidebar)
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('visible');
            });
        }

        // -------------------------------------------------------------------------
        // CONTROL DEL TEMA CLARO/OSCURO
        // -------------------------------------------------------------------------
        if (themeToggle) {
            // Iconos de sol y luna dentro del botón
            const sunIcon = themeToggle.querySelector('.sun-icon');
            const moonIcon = themeToggle.querySelector('.moon-icon');

            /**
             * Actualiza la visibilidad de los iconos según el tema actual
             * @param {string} theme - 'dark' o 'light'
             */
            const themeToggleText = document.getElementById('themeToggleText');

            /**
             * Actualiza iconos y texto del botón según el tema activo.
             * En modo oscuro → muestra Sol (para cambiar a claro)
             * En modo claro  → muestra Luna (para cambiar a oscuro)
             * @param {string} theme - 'dark' o 'light'
             */
            const updateIcons = (theme) => {
                if (theme === 'dark') {
                    // Estamos en oscuro: mostrar Sol (indica "cambiar a claro")
                    if (sunIcon) sunIcon.style.display = 'block';
                    if (moonIcon) moonIcon.style.display = 'none';
                    if (themeToggleText) themeToggleText.textContent = 'Modo Claro';
                } else {
                    // Estamos en claro: mostrar Luna (indica "cambiar a oscuro")
                    if (sunIcon) sunIcon.style.display = 'none';
                    if (moonIcon) moonIcon.style.display = 'block';
                    if (themeToggleText) themeToggleText.textContent = 'Modo Oscuro';
                }
            };

            // Establecer iconos según tema guardado al cargar
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateIcons(currentTheme);

            // Evento de clic para cambiar tema
            themeToggle.addEventListener('click', () => {
                const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcons(newTheme);
                
                // Notificar cambio al usuario con mensaje descriptivo
                Toast.info(newTheme === 'dark' ? '🌙 Tema oscuro activado' : '☀️ Tema claro activado');
            });
        }

        /**
         * =============================================================================
         * ANIMACIONES DE ENTRADA AL HACER SCROLL
         * =============================================================================
         * 
         * Observa elementos con clase .card, .metric-card, y filas de tabla
         * Les añade la clase 'animate-in' cuando entran en el viewport.
         * 
         * Esto crea un efecto de "aparición progresiva" al hacer scroll.
         * =============================================================================
         */
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target); // Solo animar una vez
                }
            });
        }, observerOptions);

        // Observar todos los elementos que deben animarse
        document.querySelectorAll('.card, .metric-card, .data-table tr').forEach(el => {
            observer.observe(el);
        });
    });
    </script>

    <?php if(isset($is_app)): ?>
    <script>
    'use strict';
    (function() {
        var BASE = <?= json_encode($base_url ?? '') ?>;
        var bellBadge = document.getElementById('notifBadge');
        var notifCount = parseInt(bellBadge ? bellBadge.textContent : '0', 10) || 0;
        var pollTimer = null;

        function updateBadge(count) {
            notifCount = count;
            if (bellBadge) {
                bellBadge.textContent = count;
                bellBadge.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        function checkNotifications() {
            fetch(BASE + '/notificaciones/count')
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    var newCount = d.count || 0;
                    if (newCount > notifCount && window.Toast) {
                        Toast.info('Tiene ' + newCount + ' notificación(es) nueva(s)');
                    }
                    updateBadge(newCount);
                })
                .catch(function(){});
        }

        // Poll cada 30 segundos
        checkNotifications();
        pollTimer = setInterval(checkNotifications, 30000);

        var bellBtn = document.getElementById('notifBell');
        if (bellBtn) {
            bellBtn.addEventListener('click', function() {
                if (notifCount > 0) {
                    fetch(BASE + '/notificaciones/read-all', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-Token': '<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>'
                        },
                        body: 'csrf_token=<?= urlencode($_SESSION['csrf_token'] ?? '') ?>'
                    }).then(function() { updateBadge(0); }).catch(function(){});
                }
            });
        }
    })();
    </script>
    <?php endif; ?>
</body>
</html>