<?php

use yii\db\Migration;
use common\models\db\UserModel;
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
            'role' => UserModel::ROLE_ADMIN,
            'status' => UserModel::STATUS_ACTIVE
        ]);
    }
}
