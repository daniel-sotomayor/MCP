FROM composer:2 AS composer

FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
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
CMD ["apache2-foreground"]
