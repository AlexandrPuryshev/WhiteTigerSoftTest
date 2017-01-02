<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Tags;
use common\models\User;
use common\models\LoginForm;
use common\models\Comment;
use Yii;
use common\models\Post;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\UploadForm;
use yii\web\UploadedFile;
use yii\bootstrap\Alert;
use yii\db\Expression;
use yii\data\Pagination;
/**
 * CRUD операции модели "Посты".
 */
class PostController extends Controller
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Список постов.
     * @return string
     */
    public function actionIndex($isAjax = 'render')
    {
        $post = new Post();
        $category = new Category();

        $postQuery = Post::findAuthorPublishedPosts();

        //Pages Navigation
        $countQuery = clone $postQuery;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 2]);
        $pages->pageSizeParam = false;
        $models = $postQuery->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        return $this->$isAjax('index', [
            'models' => $models,
            'pages' => $pages,
            'categories' => $category->getCategories()
        ]);
    }

    /**
     * Просмотр поста.
     * @param string $id идентификатор поста
     * @return string
     */
    public function actionView($id)
    {
        $post = new Post();
        return $this->render('view', [
            'model' => $this->findModel($id),
            //'commentForm' => new Comment(Url::to(['comment/add', 'id' => $id])),
        ]);
    }

    private function saveModel($model, $view){
        $upload = new UploadForm();
    	$model->updatedAt = new Expression('NOW()');
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
                'authors' => User::find()->all(),
                'category' => Category::find()->all()
            ]);
        }
    }

    /**
     * Создание поста.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Post();
        /* push autodata in field publish_date */
        $model->createdAt = new Expression('NOW()');
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
        if (Yii::$app->request->isAjax)
        {
            return $this->actionIndex('renderAjax');
        }
        else
        {
            return $this->redirect(['index']);
        }

        //return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function isUserAuthor()
    {   
        return $this->findModel(Yii::$app->request->get('id'))->author->id == Yii::$app->user->id;
    }
}
