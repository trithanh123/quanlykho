FROM php:8.4-apache

# 1. Cài đặt các thư viện hệ thống và Node.js (để chạy Tailwind/Vite)
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip libpq-dev \
    curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# 2. Bật mod_rewrite cho Apache
RUN a2enmod rewrite
WORKDIR /var/www/html

# 3. Copy toàn bộ code vào container
COPY . .

# 4. Cài đặt các thư viện PHP (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 5. CÀI ĐẶT VÀ BUILD CSS/JS (TAILWIND, VITE)
RUN npm install
RUN npm run build

# 6. Cấu hình thư mục Public cho Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 7. Cấp quyền cho các thư mục cần thiết
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# 8. Tạo thư mục chứa ảnh (nếu chưa có)
RUN mkdir -p /var/www/html/public/uploads/products
RUN chown -R www-data:www-data /var/www/html/public/uploads

# 9. TỰ ĐỘNG CHẠY MIGRATION KHI KHỞI ĐỘNG (PHẦN MỚI THÊM)
# Script này sẽ chạy lệnh migrate trước, sau đó mới bật server Apache
RUN echo '#!/bin/sh\nphp artisan migrate --force\napache2-foreground' > /usr/local/bin/start-app.sh \
    && chmod +x /usr/local/bin/start-app.sh

EXPOSE 80

# Sử dụng script khởi động thay vì lệnh mặc định
CMD ["/usr/local/bin/start-app.sh"]