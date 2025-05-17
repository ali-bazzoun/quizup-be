#!/bin/bash

set -e

echo "Waiting for MySQL to be available..."

until (echo > /dev/tcp/mysql-db/3306) >/dev/null 2>&1; do
  echo "MySQL is unavailable - sleeping"
  sleep 30
done

echo "MySQL is up - continuing with startup"

echo "MySQL is up - executing setup and seed"
php /var/www/app/database/setup.php
php /var/www/app/database/seed.php

echo "Starting Apache..."
exec apache2-foreground
