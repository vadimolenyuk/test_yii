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

if [ "$YII_ENV" = "test" ]; then
  php -r "
    \$pdo = new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'),
      'root',
      getenv('MYSQL_ROOT_PASSWORD')
    );
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS ' . getenv('DB_NAME') . ' CHARACTER SET ' . getenv('DB_CHARSET'));
    \$pdo->exec('GRANT ALL PRIVILEGES ON ' . getenv('DB_NAME') . '.* TO \"' . getenv('DB_USER') . '\"@\"%\" IDENTIFIED BY \"' . getenv('DB_PASSWORD') . '\"');
    \$pdo->exec('FLUSH PRIVILEGES');
  "
    composer install --no-interaction --prefer-dist
    composer require --dev codeception/module-rest
    composer require --dev codeception/module-asserts  
    composer require --dev codeception/module-yii2
    composer require --dev codeception/module-phpbrowser  

    php yii migrate/fresh --interactive=0

      echo "Running migrations..."
  php yii migrate/fresh --interactive=0
  
  echo "Running tests with suppressed deprecation warnings..."
  php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED" \
    vendor/bin/codecept run unit,functional -c backend/codeception.yml --html
  
  echo "Tests completed!"
fi

if [ "$YII_ENV" = "dev" ]; then

  if [ ! -d /app/vendor ]; then
    composer install \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-progress \
    --no-cache
  fi
  if [ ! -f /app/backend/web/index.php ]; then
    php init --env=Development --overwrite=All
  fi
  echo "Running Yii migrations..."
  php /app/yii migrate --interactive=0 || echo "Migrations failed or nothing to apply"

  if [ -f /app/yii ]; then
    echo "Running seeder..."
    php /app/yii seed --interactive=0 || echo "Seeder skipped or failed"
  fi
  echo "Starting Apache..."
  exec apache2-foreground
fi
