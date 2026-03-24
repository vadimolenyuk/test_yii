#!/bin/bash
set -e
cleanup_local_configs() {
  local CONFIG_DIRS=(backend/config common/config console/config /environments/dev/common/config /environments/prod/common/config)
  local FILES_TO_DELETE=(codeception-local.php test-local.php main-local.php params-local.php)

  for dir in "${CONFIG_DIRS[@]}"; do
    for file in "${FILES_TO_DELETE[@]}"; do
      if [ -f "$dir/$file" ]; then
        echo "Deleting $dir/$file"
        rm -f "$dir/$file"
      fi
      if [[ "$file" == "main-local.php" || "$file" == "params-local.php" ]]; then
        echo "Creating empty $dir/$file"
        mkdir -p "$dir"
        echo "<?php return [];" > "$dir/$file"
      fi
    done
  done
  CONFIG_FILE="/app/backend/config/codeception-local.php"

if [ ! -f "$CONFIG_FILE" ]; then
  echo "Creating $CONFIG_FILE..."
  cat << 'EOF' > "$CONFIG_FILE"
<?php
return [
    'id' => 'app-backend-test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => sprintf(
                'mysql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_PORT') ?: '3306',
                getenv('DB_NAME') ?: 'yii2_test'
            ),
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => getenv('DB_CHARSET') ?: 'utf8',
        ],
    ],
];
EOF
  echo "$CONFIG_FILE created successfully."
fi
}
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
  cleanup_local_configs
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
 
composer clear-cache
composer install --prefer-dist --no-interaction --optimize-autoloader

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

composer clear-cache

composer install --prefer-dist --no-interaction --optimize-autoloader
    composer install \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-progress \
    --no-cache
  fi
  if [ ! -f /app/backend/web/index.php ]; then
    php init --env=Development --overwrite=All
    cleanup_local_configs
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
