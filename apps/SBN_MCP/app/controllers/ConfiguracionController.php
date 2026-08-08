<?php declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\AuditTrait;

class ConfiguracionController extends Controller
{
    use AuditTrait;

    /** Tipos de dato permitidos en configuración */
    private const ALLOWED_TYPES = ['string', 'integer', 'float', 'boolean'];

    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar CSRF antes de cualquier procesamiento
            if (!$this->verifyCSRFToken($this->getInput('csrf_token'))) {
                $this->json(['error' => 'Token de seguridad inválido'], 403);
                return;
            }
            $this->update();
            return;
        }

        $params = Database::fetchAll(
            'SELECT * FROM configuracion WHERE editable = TRUE ORDER BY id_configuracion'
        );

        $this->title = 'Configuración del Sistema';
        $this->renderWithLayout('configuracion/index', [
            'params'     => $params,
            'csrf_token' => $this->generateCSRFToken(),
        ]);
    }

    private function update(): void
    {
        $valores = $_POST['config'] ?? [];
        if (!is_array($valores)) {
            $this->json(['error' => 'Datos inválidos'], 400);
            return;
        }

        foreach ($valores as $clave => $valor) {
            // Validar que la clave sea alfanumérica (previene SQL injection en nombre de clave)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', (string) $clave)) {
                continue;
            }

            $config = Database::fetch(
                'SELECT tipo_dato FROM configuracion WHERE clave = :c AND editable = TRUE',
                ['c' => (string) $clave]
            );
            if (!$config) continue;

            // Validar tipo de dato
            if (!in_array($config['tipo_dato'], self::ALLOWED_TYPES, true)) continue;

            $valor = trim((string) $valor);

            if ($config['tipo_dato'] === 'integer') {
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) continue;
                $valor = (string)(int) $valor;
            } elseif ($config['tipo_dato'] === 'float') {
                if (filter_var($valor, FILTER_VALIDATE_FLOAT) === false) continue;
                $valor = (string)(float) $valor;
            } elseif ($config['tipo_dato'] === 'boolean') {
                $valor = in_array(strtolower($valor), ['1', 'true', 'on', 'yes'], true) ? '1' : '0';
            }

            Database::query(
                'UPDATE configuracion SET valor = :v WHERE clave = :c AND editable = TRUE',
                ['v' => $valor, 'c' => (string) $clave]
            );
        }

        // Reiniciar el mailer para que tome la nueva configuración
        \App\Helpers\EmailHelper::resetMailer();

        $this->logAudit('UPDATE', 'configuracion', 0);
        $this->json(['success' => true, 'message' => 'Configuración guardada correctamente']);
    }

    /**
     * Prueba la conexión SMTP (AJAX)
     */
    public function testSmtp(): void
    {
        if (!$this->verifyCSRF()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
            return;
        }

        $email = trim($this->getInput('email'));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['error' => 'Correo electrónico inválido'], 400);
            return;
        }

        // Reiniciar mailer para usar config actualizada
        \App\Helpers\EmailHelper::resetMailer();

        $sent = \App\Helpers\EmailHelper::sendNotification(
            $email,
            'Prueba de configuración SMTP - SBN MCP',
            '<h2 style="color:#0f172a">Configuración SMTP Exitosa</h2>
             <p>El correo de prueba fue enviado correctamente desde el Sistema de Gestión de Bienes Nacionales.</p>
             <p style="color:#64748b;font-size:12px">Fecha: ' . date('d/m/Y H:i:s') . '</p>',
            "Prueba de configuración SMTP exitosa.\nFecha: " . date('d/m/Y H:i:s')
        );

        if ($sent) {
            $this->json(['success' => true, 'message' => 'Correo de prueba enviado a ' . $email]);
        } else {
            $this->json(['error' => 'Error al enviar. Verifique los datos de configuración SMTP.'], 500);
        }
    }
}
