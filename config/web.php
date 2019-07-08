<?php

return [
    'name' => 'Гостевая книга',
    'basePath' => dirname(__DIR__),
    'bd' => [
        'class' => 'app\db\Connection',
        'dsn' => 'mysql:host=localhost;port=3306;dbname=guestbook',
        'username' => 'root',
        'password' => '123456',
    ]
];
