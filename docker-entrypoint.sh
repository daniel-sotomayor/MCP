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

exec "$@"
