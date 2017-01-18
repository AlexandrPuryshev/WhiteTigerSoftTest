<?php

use yii\db\Migration;
use common\models\User;
use yii\db\Expression;

class m161219_085030_defaultUser extends Migration
{
    public function safeUp()
    {
        $this->insert('{{social_user}}', [
            'username' => Yii::$app->params['adminUsername'],
            'authKey' => Yii::$app->security->generateRandomString(),
            'passwordHash' => Yii::$app->security->generatePasswordHash(Yii::$app->params['adminPassword']),
            'email' => Yii::$app->params['adminEmail'],
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE
        ]);
    }
}
