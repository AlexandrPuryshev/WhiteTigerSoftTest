<?php

use yii\db\Migration;
use common\models\db\Post;

class m161225_181536_post extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'anons' => $this->text()->notNull(),
            'content' => $this->text()->notNull(),
            'categoryId' => $this->integer(),
            'authorId' => $this->integer(),
            'image' => $this->string(),
            'publishStatus' => "enum('" . Post::STATUS_DRAFT . "','" . Post::STATUS_PUBLISH . "') NOT NULL DEFAULT '" . Post::STATUS_DRAFT . "'",
            'createdAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',   
            'updatedAt' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);

         $this->addForeignKey(
            'FK_post_author', '{{%post}}', 'authorId', '{{%user}}', 'id', 'SET NULL', 'CASCADE'
        );

        $this->addForeignKey(
            'FK_post_category', '{{%post}}', 'categoryId', '{{%category}}', 'id', 'SET NULL', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}
