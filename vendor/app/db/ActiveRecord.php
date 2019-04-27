<?php

namespace app\db;
use App;

class ActiveRecord extends \app\core\Model {

    public $db;
    public $orderBy = "";
    public $offset = 0;
    public $condition = "";
    public $limit = 25;
    public $lastError = '';

    public function __construct(\app\db\Connection $db = null){
        $this->db = $db ? $db : App::$db;
    }

    public function tableName(){
        return '';
    } 

    public function count(){
        $sql = "SELECT COUNT(*) count FROM `" . $this->tableName() . "`";
        if($this->condition){
            $sql .= "WHERE {$this->condition}";
        }
        return $this->db->query($sql)->row['count'];
    }

    public function all($fields = "*"){
        $sql = "SELECT {$fields} FROM `" . $this->tableName() . "`";
        if($this->condition){
            $sql .= "WHERE {$this->condition}";
        }
        if($this->orderBy){
            $sql .= " ORDER BY {$this->orderBy}";
        }
        $sql .= " LIMIT {$this->offset}, {$this->limit}";

        return $this->db->query($sql)->rows;
    }

    public function delete($condition){
        if($condition){
            $sql = "DELETE FROM `" . $this->tableName() . "` WHERE $condition";
            $this->db->query($sql)->row;
            return true;
        }
    }

    public function insert($fields = []){
        $sql = "INSERT `" . $this->tableName() . "` SET ";

        if($fields){
            $upd = [];
            foreach($fields as $field){
                if(isset($this->$field)){
                    $upd[] = "`{$field}`='" . $this->db->escape($this->$field) . "'";
                }
            }
            if($upd){
                $sql .= implode(', ',$upd);

                $this->db->query($sql)->row;
                return true;
            }
        }
        
        return false;
    }

}