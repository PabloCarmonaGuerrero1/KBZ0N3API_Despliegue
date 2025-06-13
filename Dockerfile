FROM php:8.2-cli

# Instalar dependencias de sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar archivos al contenedor
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Generar clave de app
RUN php artisan key:generate

# Exponer el puerto
EXPOSE 10000

# Comando para arrancar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
