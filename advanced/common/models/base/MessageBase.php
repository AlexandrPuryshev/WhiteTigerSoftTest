<?php

namespace common\models\base;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class MessageBase extends ActiveRecord
{

	/**
	 * @return string
	 */
	public static function tableName() 
	{
		return '{{%messsege}}';
	}

	/**
	 * @return array
	 */
	public function behaviors() 
	{
		return 
		[
			[
				'class'              => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'updatedAt',
				'value'              => function() {
					return date('Y-m-d H:i:s');
				},
			],
		];
	}
}