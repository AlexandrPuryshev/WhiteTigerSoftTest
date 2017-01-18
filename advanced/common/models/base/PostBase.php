<?php

namespace common\models\base;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\base\UserBase;
use common\models\base\CommentBase;
use common\models\base\CategoryBase;


/**
 * Модель постов.
 *
 * @property string $id
 * @property string $title заголовок
 * @property string $anons анонс
 * @property string $content контент
 * @property string $category_id категория
 * @property string $author_id автор
 * @property string $publish_status статус публикации
 * @property string $publish_date дата публикации
 *
 * @property User $author
 * @property Category $category
 * @property Comment[] $comments
 */
class PostBase extends ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['categoryId', 'authorId'], 'integer'],
            [['anons', 'content'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'anons' => 'Anons',
            'content' => 'Content',
            'category' => 'Category',
            'categoryId' => 'Category',
            'author' => 'Author',
            'authorId' => 'Author',
            'publishStatus' => 'Status of publish'
        ];
    }


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
     * @return ActiveDataProvider
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
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(UserBase::className(), ['id' => 'authorId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CategoryBase::className(), ['id' => 'categoryId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(CommentBase::className(), ['postId' => 'id']);
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
}
