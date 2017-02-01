<?php

namespace frontend\controllers;

use common\models\db\CategoryModel;
use common\models\db\UserModel;
use common\models\LoginForm;
use frontend\models\CommentForm;
use common\models\db\CommentModel;
use Yii;
use common\models\db\PostModel;
use common\models\db\PostSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use frontend\models\UploadForm;
use yii\web\UploadedFile;
use yii\bootstrap\Alert;
use yii\db\Expression;
use yii\data\Pagination;
use yii\web\Controller;
use yii\helpers\Url;
/**
 * CRUD операции модели "Посты".
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'index', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'view', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ], 
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (PostModel::isUserAuthor()) {
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
                    'delete' => ['post'],
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
    protected function renderIndex($dataProvider, $сategoryModel, $whatRender)
    {
    	$postQuery = $сategoryModel->findCategoryes();

    	$dataProviderCategory = new ActiveDataProvider([
    			'query' => $postQuery,
    	]);

        return $this->render($whatRender, [
            'models' => $dataProvider,
            'categories' => $dataProviderCategory,
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
        $сategoryModel = new CategoryModel();
        $postQuery = PostModel::findMyPublishedPosts();
        $dataProvider = $this->newPostDataProvider($postQuery);
        return $this->renderIndex($dataProvider, $сategoryModel, 'index');
    }

    /**
     * Просмотр поста.
     * @param string $id идентификатор поста
     * @return string
     */
    public function actionView($id)
    {
        $postModel = new PostModel();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'commentForm' => new CommentForm(Url::to(['comment/add', 'id' => $id])),
        ]);
    }

    private function saveModel($model, $view){
        $upload = new UploadForm();
        /* push auto author id in field author_id */
        $model->authorId = Yii::$app->user->id;
        $upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
        // if you update, older image you delete, if uploading image is null
        if ($view == 'update') {
            $model->image = null;
        }

        if ($upload->upload()) {  
            $model->image = $upload->name;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render($view, [
                'model' => $model,
                'image' => $upload,
                'authors' => UserModel::find()->all(),
                'category' => CategoryModel::find()->all()
            ]);
        }
    }

    /**
     * Создание поста.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new PostModel();
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
     * Проверка на автора поста
     * @return bool
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*protected function isUserAuthor()
    {   
        Yii::warning(PostModel::find()->one()->getAuthor()->one()->id);
        $requestModel = $this->findModel(Yii::$app->request->get('id'));
        if ($requestModel == null) {
            throw new NotFoundHttpException("Null request in isUserAuthor() by id");
        }
        return $requestModel->author->id == Yii::$app->user->id;
    }*/

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PostModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
