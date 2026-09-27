#!/bin/bash
# Script para inicializar bases de datos en Fly.io
# Ejecutar después del deploy: fly ssh console -C "bash /var/www/html/scripts/init-db.sh"

set -e

echo "🗄️  Inicializando bases de datos..."

# Importar SBN_MCP
if [ -f /var/www/html/apps/SBN_MCP/sql/hospital_bienes.sql ]; then
    echo "Importando hospital_bienes..."
    mysql -h "$DB_HOST_SBN" -P "$DB_PORT_SBN" -u "$DB_USER_SBN" -p"$DB_PASS_SBN" "$DB_NAME_SBN" < /var/www/html/apps/SBN_MCP/sql/hospital_bienes.sql
    echo "✅ hospital_bienes importada"
fi

# Importar migraciones de SBN_MCP
if [ -d /var/www/html/apps/SBN_MCP/sql/migraciones ]; then
    for f in /var/www/html/apps/SBN_MCP/sql/migraciones/*.sql; do
        echo "Ejecutando migración: $(basename "$f")"
        mysql -h "$DB_HOST_SBN" -P "$DB_PORT_SBN" -u "$DB_USER_SBN" -p"$DB_PASS_SBN" "$DB_NAME_SBN" < "$f"
    done
fi

# Importar SRCC_MCP (cuando tenga SQL)
if [ -f /var/www/html/apps/SRCC_MCP/sql/hospital_citas.sql ]; then
    echo "Importando hospital_citas..."
    mysql -h "$DB_HOST_SRCC" -P "$DB_PORT_SRCC" -u "$DB_USER_SRCC" -p"$DB_PASS_SRCC" "$DB_NAME_SRCC" < /var/www/html/apps/SRCC_MCP/sql/hospital_citas.sql
    echo "✅ hospital_citas importada"
fi

echo "✅ Bases de datos inicializadas"
