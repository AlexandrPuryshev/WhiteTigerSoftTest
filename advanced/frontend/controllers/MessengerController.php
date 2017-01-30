<?php

namespace frontend\controllers;

use common\models\db\MessageModel;
use frontend\models\UploadForm;
use yii\data\ActiveDataProvider;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\Controller;

/**
 * Class MessageController
 * @package app\controllers
 */
class MessengerController extends Controller 
{

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
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => MessageModel::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


	/**
	 *
	 */
	public function init() 
	{
		$this->currentUser = isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : null;
	}

	/**
	 * Загрузка изображений к сообщению
	 */
	public function actionUpload() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$upload = new UploadForm();


		if (Yii::$app->request->isPost) {
			$upload->imageFile = UploadedFile::getInstance($model, 'imageFile');

			if($upload->upload()) {
				 $model->image = $upload->name;
			}

			return $upload->imageFile->baseName;
			
		} else {
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

		$message = new MessageModel();
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
		$user = MessageModel::findOne($msg['id']);

		if ($user->userName === $this->currentUser) {
			$user->delete();

			return true;
		} else {
			return false;
		}
	}

}


