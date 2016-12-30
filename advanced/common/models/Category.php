<?php

namespace common\models;

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
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Category',
            'description' => 'Description',
        ];
    }

    /**
     * Возвращает список постов принадлежащих категории.
     * @return ActiveDataProvider
     */
    public function getPosts()
    {
        return new ActiveDataProvider([
            'query' => Post::find()
                ->where([
                    'category_id' => $this->id,
                    'publish_status' => Post::STATUS_PUBLISH
                ])
        ]);
    }

     /**
     * Возвращает список категорий
     * @return ActiveDataProvider
     */
    public function getCategories()
    {
        return new ActiveDataProvider([
            'query' => Category::find()
        ]);
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
