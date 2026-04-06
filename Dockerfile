FROM php:8.4-apache

# Chỉ cài những thư viện bắt buộc cho Database
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Bật mod_rewrite
RUN a2enmod rewrite
WORKDIR /var/www/html

# Copy code
COPY . .

# Cấu hình thư mục Public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền lưu trữ
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
EXPOSE 80