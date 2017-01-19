<?php

use yii\db\Migration;

class m170119_154025_like extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%like}}', [
            'id' => $this->primaryKey(),
            'messageId' => $this->integer()->defaultValue(null),
            'userName' => $this->string()->defaultValue(null),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%like}}');
    }
}
