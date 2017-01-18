<?php

use yii\db\Migration;
use yii\db\Expression;

class m130524_201442_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'authKey' => $this->string(32)->notNull(),
            'passwordHash' => $this->string()->notNull(),
            'passwordResetToken' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'role' => $this->smallInteger()->notNull()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'createdAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',   
            'updatedAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

         $this->createIndex('username', '{{%user}}', 'username', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
