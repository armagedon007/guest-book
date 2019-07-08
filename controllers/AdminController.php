<?php

namespace app\controllers;

use App;
use \app\models\Post;
use \app\models\User;

// контролер
class AdminController extends \app\core\Controller {
	public $layout = 'admin';
	// экшен
	function actionIndex() {
//
		if(User::isGuest()){
			
			$error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '' ;
			if(isset($_SESSION['login_error']))
				unset($_SESSION['login_error']);
			$view = $this->render('login', compact('error'));
		}else{
			$model = new Post();
			$params = [];
			$url = '';
			if(isset($_GET['type'])){
				$url = http_build_query([
					'type' => $_GET['type'],
				]);
				$model->type = $_GET['type'];
			}
			$data = $model->search($_GET);

			$pagination = $this->renderPartial('pagination', compact('data', 'url'));

			$view = $this->render('index', compact('model', 'data', 'pagination'));

		}
		echo $view;
	}

	public function actionLogin(){
		$post = isset($_POST['User']) ? $_POST['User'] : [];
		
		if(isset($post['password'])){
			if(!User::login($post['password'])){
				$_SESSION['login_error'] = 'Не верный пароль';
			}
		}
		header("Location: /?route=admin/index"); 
		exit;
	}

	public function actionLogout(){
		User::logout();
		header("Location: /?route=admin/index"); 
		exit;
	}

	function actionAddpost(){
		$post = isset($_POST['Post']) ? $_POST['Post'] : '';
		
		$model = new Post();
		header("Content-type: application/json; charset=utf-8");
		if($model->load($post) && $model->save()){
			echo json_encode([
				'status' => 'success',
			]);
		}else{
			echo json_encode([
				'status' => 'error',
				'message' => $model->lastError,
			]);
		}
	}
	function actionDeletepost(){
		$id = isset($_POST['id']) && $_POST['id'] ? $_POST['id'] : '';
		
		$model = new Post();

		$model->delete("`id`='{$id}'");

		header("Content-type: application/json; charset=utf-8");
		
		echo json_encode([
			'status' => 'success',
		]);
		
	}
}
