<?php

namespace frontend\controllers;

use common\controllers\MessengerControllerBase;


class MessengerController extends MessengerControllerBase
{
	public function actions() 
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
				'view' => 'frontend/views/messenger/error.php'
			],
		];
	}
}
	