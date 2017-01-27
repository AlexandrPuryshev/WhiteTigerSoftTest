<?php

namespace common\controllers;

use common\models\base\CategoryBase;
use common\models\base\UserBase;
use common\models\LoginForm;
use common\models\base\CommentBase;
use Yii;
use common\models\base\PostBase;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\base\UploadFormBase;
use yii\web\UploadedFile;
use yii\bootstrap\Alert;
use yii\db\Expression;
use yii\data\Pagination;
/**
 * CRUD операции модели "Посты".
 */
class PostControllerBase extends Controller
{

    public function init() 
    {
         //TODO: set url image aliase
         Yii::setAlias('@imageUrlPath', Yii::$app->request->hostInfo.Yii::getAlias('@web'). '/../runtime/image');
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isUserAuthor()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['PostBase'],
                ],
            ],
        ];
    }

    /**
     * Ренденр поста.
     * @param ActiveDataProvider $dataProvider модель поста
     * @param CategoryBase $CategoryBase модель категории
     * @param string $whatRender какую view рендерим
     **/
    protected function renderIndex($dataProvider, $CategoryBase, $whatRender)
    {
    	$postQuery = $CategoryBase->findCategoryes();
    	$dataProvider = new ActiveDataProvider([
    			'query' => $postQuery,
    	]);

        return $this->render($whatRender, [
            'models' => $dataProvider,
            'categories' => $dataProvider,
        ]);
    }

    protected function newPostDataProvider($postQuery){
        $dataProvider = new ActiveDataProvider([
            'query' => $postQuery,
            'pagination' => [
                'pageSize' => 3,
                'validatePage' => false,
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Список постов.
     * @return string
     */
    public function actionIndex()
    {
        $CategoryBase = new CategoryBase();
        $postQuery = PostBase::findMyPublishedPosts();
        $dataProvider = $this->newPostDataProvider($postQuery);
        return $this->renderIndex($dataProvider, $CategoryBase, 'index');
    }

    /**
     * Просмотр поста.
     * @param string $id идентификатор поста
     * @return string
     */
    public function actionView($id)
    {
        $PostBase = new PostBase();
        return $this->render('view', [
            'model' => $this->findModel($id),
            //'commentForm' => new CommentBase(Url::to(['CommentBase/add', 'id' => $id])),
        ]);
    }

    private function saveModel($model, $view){
        $upload = new UploadFormBase();
        /* push auto author id in field author_id */
        $model->authorId = Yii::$app->user->id;
        $upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
        // if you update, older image you delete, if uploading image is null
        if($view == 'update')
        {
            $model->image = null;
        }
        if($upload->upload())
        {  
            $model->image = $upload->name;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render($view, [
                'model' => $model,
                'image' => $upload,
                'authors' => UserBase::find()->all(),
                'category' => CategoryBase::find()->all()
            ]);
        }
    }

    /**
     * Создание поста.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new PostBase();
       	return $this->saveModel($model, 'create');
    }

    /**
     * Редактирование поста.
     * @param string $id идентификатор редактируемого поста
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->saveModel($model, 'update');
    }

    /**
     * Удаление поста.
     * @param string $id идентификатор удаляемого поста
     * @return Response
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->actionIndex();

        //return $this->redirect(['index']);
    }

    /**
     * Finds the PostBase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PostBase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostBase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function isUserAuthor()
    {   
        $requestModel = $this->findModel(Yii::$app->request->get('id'));
        if($requestModel == null){
            throw new NotFoundHttpException("Null request in isUserAuthor() by id");
        }
        return $requestModel->author->id == Yii::$app->user->id;
    }
}
