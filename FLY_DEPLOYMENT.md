# Despliegue en Fly.io

El despliegue usa una sola aplicacion PHP/Apache para servir el sitio del hospital desde `/` y SBN desde `/apps/SBN_MCP/public/`. El enlace `Personal` ya apunta a la pantalla de acceso de SBN. Los proyectos futuros que se agreguen al monorepo quedaran dentro de la misma imagen; deben tener su propio control de acceso y reglas para impedir que se publiquen archivos internos.

## Ramas y despliegue automatico

- `dev` es la rama de desarrollo: cada push y pull request ejecuta Composer, validacion de sintaxis PHP, PHPCS, PHPStan y build de Docker. No despliega a Fly.io.
- `master` es produccion: ejecuta los mismos checks y solo un push despliega a Fly.io cuando todos terminan correctamente. Un pull request hacia `master` solo valida; debe fusionarse para publicar.
- En GitHub configura la variable de repositorio `FLY_APP_NAME` con el nombre real de la app Fly y el secreto `FLY_API_TOKEN` en el entorno `production`. No guardes tokens en el repositorio.
- El despliegue automatico usa el nombre de `FLY_APP_NAME`; el valor `app` de `fly.toml` debe actualizarse tambien con el nombre real para que los comandos manuales funcionen.

El proyecto aun no contiene `apps/SBN_MCP/tests/run_tests.php`, aunque el script `composer test` lo referencia; el workflow no invoca ese script roto y ejecuta lint, analisis estatico, sintaxis y build hasta que se incorpore una suite de pruebas real.

## Requisitos previos

- Una cuenta de Fly.io y `flyctl` instalado y autenticado con `fly auth login`.
- Docker Desktop activo para validar la imagen localmente.
- Una base de datos MySQL accesible desde Fly.io. Fly.io no proporciona MySQL gestionado nativo; el host y las credenciales deben venir de un proveedor externo.
- Revisar el esquema `apps/SBN_MCP/sql/hospital_bienes.sql` antes de importarlo. No importar datos reales ni credenciales de prueba sin revisarlos.

## Preparar el primer despliegue

1. Cambia `app` en `fly.toml` por un nombre globalmente unico y en minusculas. Usa la misma region para el volumen y la maquina; `mia` es la configuracion inicial.
2. Crea la aplicacion y su volumen persistente:

   ```powershell
   fly apps create nombre-unico-de-la-app
   fly volumes create mcp_data --region mia --size 2
   ```

3. Crea la base MySQL en un proveedor externo e importa el esquema revisado. Configura acceso de red desde Fly.io y conserva el certificado CA del proveedor si exige TLS.
4. Configura los secretos en Fly.io. No guardes estos valores en el repositorio ni en `fly.toml`:

   ```powershell
   fly secrets set APP_KEY=CLAVE_ALEATORIA DB_HOST=HOST_DB DB_PORT=3306 DB_NAME=NOMBRE_DB DB_USER=USUARIO_DB DB_PASS=CLAVE_DB
   ```

   Genera `APP_KEY` localmente con `php -r "echo bin2hex(random_bytes(32));"`. Si el proveedor requiere TLS, coloca su CA en `/data/mysql-ca.pem` y agrega `DB_SSL_CA=/data/mysql-ca.pem` como secreto; la conexion PDO valida el certificado.
5. Despliega desde la raiz del repositorio:

   ```powershell
   fly deploy
   fly status
   fly logs
   ```

El volumen conserva uploads, reportes, logs, respaldos, cache y sesiones. La configuracion limita el servicio a una maquina porque los volumenes Fly son locales a una maquina; no aumentes ese limite sin migrar primero esos datos a almacenamiento compartido.

## Pendientes antes de abrir al publico

- La base MySQL y sus credenciales no se pueden crear desde este repositorio. Configuralas en un proveedor y anade los valores como secretos de Fly.
- Revisa y migra los datos existentes de XAMPP solo despues de validar el esquema y las cuentas iniciales.
- Los formularios de la web usan una direccion `contact@example.com` y la biblioteca `assets/vendor/php-email-form/php-email-form.php` no esta presente. Configura un proveedor de correo y completa ese endpoint antes de considerar funcionales las citas/contacto.
- `apps/SRCC_MCP` solo contiene archivos de proteccion y carpetas vacias; todavia no hay una aplicacion desplegable alli.
- Configura dominio y certificado cuando tengas el dominio del hospital: `fly certs add tu-dominio`.

No se crea ni se despliega una aplicacion Fly durante la preparacion local; eso requiere acceso a la cuenta Fly y a la base MySQL de produccion.