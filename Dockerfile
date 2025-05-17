FROM php:8.2-apache

RUN apt-get update && apt-get upgrade -y && apt-get clean
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

COPY public/ /var/www/html

COPY app/ /var/www/app/

WORKDIR /var/www/html

RUN chmod +x /var/www/app/scripts/startup.sh

EXPOSE 80

# CMD ["/var/www/app/scripts/startup.sh"]
