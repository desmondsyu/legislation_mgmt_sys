FROM php:8.1-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY src/ /var/www/html/
COPY .env /var/www/html/.env
WORKDIR /var/www/html
EXPOSE 80