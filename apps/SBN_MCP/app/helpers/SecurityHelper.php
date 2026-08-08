<?php
/**
 * Helper de Seguridad
 * Sistema de Gestión de Bienes Nacionales
 * Cumplimiento OWASP Top 10
 */

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Hashear contraseña usando bcrypt
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verificar contraseña
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Verificar si necesita re-hasheo
     */
    public static function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Sanitizar entrada para prevenir XSS
     */
    public static function sanitizeInput(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitizar para contexto HTML
     */
    public static function escapeHtml(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Validar que una cadena solo contenga caracteres seguros
     */
    public static function validateAlphanumeric(string $input): bool
    {
        return preg_match('/^[a-zA-Z0-9\s\-_]+$/', $input);
    }

    /**
     * Validar número de cédula venezolana
     * Formato: V-12345678, E-12345678, o 12345678
     */
    public static function validateCedula(string $cedula): bool
    {
        return preg_match('/^[VEve]?-[0-9]{5,8}$|^[0-9]{5,8}$/', $cedula);
    }

    /**
     * Generar token CSRF
     */
    public static function generateCSRFToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Validar token CSRF
     */
    public static function validateCSRFToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['csrf_token']) &&
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Generar campo oculto HTML para formularios
     */
    public static function csrfField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' .
               self::generateCSRFToken() . '">';
    }

    /**
     * Limpiar salida para evitar XSS
     */
    public static function cleanOutput($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'cleanOutput'], $data);
        }

        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validar email
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validar URL
     */
    public static function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Generar token aleatorio seguro
     */
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Validar fecha
     */
    public static function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Validar número decimal
     */
    public static function validateDecimal(string $value): bool
    {
        return preg_match('/^\d+(\.\d{1,2})?$/', $value);
    }

    /**
     * Escapar salida para JSON
     */
    public static function jsonEncode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    /**
     * Verificar permisos de rol
     */
    public static function hasPermission(array $permissions, string $permission): bool
    {
        return in_array($permission, $permissions);
    }

    /**
     * Generar hash para API
     */
    public static function hashApiKey(string $key): string
    {
        return hash('sha256', $key);
    }

    /**
     * Sanitiza HTML rico (proveniente del editor de actas) eliminando
     * únicamente lo peligroso: scripts, iframes, event handlers (on*),
     * javascript:, data: URI, expression() CSS.
     * No usa DOMDocument (que rompe el HTML de Word). Enfoque regex.
     */
    public static function sanitizeRichHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $result = $html;

        // 1. Eliminar etiquetas completamente peligrosas (contenido y todo)
        $dangerousTags = [
            'script', 'style', 'iframe', 'object', 'embed', 'applet',
            'form', 'link', 'meta', 'base', 'noscript', 'template',
        ];
        foreach ($dangerousTags as $tag) {
            $result = preg_replace(
                '#<' . $tag . '\b[^>]*>.*?</' . $tag . '>#is',
                '',
                $result
            );
            // Auto-cierre: <script ... />
            $result = preg_replace('#<' . $tag . '\b[^>]*/>#is', '', $result);
        }

        // 2. Eliminar etiquetas peligrosas sin contenido
        $result = preg_replace('#<\s*(html|head|body)\b[^>]*>#i', '', $result);
        $result = preg_replace('#<\s*/(html|head|body)\s*>#i', '', $result);

        // 3. Eliminar event handlers: onclick, onerror, onload, onmouseover, etc.
        $result = preg_replace('#\s+on[a-z]+\s*=\s*["\'][^"\']*["\']#i', '', $result);
        $result = preg_replace('#\s+on[a-z]+\s*=\s*\S+#i', '', $result);

        // 4. Eliminar javascript:, vbscript:, data: en href/src (excepto data:image)
        $result = preg_replace(
            '#((?:href|src|action)\s*=\s*)(["\'])\s*javascript\s*:#i',
            '$1$2#',
            $result
        );
        $result = preg_replace(
            '#((?:href|src|action)\s*=\s*)(["\'])\s*vbscript\s*:#i',
            '$1$2#',
            $result
        );
        // data:URI excepto imágenes
        $result = preg_replace(
            '#((?:href|src|action)\s*=\s*)(["\'])\s*data\s*:(?!image/)#i',
            '$1$2#',
            $result
        );

        // 5. Eliminar expression() y -moz-binding en CSS inline
        $result = preg_replace('#expression\s*\([^)]*\)#i', '', $result);
        $result = preg_replace('#-moz-binding\s*:[^;]*;?#i', '', $result);

        // 6. Neutralizar @import en style
        $result = preg_replace('#@import\b[^;]*;?#i', '', $result);

        return $result;
    }
}
