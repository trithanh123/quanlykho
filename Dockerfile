# Sử dụng bản PHP 8.2 có sẵn Apache (phù hợp với Laravel)
FROM php:8.2-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd

# Bật chế độ Rewrite của Apache để chạy được các route Laravel
RUN a2enmod rewrite

# Chuyển vào thư mục làm việc trong server
WORKDIR /var/www/html

# Copy toàn bộ code từ máy ông vào server
COPY . .

# Cấu hình Apache trỏ thẳng vào thư mục public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cài đặt Composer và các thư viện PHP
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho thư mục storage để Laravel không bị lỗi Permission
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Mở cổng 80 để web chạy
EXPOSE 80