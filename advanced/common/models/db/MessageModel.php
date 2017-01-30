<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class MessageModel extends Message
{

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