<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "social_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $authKey
 * @property string $passwordHash
 * @property string $passwordResetToken
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Comment[] $comments
 * @property Post[] $posts
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'authKey', 'passwordHash', 'email'], 'required'],
            [['role', 'status'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['username', 'passwordHash', 'passwordResetToken', 'email'], 'string', 'max' => 255],
            [['authKey'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['passwordResetToken'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'authKey' => 'Auth Key',
            'passwordHash' => 'Password Hash',
            'passwordResetToken' => 'Password Reset Token',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['authorId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['authorId' => 'id']);
    }
}
