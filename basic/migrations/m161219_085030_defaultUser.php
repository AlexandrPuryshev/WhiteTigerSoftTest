<?php

use yii\db\Migration;

class m161219_085030_defaultUser extends Migration
{
    public function up()
    {
        $this->insert('{{social_user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'email' => 'Kronos0041@gmail.com',
            'role' => '100',
            'status' => '10',
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }

    public function down()
    {
        $this->delete('{{user}}', 'username = "admin"');
    }
}
