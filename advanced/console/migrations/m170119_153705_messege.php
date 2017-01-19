<?php

use yii\db\Migration;

class m170119_153705_messege extends Migration
{
     public function safeUp()
    {
        $this->createTable('{{%messsege}}', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->notNull(),
            'userName' => $this->string()->defaultValue(null),
            'createdAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',   
            'updatedAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%messsege}}');
    }
}
