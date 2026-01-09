FROM node:20-alpine AS nodebuilder
WORKDIR /app

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build


#PHP + Apache
FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip gd intl \
    && a2enmod rewrite

# Fix DocumentRoot Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

# Allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

COPY --from=nodebuilder /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache public \
 && chmod -R 775 storage bootstrap/cache public

EXPOSE 80
CMD ["apache2-foreground"]
