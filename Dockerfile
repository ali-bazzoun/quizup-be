FROM php:8.2-apache

# Upgrade packages to reduce vulnerabilities
RUN apt-get update && apt-get upgrade -y && apt-get clean

# Install PHP extensions needed for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Expose port
EXPOSE 80
