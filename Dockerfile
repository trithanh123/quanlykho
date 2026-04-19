FROM php:8.4-apache

# 1. Cài đặt các thư viện hệ thống, Node.js và các Extension PHP quan trọng
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip unzip libpq-dev \
    libicu-dev curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql bcmath intl gd opcache

# 2. Bật mod_rewrite cho Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

# 3. Copy toàn bộ code vào container
COPY . .

# 4. Cài đặt các thư viện PHP (Composer)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 5. Cài đặt và build CSS/JS (Tailwind, Vite)
RUN npm install
RUN npm run build

# 6. Cấu hình thư mục Public cho Apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 7. Tạo các thư mục cache hệ thống và cấp quyền
RUN mkdir -p /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/cache \
             /var/www/html/storage/logs \
             /var/www/html/public/uploads/products \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public

# 8. Script khởi động — tự động dùng $PORT do Render cấp
RUN printf '#!/bin/sh\n\
# Render cấp port động qua biến $PORT, Apache cần lắng nghe đúng port đó\n\
RENDER_PORT="${PORT:-80}"\n\
sed -i "s/Listen 80/Listen ${RENDER_PORT}/" /etc/apache2/ports.conf\n\
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${RENDER_PORT}>/" /etc/apache2/sites-available/*.conf\n\
\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan migrate --force\n\
\n\
apache2-foreground\n' > /usr/local/bin/start-app.sh \
    && chmod +x /usr/local/bin/start-app.sh

EXPOSE 10000

CMD ["/usr/local/bin/start-app.sh"]