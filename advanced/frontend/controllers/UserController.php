<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\controllers\UserControllerBase;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends UserControllerBase
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['user'],
                ],
            ],*/
        ];
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'modelThisView' => $this->findModel($id),
            'myModel' => $this->findModel(Yii::$app->user->id),
        ]);
    }
}
