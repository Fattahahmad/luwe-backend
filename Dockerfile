# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi Laravel yang diperlukan
RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git curl libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin project Laravel ke folder Apache
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install dependencies Laravel
RUN composer install --no-dev --optimize-autoloader

# Copy Apache config agar point ke /public
RUN echo "<Directory /var/www/html/public>\n\tAllowOverride All\n</Directory>" >> /etc/apache2/apache2.conf

# Aktifkan mod_rewrite untuk Laravel
RUN a2enmod rewrite

# Set permission (optional)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
