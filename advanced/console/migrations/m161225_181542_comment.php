<?php

use yii\db\Migration;
use common\models\Comment;

class m161225_181542_comment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->string()->notNull(),
            'publish_status' => "enum('" . Comment::STATUS_MODERATE . "','" . Comment::STATUS_PUBLISH . "') NOT NULL DEFAULT '" . Comment::STATUS_MODERATE . "'",
            'postId' => $this->integer(),
            'authorId' => $this->integer()
        ], $tableOptions);

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
