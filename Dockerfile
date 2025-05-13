FROM php:8.2-apache

RUN apt-get update && apt-get upgrade -y && apt-get clean

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

EXPOSE 80
