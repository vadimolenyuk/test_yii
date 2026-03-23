#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
counter=0

until mysqladmin ping -h "$DB_HOST" -u "$DB_USER" -p"$MYSQL_ROOT_PASSWORD" --silent; do
  sleep 1
  counter=$((counter+1))
  if [ $counter -gt 60 ]; then
    echo "MySQL timeout after 60s"
    exit 1
  fi
  echo "Still waiting... ($counter/60)"
done
echo "MySQL is up!"

if [ ! -d /app/vendor ]; then
  composer install --prefer-dist --no-interaction --optimize-autoloader
fi

echo "Running Yii migrations..."
php /app/yii migrate --interactive=0 || echo "Migrations failed or nothing to apply"

if [ -f /app/yii ]; then
  echo "Running seeder..."
  php /app/yii seed --interactive=0 || echo "Seeder skipped or failed"
fi

echo "Starting Apache..."
exec apache2-foreground