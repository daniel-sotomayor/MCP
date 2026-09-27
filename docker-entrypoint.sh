#!/bin/sh
set -eu

APP_DIR=/var/www/html/apps/SBN_MCP

for storage_dir in uploads reports logs backups cache; do
    data_dir="/data/$storage_dir"
    app_path="$APP_DIR/$storage_dir"
    mkdir -p "$data_dir"

    if [ -d "$app_path" ] && [ ! -L "$app_path" ]; then
        cp -a "$app_path/." "$data_dir/"
        rm -rf "$app_path"
    fi

    ln -sfn "$data_dir" "$app_path"
    chown -R www-data:www-data "$data_dir"
done

mkdir -p /data/sessions
chown -R www-data:www-data /data/sessions

exec "$@"#!/bin/bash
set -e

echo "🚀 Inicializando contenedor MCP..."

# Configurar SBN_MCP
if [ ! -f /var/www/html/apps/SBN_MCP/.env ]; then
    echo "Configurando SBN_MCP..."
    cp /var/www/html/apps/SBN_MCP/.env.example /var/www/html/apps/SBN_MCP/.env
    
    if [ -n "$DB_HOST_SBN" ]; then
        sed -i "s/DB_HOST=localhost/DB_HOST=$DB_HOST_SBN/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$DB_PORT_SBN" ]; then
        sed -i "s/DB_PORT=3306/DB_PORT=$DB_PORT_SBN/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$DB_NAME_SBN" ]; then
        sed -i "s/DB_NAME=hospital_bienes/DB_NAME=$DB_NAME_SBN/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$DB_USER_SBN" ]; then
        sed -i "s/DB_USER=root/DB_USER=$DB_USER_SBN/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$DB_PASS_SBN" ]; then
        sed -i "s/DB_PASS=admin/DB_PASS=$DB_PASS_SBN/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$APP_ENV" ]; then
        sed -i "s/APP_ENV=development/APP_ENV=$APP_ENV/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$APP_DEBUG" ]; then
        sed -i "s/APP_DEBUG=true/APP_DEBUG=$APP_DEBUG/" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$APP_TIMEZONE" ]; then
        sed -i "s|APP_TIMEZONE=America/Caracas|APP_TIMEZONE=$APP_TIMEZONE|" /var/www/html/apps/SBN_MCP/.env
    fi
    if [ -n "$APP_KEY" ]; then
        sed -i "s/APP_KEY=.*/APP_KEY=$APP_KEY/" /var/www/html/apps/SBN_MCP/.env
    fi
fi

# Configurar SRCC_MCP
if [ ! -f /var/www/html/apps/SRCC_MCP/.env ]; then
    echo "Configurando SRCC_MCP..."
    cp /var/www/html/apps/SRCC_MCP/.env.example /var/www/html/apps/SRCC_MCP/.env
    
    if [ -n "$DB_HOST_SRCC" ]; then
        sed -i "s/DB_HOST=localhost/DB_HOST=$DB_HOST_SRCC/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$DB_PORT_SRCC" ]; then
        sed -i "s/DB_PORT=3306/DB_PORT=$DB_PORT_SRCC/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$DB_NAME_SRCC" ]; then
        sed -i "s/DB_NAME=hospital_citas/DB_NAME=$DB_NAME_SRCC/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$DB_USER_SRCC" ]; then
        sed -i "s/DB_USER=root/DB_USER=$DB_USER_SRCC/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$DB_PASS_SRCC" ]; then
        sed -i "s/DB_PASS=/DB_PASS=$DB_PASS_SRCC/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$APP_ENV" ]; then
        sed -i "s/APP_ENV=development/APP_ENV=$APP_ENV/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$APP_DEBUG" ]; then
        sed -i "s/APP_DEBUG=true/APP_DEBUG=$APP_DEBUG/" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$APP_TIMEZONE" ]; then
        sed -i "s|APP_TIMEZONE=America/Caracas|APP_TIMEZONE=$APP_TIMEZONE|" /var/www/html/apps/SRCC_MCP/.env
    fi
    if [ -n "$APP_KEY" ]; then
        sed -i "s/APP_KEY=.*/APP_KEY=$APP_KEY/" /var/www/html/apps/SRCC_MCP/.env
    fi
fi

# Crear directorios necesarios
mkdir -p /var/www/html/apps/SBN_MCP/uploads /var/www/html/apps/SBN_MCP/logs \
    /var/www/html/apps/SBN_MCP/backups /var/www/html/apps/SBN_MCP/reports \
    /var/www/html/apps/SBN_MCP/cache
mkdir -p /var/www/html/apps/SRCC_MCP/uploads /var/www/html/apps/SRCC_MCP/logs \
    /var/www/html/apps/SRCC_MCP/backups /var/www/html/apps/SRCC_MCP/cache

echo "✅ Inicialización completada"
echo "Servidor listo en puerto 8080"

exec apache2-foreground
