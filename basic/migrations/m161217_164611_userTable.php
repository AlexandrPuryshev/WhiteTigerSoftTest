<?php

use yii\db\Schema;
use yii\db\Migration;

class m161217_164611_userTable extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
