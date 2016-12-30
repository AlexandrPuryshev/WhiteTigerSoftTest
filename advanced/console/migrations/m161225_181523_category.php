<?php

use yii\db\Migration;

class m161225_181523_category extends Migration
{


    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string()
        ], $tableOptions);

        $this->createIndex('name', '{{%category}}', 'name', true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
