<?php

namespace app\core;

use App;

// абстрактый класс контроллера
abstract class Component{
	protected $application;
	protected $_components; 
	protected $_definitions; 

	public function get($id, $throwException = true){
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_components[$id] = $definition;
            } else {
                return $this->_components[$id] = App::createObject($definition);
            }
        } elseif ($throwException) {
            throw new \Exception("Неизвестный компонент ID: $id");
        } else {
            return null;
        }
	}
	
	public function set($id, $definition){
        unset($this->_components[$id]);

        if ($definition === null) {
            unset($this->_definitions[$id]);
            return;
        }

        if (is_object($definition) || is_callable($definition, true)) {
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new \Exception("Конфигурация компонента \"$id\" должна содержать элемент \"class\".");
            }
        } else {
            throw new \Exception("Неизвестная конфигурация для \"$id\": " . gettype($definition));
        }
	}
	
	public function setComponents($components){
        foreach ($components as $id => $component) {
            $this->set($id, $component);
        }
    }
}
