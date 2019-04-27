<?php

namespace app;

use App;

class Instance{

    public $id;

    protected function __construct($id){
        $this->id = $id;
    }

    public static function of($id){
        return new static($id);
    }

   
    

    public function get($container = null){
        if ($container) {
            return $container->get($this->id);
        }
        if (Yii::$app && Yii::$app->has($this->id)) {
            return Yii::$app->get($this->id);
        } else {
            return Yii::$container->get($this->id);
        }
    }

  
}
