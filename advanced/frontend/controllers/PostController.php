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
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true
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
    public function actionIndex()
    {
        $post = new Post();
        $category = new Category();

        return $this->render('index', [
            'posts' => $post->getPublishedPosts(),
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

    /**
     * Создание поста.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Post();
        $upload = new UploadForm();
        /* push autodata in field publish_date */
        $model->createdAt = new Expression('NOW()');
        $model->updatedAt = new Expression('NOW()');
        /* push auto author id in field author_id */
        $model->authorId = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
                $upload->upload();
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'image' => $upload,
                'category' => Category::find()->all(),
                'authors' => User::find()->all()
            ]);
        }
    }

    /**
     * Редактирование поста.
     * @param string $id идентификатор редактируемого поста
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $upload = new UploadForm();

        /* push autodata in field publish_date */
        $model->updatedAt = new Expression('NOW()');
        /* push auto author id in field author_id */
        $model->authorId = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $upload->imageFile = UploadedFile::getInstance($upload, 'imageFile');
                $upload->upload();
                return $this->redirect(['view', 'id' => $model->id]);
        }
        else {
            return $this->render('update', [
                'model' => $model,
                'image' => $upload,
                'authors' => User::find()->all(),
                'category' => Category::find()->all()
            ]);
        }
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

        $this->actionIndex();

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
}
