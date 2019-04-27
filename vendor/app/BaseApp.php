<?php

namespace app;

use App;
session_start();
defined('APP_PATH') or define('APP_PATH', __DIR__);

class BaseApp{

    public static $classMap = [];
    public static $app;
    public static $container;
    public static $db;

    public static function autoload($className){
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
        }elseif (strpos($className, '\\') !== false) {
            if(stripos($className, 'app\\') === 0){
                $className = App::$app->getBasePath() . substr($className, 3);
            } 
            $classFile = str_replace('\\', '/', $className) . '.php';
            if ($classFile === false || !is_file($classFile)) {
                return;
            }
        } else {
            return;
        } 

        // подключаем файл с классом
        include($classFile);
    }

    public static function createObject($type, array $params = []){
        if (is_string($type)) {
            return static::$container->get($type, $params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class, $params, $type);
        } elseif (is_callable($type, true)) {
            return static::$container->invoke($type, $params);
        } elseif (is_array($type)) {
            throw new \Exception('Конфигурация объекта должна быть массивом с элементом "class".');
        }

        throw new \Exception('Неподдерживаемый тип: ' . gettype($type));
    }
}
