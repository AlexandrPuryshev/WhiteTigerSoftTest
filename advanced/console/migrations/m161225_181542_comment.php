<?php

use yii\db\Migration;

class m161225_181542_comment extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'parentId' => $this->integer(),
            'title' => $this->string()->notNull(),
            'content' => $this->string()->notNull(),
            'postId' => $this->integer(),
            'authorId' => $this->integer()
        ]);

        $this->addForeignKey(
            'FK_comment_author', '{{%comment}}', 'authorId', '{{%user}}', 'id', 'SET NULL', 'CASCADE'
        );

        $this->addForeignKey(
            'FK_comment_post', '{{%comment}}', 'postId', '{{%post}}', 'id', 'SET NULL', 'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
