<?php
/**
 * =============================================================================
 * LAYOUT PRINCIPAL: HEADER
 * =============================================================================
 * 
 * Este archivo es la cabecera HTML compartida por todas las páginas del sistema.
 * Se encarga de:
 * - Definir la estructura HTML5 base
 * - Cargar las hojas de estilo CSS según el tipo de página
 * - Inicializar el tema (claro/oscuro) antes de renderizar
 * - Configurar el contenedor de notificaciones (toasts)
 * 
 * Variables esperadas:
 * @var string $title      Título de la página (se muestra en la pestaña)
 * @var string $base_url   URL base de la aplicación para recursos estáticos
 * @var bool   $is_app     Indica si es página interna (carga app.css)
 * @var bool   $is_auth    Indica si es página de autenticación (carga auth.css)
 * 
 * Ejemplo de uso en controladores:
 * $data = [
 *     'title'    => 'Dashboard',
 *     'is_app'   => true,
 *     'base_url' => '/sbn_mcp'
 * ];
 * View::render('dashboard/index', $data);
 * =============================================================================
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta tags esenciales para responsive y compatibilidad -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Título dinámico con fallback seguro usando htmlspecialchars para prevenir XSS -->
    <title><?= htmlspecialchars($title ?? 'Sistema de Bienes Nacionales') ?> — MCP</title>
    
    <!-- Favicon en formato SVG para mejor calidad en cualquier tamaño -->
    <link rel="icon" type="image/svg+xml" href="<?= $base_url ?? '' ?>/img/favicon.svg">
    
    <!-- Google Fonts: preconnect + preload (elimina render-blocking de @import) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"></noscript>
    
    <!-- Meta descripción para SEO -->
    <meta name="description" content="Sistema de Gestión de Bienes Nacionales — Maternidad Concepción Palacios">
    
    <!-- ==========================================================================
        SCRIPT DE TEMA: Se ejecuta inmediatamente para evitar parpadeo de tema
        ==========================================================================
        Este script se ejecuta antes de que el DOM se renderice completamente,
        aplicando el tema guardado en localStorage para evitar el "flash" de
        tema incorrecto al cargar la página.
        ========================================================================== -->
    <script>
        (function() {
            // Recuperar tema guardado o usar 'light' como valor por defecto
            const savedTheme = localStorage.getItem('theme') || 'light';
            // Aplicar tema al elemento HTML raíz
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- ==========================================================================
        HOJAS DE ESTILO CSS
        ==========================================================================
        Se cargan diferentes CSS según el tipo de página:
        - main.css: Siempre se carga (estilos base, landing page)
        - app.css: Solo páginas internas del sistema (dashboard, bienes, etc.)
        - auth.css: Solo páginas de autenticación (login, recuperar clave)
        ========================================================================== -->
    <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/main.css" media="all">
    <?php if(isset($is_app)): ?>
        <!-- Estilos específicos para el sistema interno -->
        <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/app.css" media="all">
    <?php endif; ?>
    <?php if(isset($is_auth)): ?>
        <!-- Estilos específicos para páginas de autenticación -->
        <link rel="stylesheet" href="<?= $base_url ?? '' ?>/css/auth.css" media="all">
    <?php endif; ?>
</head>

<!-- ==========================================================================
    CLASE DEL BODY
    ==========================================================================
    Se añade una clase según el modo para facilitar estilos específicos:
    - app-mode: Páginas internas con sidebar
    - auth-mode: Páginas de login/recuperación
    - public-mode: Landing page y páginas públicas
    ========================================================================== -->
<body class="<?= isset($is_app) ? 'app-mode' : (isset($is_auth) ? 'auth-mode' : 'public-mode') ?>">
    
    <?php if(isset($is_app)): ?>
        <div class="sidebar-overlay" id="sidebarOverlay" role="presentation" aria-hidden="true"></div>
        <!-- Topbar fija para móvil -->
        <div id="mobileTopbar" style="display:none;position:fixed;top:0;left:0;right:0;height:56px;background:#0f172a;z-index:9990;align-items:center;padding:0 1rem;gap:0.75rem;box-shadow:0 2px 12px rgba(0,0,0,.4);border-bottom:1px solid rgba(255,255,255,.08);">
            <button id="hamburgerBtn" aria-label="Abrir menú" style="background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);color:#fff;cursor:pointer;padding:0;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;width:40px;height:40px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <span style="color:#fff;font-weight:700;font-size:0.95rem;flex:1;font-family:Inter,system-ui,sans-serif;">Bienes Nacionales</span>
        </div>
        <script>if(window.innerWidth<=1024){document.getElementById('mobileTopbar').style.display='flex';}</script>
        <div class="app-layout">
    <?php else: ?>
        <!-- Layout público (landing page, login, etc.) -->
        <main class="public-layout">
    <?php endif; ?>

    <!-- =========================================================================
        CONTENEDOR DE NOTIFICACIONES (TOASTS)
        =========================================================================
        Aquí se inyectan dinámicamente las notificaciones del sistema.
        El atributo aria-live="polite" asegura que lectores de pantalla
        anuncien las notificaciones sin interrumpir al usuario.
        ========================================================================= -->
    <div class="toast-container" id="toastContainer" aria-live="polite"></div>