# Stage 1: Composer dependencies
FROM composer:2 as composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Stage 2: Final image
FROM dunglas/frankenphp:1-builder-php8.4.7

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && install-php-extensions pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências PHP
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Configurar permissões
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Expor porta
EXPOSE 80

# Copiar script de inicialização
COPY docker/init-app.sh /usr/local/bin/init-app.sh
RUN chmod +x /usr/local/bin/init-app.sh

# Comando para iniciar o script de inicialização
CMD ["/usr/local/bin/init-app.sh"]
