<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use common\models\base\CommentBase;

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
class Comment extends CommentBase
{

}
