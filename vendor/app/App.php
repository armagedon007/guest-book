<?php

require(__DIR__ . '/BaseApp.php');

class App extends \app\BaseApp{
}

spl_autoload_register(['App', 'autoload'], true, true);
App::$classMap = require(__DIR__ . '/classes.php');
App::$container = new app\Container();