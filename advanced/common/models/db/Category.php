<?php

namespace common\models\db;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class Category extends BaseCategory
{
    /**
     * Возвращает список постов принадлежащих категории.
     * @return ActiveQuery
     */
    public function findPostsByCategoryId($id)
    {
        return Post::find()->where([
                'categoryId' => $id,
        ]);
    }

    /**
     * Возвращает список категорий
     * @return ActiveQuery
     */
    public function findCategoryes()
    {
        return Category::find();
    }

     /**
     * Возвращает модель категории.
     * @param int $id идентификатор категории
     * @throws NotFoundHttpException в случае, когда категория не найдена
     * @return Category
     */
    public function getCategory($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
    }

}
