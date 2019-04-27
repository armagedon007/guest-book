<?php

namespace app\core;

use App;

// класс для подключения шаблонов и передачи данных в отображение
class View extends Component{

	private $template;
	private $controller;
	private $layouts;

	private $_viewFiles = [];
	public $context;
	
	public function render($view, $params = [], $context = null){
		$viewFile = $this->findViewFile($view, $context);
        return $this->renderFile($viewFile, $params, $context);
	}
	
	protected function findViewFile($view, $context = null){
		
		if (strncmp($view, '//', 2) === 0) {
            $file = App::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } elseif ($context instanceof ViewContextInterface) {
			$file = $context->getViewPath() . DIRECTORY_SEPARATOR . $view;
			
        } elseif (($currentViewFile = $this->getViewFile()) !== false) {
			$file = dirname($currentViewFile) . DIRECTORY_SEPARATOR . $view;
        } else {
            throw new \Exception("Невозможно ототбразить вид '$view': нет содержимого.");
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.php';
        return $path;
	}
	
	public function getViewFile(){
        return end($this->_viewFiles);
	}
	
	public function renderFile($viewFile, $params = [], $context = null){

        if (!is_file($viewFile)) {
            throw new \Exception("Файл вида не существует: $viewFile");
        }

        $oldContext = $this->context;
        if ($context !== null) {
            $this->context = $context;
        }
        $output = '';
        $this->_viewFiles[] = $viewFile;

		$output = $this->renderPhpFile($viewFile, $params);

		array_pop($this->_viewFiles);
        $this->context = $oldContext;

        return $output;
	}
	
	public function renderPhpFile($_file_, $_params_ = []){
		
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require($_file_);
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
	
}