<?php

namespace app\models;

use App;

// модель
class User extends \app\core\Model{

	private static $password = 'password';

	public static function isGuest(){
		return isset($_SESSION['user_login']) ? false : true;
	}

	public static function login($password){
		if(md5($password) == md5(self::$password)){
			$_SESSION['user_login'] = 1;
			return true;
		}
		return false;
	}
	public static function logout(){
		unset($_SESSION['user_login']);
	}

	public function getUser(){
		return array('id'=>1, 'name'=>'test_name');
	}	
	
}