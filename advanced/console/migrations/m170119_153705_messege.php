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
            'image' => $this->string(),
            'createdAt' => $this->dateTime() . ' DEFAULT NOW()',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%messsege}}');
    }
}
