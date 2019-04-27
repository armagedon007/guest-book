<?php

namespace app\models;

use App;

class Post extends \app\db\ActiveRecord{

    public $text;
    public $type = NULL;
    public $photo;
    public $dateAt;

    public $typeItems = [
        1 => 'Вопрос',
        2 => 'Отзыв',
        3 => 'Предложение',
    ];

    public function getItemType($type){
        return isset($this->typeItems[$type]) ? $this->typeItems[$type] : 'Не задано';
    }

    public function tableName(){
        return 'post';
    } 

    public function attributeLabels(){
        return [
            'text' => 'Основной текст',
            'type' => 'Тематика',
            'photo' => 'Фото',
            'dateAt' => 'Добавлен',
        ];
    }
    
    public function search($params = []){
        
        $this->condition = "";
        $this->orderBy = "dateAt DESC";
        $this->limit = 15;
        $page = isset($_GET['page']) && $_GET['page']*1 ? $_GET['page'] : 1;
        $this->offset = ($page - 1)*$this->limit;

        if(isset($params['type']) && $params['type']){
            $this->condition = "type={$params['type']}";
        }
        $count = self::count();
        $found = min($page*$this->limit, $count);
        return [
            'count' => $count,
            'found' => $found,
            'page' => $page,
            'limit' => $this->limit,
            'items' => self::all(),
        ];
    }

    public function save(){
        if(isset($_FILES['photo'])){
            $file = $_FILES['photo'];
            $path = App::$app->getBasePath() . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $path);
            $this->photo = '/images/' . basename($file['name']);
        }
        $this->dateAt = time();
        $fields = ['text', 'photo', 'type', 'dateAt'];
        return $this->insert($fields);
    }
 
} 