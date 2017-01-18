<?php

namespace common\models\base;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use common\models\base\UserBase;
use common\models\base\PostBase;

/**
 * Модель комментариев.
 *
 * @property integer $id
 * @property integer $pid идентификатор родительского комментария
 * @property string $title заголовок
 * @property string $content комментарий
 * @property string $publish_status статус публикации
 * @property integer $post_id идентификатор поста, к которому относится комментарий
 * @property integer $author_id идентификатор автора комментария
 *
 * @property Post $post
 * @property User $author
 */
class CommentBase extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'authorId'], 'integer'],
            [['title', 'content'], 'required'],
            [['title', 'content'], 'string', 'max' => 255]
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
            'content' => 'Content',
            'postId' => 'Post ID',
            'authorId' => 'Author ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(PostBase::className(), ['id' => 'postId']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(UserBase::className(), ['id' => 'authorId']);
    }

    /**
     * Возвращает комментарий.
     * @param int $id идентификатор комментария
     * @throws NotFoundHttpException
     * @return Comment
     */
    protected function findModel($id)
    {
        if (($model = CommentBase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
