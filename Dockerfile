FROM php:8.4-apache [cite: 1]

# 1. Cài đặt các thư viện hệ thống, Node.js và các Extension PHP quan trọng
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip libpq-dev \
    libicu-dev curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - [cite: 2] \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql bcmath intl gd opcache

# 2. Bật mod_rewrite cho Apache
RUN a2enmod rewrite
WORKDIR /var/www/html

# 3. Copy toàn bộ code vào container [cite: 3]
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

# 7. Tạo các thư mục cache hệ thống và cấp quyền
RUN mkdir -p /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/cache \
             /var/www/html/storage/logs \
             /var/www/html/public/uploads/products
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public 

# 8. SCRIPT KHỞI ĐỘNG TỐI ƯU (Tăng tốc & Chống lỗi)
RUN echo '#!/bin/sh\n\
# Tăng tốc ứng dụng bằng cách cache cấu hình\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
# Chạy migration cập nhật Database\n\
php artisan migrate --force\n\
# Bật server Apache\n\
apache2-foreground' > /usr/local/bin/start-app.sh \
    && chmod +x /usr/local/bin/start-app.sh

EXPOSE 80

# Sử dụng script khởi động thay vì lệnh mặc định 
CMD ["/usr/local/bin/start-app.sh"]