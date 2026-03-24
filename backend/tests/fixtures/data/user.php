<?php

return [
    'user1' => [
        'id' => 1,
        'firstname' => 'user_test_1',
        'lastname' => 'user_test_1',
        'password_hash' => Yii::$app->security->generatePasswordHash("test"),
        'auth_key' => Yii::$app->security->generateRandomString(32),
        'created_at' => time(),
        'updated_at' => time(),
    ],
    'user2' => [
        'id' => 2,
        'firstname' => 'user_test_2',
        'lastname' => 'user_test_2',
        'password_hash' => Yii::$app->security->generatePasswordHash("test"),
        'auth_key' => Yii::$app->security->generateRandomString(32),
        'created_at' => time(),
        'updated_at' => time(),
    ],
];