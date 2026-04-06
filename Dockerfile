# --- Giai đoạn 1: Build giao diện (Node.js) ---
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY . .
RUN npm install && npm run build

# --- Giai đoạn 2: Chạy Web (PHP) ---
FROM php:8.2-apache

# Cài đặt các thư viện hệ thống (Chỉ lấy cái thực sự cần để nhẹ máy)
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd

# Bật mod_rewrite cho Laravel
RUN a2enmod rewrite

# Chuyển vào thư mục web
WORKDIR /var/www/html

# Copy code từ máy và copy kết quả build từ Giai đoạn 1 sang
COPY . .
COPY --from=node_builder /app/public/build ./public/build

# Cấu hình Apache trỏ vào thư mục public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80