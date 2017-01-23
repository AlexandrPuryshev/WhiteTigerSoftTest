<?php

namespace common\controllers;

use common\models\base\MessageBase;
use common\models\base\UploadFormBase;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Class MessengerController
 * @package app\controllers
 */
class MessengerControllerBase extends Controller {

	private $currentUser = null;

	public function behaviors() 
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['index', 'upload', 'newmessage', 'deletemessage'],
				'rules' => [
					[
						'actions' => ['index', 'upload', 'newmessage', 'deletemessage'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
		];
	}

	public function actions() 
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 *
	 */
	public function init() 
	{
		$this->currentUser = isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : null;
	}

	/**
	 * Страница чата
	 * @return string
	 */
	public function actionIndex() 
	{
		return $this->render('index');
	}

	/**
	 * Загрузка изображений к сообщению
	 */
	public function actionUpload() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$upload = new UploadFormBase();


		if (Yii::$app->request->isPost) 
		{
			$upload->imageFile = UploadedFile::getInstance($model, 'imageFile');

			if($upload->upload())
			{
				 $model->image = $upload->name;
			}

			return $upload->imageFile->baseName;
		} 
		else 
		{
			return false;
		}

	}

	/**
	 * @return mixed
	 */
	public function actionNewMessage() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$msg = Yii::$app->request->post();

		$message = new MessageBase();
		$message->content   = $msg['content'];
		$message->userName = $msg['userName'];
		$message->save();

		return [
			'id'         => $message->id,
			'createdAt' => date("d M H:i", strtotime($message->createdAt)),
			'owner'      => true
		];
	}

	/**
	 * @return bool
	 */
	public function actionDeleteMessage() 
	{
		$msg  = Yii::$app->request->post();
		$user = MessageBase::findOne($msg['id']);

		if ($user->userName === $this->currentUser)
		{
			$user->delete();

			return true;
		}
		else
		{
			return false;
		}
	}

}


