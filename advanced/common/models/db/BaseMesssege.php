<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "social_messsege".
 *
 * @property integer $id
 * @property string $content
 * @property string $userName
 * @property string $createdAt
 */
class BaseMesssege extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_messsege';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['createdAt'], 'safe'],
            [['userName'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'userName' => 'User Name',
            'createdAt' => 'Created At',
        ];
    }
}
