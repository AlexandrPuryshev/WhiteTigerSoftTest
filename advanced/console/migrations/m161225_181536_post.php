<?php

use yii\db\Migration;
use common\models\Post;

class m161225_181536_post extends Migration
{

    public function safeUp()
    {

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'anons' => $this->text()->notNull(),
            'content' => $this->text()->notNull(),
            'categoryId' => $this->integer(),
            'authorId' => $this->integer(),
            'image' => $this->string(),
            'publishStatus' => "enum('" . Post::STATUS_DRAFT . "','" . Post::STATUS_PUBLISH . "') NOT NULL DEFAULT '" . Post::STATUS_DRAFT . "'",
            'createdAt' => $this->string()->notNull(),
            'updatedAt' => $this->string()->notNull(),
        ], $tableOptions);

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
