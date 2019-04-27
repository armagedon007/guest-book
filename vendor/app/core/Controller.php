<?php

namespace app\core;

use App;

// абстрактый класс контроллера
class Controller extends Component implements ViewContextInterface{

    private $_view;
    private $_viewPath;
    public $id;
    public $module;
	public $layout = 'default'; // шаблон

	// в конструкторе подключаем шаблоны
	function __construct($module) {
        $this->module = $module;
        //$this->id = $id;
	}

	public function render($view, $params = []){
        $content = $this->getView()->render($view, $params, $this);
        return $this->renderContent($content);
	}	
	
	public function renderContent($content){
        $layoutFile = $this->findLayoutFile();
        if ($layoutFile !== false) {
            return $this->getView()->renderFile($layoutFile, ['content' => $content], $this);
        }
        return $content;
    }
    
    public function renderPartial($view, $params = []){
        return $this->getView()->render($view, $params, $this);
    }

	public function setView($view){
        $this->_view = $view;
    }

	public function getView(){
        if ($this->_view === null) {
            $this->_view = App::$app->getView();
        }
        return $this->_view;
    }

    public function getViewPath(){
        if ($this->_viewPath === null) {
            $this->_viewPath = App::$app->getViewPath() . DIRECTORY_SEPARATOR . $this->module;
        }
        return $this->_viewPath;
    }
	
	public function findLayoutFile(){

        if (is_string($this->layout)) {
            $layout = $this->layout;
        } else {
            return false;
        }

        $file = App::$app->getLayoutPath() . DIRECTORY_SEPARATOR . $layout;

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
		$path = $file . '.php';
        return $path;
    }
}
