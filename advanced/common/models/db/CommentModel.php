<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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
class CommentModel extends Comment
{
    /**
     * Возвращает комментарий.
     * @param int $id идентификатор комментария
     * @throws NotFoundHttpException
     * @return Comment
     */
    protected function findModel($id)
    {
        if (($model = CommentModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
