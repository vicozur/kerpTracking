FROM php:8.2-apache

# 1. Instalar dependencias del sistema y librerías para PHP
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install iconv \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pgsql pdo_pqsql pdo_pgsql \
    && docker-php-ext-install zip

# 2. Habilitar mod_rewrite para CodeIgniter 4
RUN a2enmod rewrite

# 3. Instalar Composer (necesario para las librerías de Google)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configurar el DocumentRoot de Apache a la carpeta /public de CI4
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Establecer directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar el código (el instalador Python clonará el repo aquí)
# Por ahora dejamos el contenedor listo para recibir el código.