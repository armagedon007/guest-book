<?php
// включим отображение всех ошибок
error_reporting (E_ALL); 

// подключаем конфиг
$config = require(__DIR__ . '/../config/web.php');

// подключаем ядро сайта
require(__DIR__ . '/../vendor/app/App.php');

(new app\core\Application($config))->run();
