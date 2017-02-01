<?php

namespace common\models\db;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\db\UserModel;
use common\models\db\CommentModel;
use common\models\db\CategoryModel;

class PostModel extends Post
{
     /**
     * Статус поста: опубликованн.
     */
    const STATUS_PUBLISH = 'publish';
    /**
     * Статус поста: черновие.
     */
    const STATUS_DRAFT = 'draft';


    /**
     * Возвращает опубликованные комментарии
     * @return ActiveDataProvider
     */
    /*public function getPublishedComments()
    {
        return new ActiveDataProvider([
            'query' => $this->getComments()
                ->where(['publishStatus' => Comment::STATUS_PUBLISH])
        ]);
    }*/
    
    /**
     * Возвращает опубликованные комментарии
     * @return ActiveQuery
     */
    public static function findPublishedComments()
    {
        return self::find()->where(['publishStatus' => Comment::STATUS_PUBLISH]);
    }

    /**
     * Возвращает все опубликованные посты
     * @return ActiveQuery
     */
    public static function findPublishedPosts()
    {
        return self::find()->orderBy(['createdAt' => SORT_DESC]);
    }

     /**
     * Возвращает опубликованные посты автора
     * @return ActiveQuery
     */
    public static function findMyPublishedPosts()
    {
        return self::find()->where(['authorId' => Yii::$app->user->id]);
    }


    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Опубликован ли пост.
     * @return bool
     */
    protected function isPublished()
    {
        return $this->publishStatus === self::STATUS_PUBLISH;
    }

    /**
     * Возвращает модель поста.
     * @param int $id
     * @throws NotFoundHttpException в случае, когда пост не найден или не опубликован
     * @return Post
     */
    public function getPost($id)
    {
        if (
            ($model = Post::findOne($id)) !== null &&
            $model->isPublished()
        ) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
    }

     /**
     * Проверка на автора поста
     * @return bool
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function isUserAuthor()
    {   
        $authorId = Post::find()->one()->getAuthor()->one()->id;
        if ($authorId == null) {
            throw new NotFoundHttpException("Null request in isUserAuthor() by id");
        }
        return $authorId == Yii::$app->user->id;
    }

    /**
     * Возвращает опубликованные комментарии
     * @return ActiveDataProvider
     */
    public function getPublishedComments()
    {
        return new ActiveDataProvider([
            'query' => parent::getComments(),
        ]);
    }


}
