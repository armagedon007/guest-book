<?php

namespace app\core;

use App;
use ReflectionClass;

class Model extends Component{
    
    public function attributes(){
        $class = new ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            
            if (!$property->isStatic()) {
                $names[$property->getName()] = $property->getName();
            }
        }
        return $names;
    }

    public function attributeLabels(){
        return [];
    }

    public function getAttributes($names = null, $except = []){
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    public function setAttributes($values){
        if (is_array($values)) {
            $attributes = $this->attributes();
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->$name = $value;
                } 
            }
        }
    }

    public function __get($name){
        if (($pos = strpos($name, 'Label')) === strlen($name) - 5) {
            $labels = $this->attributeLabels();
            $name = substr($name, 0, $pos);
            return isset($labels[$name]) ? $labels[$name] : $name;
        }
        return parent::__get($name);
    }

    public function load($data){
        $this->setAttributes($data);

        return true;
        
    }

    public function fields(){
        $fields = $this->attributes();

        return array_combine($fields, $fields);
    }
}
