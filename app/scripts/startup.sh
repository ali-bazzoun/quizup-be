#!/bin/bash

echo "Waiting for MySQL to be ready..."

until mysqladmin ping -h db -uquizuser -pquizpass --silent; do
  >&2 echo "MySQL is unavailable - sleeping"
  sleep 2
done

echo "MySQL is up - executing setup and seed"
php /var/www/app/scripts/setup.php
php /var/www/app/scripts/seed.php

echo "Starting Apache..."
apache2-foreground
