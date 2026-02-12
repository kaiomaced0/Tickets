# Multi-stage build para otimização
FROM php:8.2-fpm-alpine AS base

# Configura timezone e locale do container
ENV TZ=America/Sao_Paulo
RUN apk add --no-cache tzdata \
    && cp /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && apk del tzdata

# Instala dependências do sistema (runtime)
RUN apk add --no-cache \
    git \
    curl \
    libpng \
    libzip \
    zip \
    unzip \
    mariadb-client \
    supervisor \
    nginx \
    icu-libs \
    icu-data-full \
    nodejs \
    npm

# Instala extensões PHP necessárias
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libpng-dev \
    libzip-dev \
    icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql zip gd opcache intl \
    && apk del .build-deps

# Instala Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia entrypoint de desenvolvimento e torna executável
COPY docker/entrypoint-dev.sh /entrypoint-dev.sh
RUN chmod +x /entrypoint-dev.sh

# Configura diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos de dependências primeiro (cache layer)
COPY composer.json composer.lock ./

# Instala dependências do Composer (sem dev)
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Stage para build dos assets
FROM node:18-alpine AS assets

WORKDIR /app

# Copia package.json e instala dependências
COPY package.json package-lock.json ./
RUN npm ci

# Copia código fonte e builda assets
COPY . .
RUN npm run build

# Stage final
FROM base AS production

# Copia código da aplicação
COPY . /var/www/html

# Copia assets buildados do stage anterior
COPY --from=assets /app/public/build /var/www/html/public/build

# Finaliza instalação do Composer
RUN composer dump-autoload --optimize

# Configura permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copia configurações
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copia script de entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expõe porta 80
EXPOSE 80

# Define entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Comando padrão
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
