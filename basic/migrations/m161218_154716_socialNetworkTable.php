<?php

use app\models\Post;
use app\models\Comment;
use yii\db\Schema;
use yii\db\Migration;

class m161218_154716_socialNetworkTable extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('username', '{{%user}}', 'username', true);

        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'description' => Schema::TYPE_STRING
        ], $tableOptions);

        $this->createIndex('name', '{{%category}}', 'name', true);

        $this->createTable('{{%listofusers}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('name', '{{%listofusers}}', 'name', true);


        $this->createTable('{{%post}}', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'anons' => Schema::TYPE_TEXT . ' NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER,
            'author_id' => Schema::TYPE_INTEGER,
            'publish_status' => "enum('" . Post::STATUS_DRAFT . "','" . Post::STATUS_PUBLISH . "') NOT NULL DEFAULT '" . Post::STATUS_DRAFT . "'",
            'publish_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], $tableOptions);

        $this->createIndex('FK_post_author', '{{%post}}', 'author_id');

        $this->addForeignKey(
            'FK_post_author', '{{%post}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE'
        );

        $this->createIndex('FK_post_category', '{{%post}}', 'category_id');

        $this->addForeignKey(
            'FK_post_category', '{{%post}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'CASCADE'
        );


         $this->createTable('{{%comment}}', [
            'id' => Schema::TYPE_PK,
            'pid' => Schema::TYPE_INTEGER,
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'content' => Schema::TYPE_STRING . ' NOT NULL',
            'publish_status' => "enum('" . Comment::STATUS_MODERATE . "','" . Comment::STATUS_PUBLISH . "') NOT NULL DEFAULT '" . Comment::STATUS_MODERATE . "'",
            'post_id' => Schema::TYPE_INTEGER,
            'author_id' => Schema::TYPE_INTEGER
        ], $tableOptions);

        $this->createIndex('FK_comment_author', '{{%comment}}', 'author_id');
        $this->addForeignKey(
            'FK_comment_author', '{{%comment}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE'
        );

        $this->createIndex('FK_comment_post', '{{%comment}}', 'post_id');
        $this->addForeignKey(
            'FK_comment_post', '{{%comment}}', 'post_id', '{{%post}}', 'id', 'SET NULL', 'CASCADE'
        );
    }


    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%listOfUsers}}');
    }
}
