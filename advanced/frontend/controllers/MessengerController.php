<?php

namespace frontend\controllers;

use common\models\db\Message;
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
            'query' => Message::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


	/**
	 * Загрузка изображений к сообщению
	 */
	public function actionUpload() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;

		$upload = new UploadForm();


		if (Yii::$app->request->isPost) {
			$upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
			$upload->upload("message");

			Yii::warning($upload->imageFile);
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

		$messageInfo = Yii::$app->request->post();

		$message = new Message();
		$message->content   = $messageInfo['content'];
		$message->userName = $messageInfo['userName'];
		$message->save();

		return [
			'id'        => $message->id,
			'createdAt' => Yii::$app->formatter->asTime("now", 'php:d M H:i'),
			'owner'     => true
		];
	}

	/**
	 * @return bool
	 */
	public function actionDeleteMessage() 
	{
		$messageInfo  = Yii::$app->request->post();
		$user = Message::findOne($messageInfo['idMessage']);

		if (strcasecmp($user->userName, $this->getCurrentUserName()) == 0) {
			$user->delete();
			return true;
		} else {
			return false;
		}
	}

	protected function getCurrentUserName()
	{
		return isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : null;
	}

}


