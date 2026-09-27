FROM composer:2 AS composer

FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd intl mbstring mysqli opcache pdo_mysql zip \
    && a2enmod deflate expires headers rewrite \
    && sed -ri '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY deploy/php-production.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker-entrypoint.sh /usr/local/bin/mcp-entrypoint

WORKDIR /var/www/html
COPY . /var/www/html

RUN sed -i 's|ErrorDocument 403 /MCP/403.html|ErrorDocument 403 /403.html|' /var/www/html/.htaccess \
    && cd /var/www/html/apps/SBN_MCP \
    && composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader \
    && chmod +x /usr/local/bin/mcp-entrypoint

EXPOSE 80
ENTRYPOINT ["mcp-entrypoint"]
CMD ["apache2-foreground"]# Dockerfile para Fly.io - Proyecto MCP
# Maternidad Concepción Palacios
# Contiene SBN_MCP + SRCC_MCP con MySQL separado por cada sistema

FROM php:8.2-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libcurl4-openssl-dev \
    libmariadb-dev \
    default-mysql-client \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        gd \
        mbstring \
        curl \
        zip \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache
RUN a2enmod rewrite && a2enmod headers && a2enmod deflate && a2enmod expires

# Configurar DocumentRoot para SBN_MCP (sistema principal)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/apps/SBN_MCP/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Crear estructura de directorios
RUN mkdir -p /var/www/html/apps/SBN_MCP/public \
    && mkdir -p /var/www/html/apps/SRCC_MCP/public

# Copiar archivos del proyecto
COPY . /var/www/html/

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/apps/SBN_MCP/uploads \
    && chmod -R 777 /var/www/html/apps/SBN_MCP/logs \
    && chmod -R 777 /var/www/html/apps/SBN_MCP/backups \
    && chmod -R 777 /var/www/html/apps/SBN_MCP/reports \
    && chmod -R 777 /var/www/html/apps/SBN_MCP/cache \
    && chmod -R 777 /var/www/html/apps/SRCC_MCP/uploads \
    && chmod -R 777 /var/www/html/apps/SRCC_MCP/logs \
    && chmod -R 777 /var/www/html/apps/SRCC_MCP/backups \
    && chmod -R 777 /var/www/html/apps/SRCC_MCP/cache

# Instalar dependencias de SBN_MCP
RUN cd /var/www/html/apps/SBN_MCP \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Instalar dependencias de SRCC_MCP
RUN cd /var/www/html/apps/SRCC_MCP \
    && composer install --no-dev --optimize-autoloader --no-interaction

# Script de inicialización
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
