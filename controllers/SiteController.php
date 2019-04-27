<?php

namespace app\controllers;

use App;
use app\models\Post;

// контролер
class SiteController extends \app\core\Controller {
		
	// стартовая страница
	function actionIndex() {
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

		echo $view;
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
	
}