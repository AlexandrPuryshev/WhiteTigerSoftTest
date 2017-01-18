<?php

use yii\db\Migration;

class m161225_181523_category extends Migration
{


    public function safeUp()
    {   
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()
        ]);

        $this->createIndex('name', '{{%category}}', 'name', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
