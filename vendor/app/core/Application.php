<?php

namespace app\core;

use App;

// класс роутера

class Application extends Component{

    private $_basePath;
    private $_layoutPath;
    private $_viewPath;
	private $path;
	public $defaultController = 'site';
    public $name = 'My Application';
    public $controllerNamespace = 'app\\controllers';

	// получаем экземпляр
	function __construct($config) {
        App::$app = $this;
		if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new \Exception('Не задана переменная "basePath" в конфигурации приложения.');
		}
		$this->setComponents($this->coreComponents());
        $this->setPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'controllers');
        if (isset($config['name'])) {
            $this->name = $config['name'];
            unset($config['name']);
        }

        if(isset($config['bd'])){
            $dbClass = $config['bd']['class'];
            App::$db = new $dbClass($config['bd']['dsn'], $config['bd']['username'], $config['bd']['password']);
        }
	}

	public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
	}
	
	public function setBasePath($path){

		$p = strncmp($path, 'phar://', 7) === 0 ? $path : realpath($path);
        if ($p !== false && is_dir($p)) {
            $this->_basePath = $p;
        } else {
            throw new \Exception("Директория не существует: $path");
        }
    }

	// задаем путь до папки с контроллерами
	public function setPath($path) {
        //$path = trim($path, '/\\');
        $path .= DIRECTORY_SEPARATOR;
		// если путь не существует
        if (is_dir($path) == false) {
			throw new \Exception ('Директория "controllers" не существует: `' . $path . '`');
        }
        $this->path = $path;
    }	
    

    public function setLayoutPath($path){
        $this->_layoutPath = $path;
    }

    public function setViewPath($path){
        $this->_viewPath = $path;
    }
	
	// определение контроллера и экшена из урла
	private function getController(&$file, &$controller, &$action, &$components) {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
		unset($_GET['route']);
        if (empty($route)) {
			$route = 'site/index'; 
		}
		
        // Получаем части урла
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        // Находим контроллер
		$controller = ucfirst($parts[0]);
		$action = (isset($parts[1]) ? ucfirst($parts[1]) : 'Index');

		$file = $this->path . $controller . 'Controller.php';
		
		$components = '';
	}
	
	public function run() {
        // Анализируем путь
        $this->getController($file, $controllerName, $id, $components);
        // Проверка существования файла, иначе 404
        if (is_readable($file) == false) {
			die ('404 Not Found');
        }
		
        // Подключаем файл
        include ($file);

        // Создаём экземпляр контроллера
		$class = $this->controllerNamespace . '\\' . $controllerName . 'Controller';
        $module = strtolower($controllerName);
        $controller = App::createObject($class, [$module, $this]);
        $action = 'action' . $id;
        
        //$controller = new $class($module);
        if(get_class($controller) === $class){
            if (method_exists($controller, $action)) {
                $method = new \ReflectionMethod($controller, $action);
                if ($method->isPublic() && $method->getName() === $action) {
                    $controller->$action();
                }
            }
        }else{
            die ('404 Not Found');
        }
    
    }
    
    public function getLayoutPath(){
        if ($this->_layoutPath === null) {
            $this->_layoutPath = $this->getViewPath() . DIRECTORY_SEPARATOR . 'layouts';
        }

        return $this->_layoutPath;
    }

    public function getViewPath(){
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'views';
        }
        return $this->_viewPath;
    }

    public function getView(){
        return $this->get('view');
    }

    public function coreComponents(){
        return [
            'view' => ['class' => 'app\core\View'],
        ];
    }
}
