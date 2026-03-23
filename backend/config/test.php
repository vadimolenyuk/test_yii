<?php

return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/main.php',
    [
        'id' => 'app-test',
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
                'username' => getenv('DB_USER'),
                'password' => getenv('DB_PASSWORD'),
                'charset' => getenv('DB_CHARSET'),
            ],
        ],
    ]);
