<?php

namespace common\models\db;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class UserModel extends User implements \yii\web\IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 100;


    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
       return [
            'timestamp' => [
                'class' => '\yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createdAt', 'updatedAt'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }*/

    /**
      * @inheritdoc
      */
     public function rules()
     {
         return [
             [['username', 'email'], 'required'],
             [['passwordHash'], 'required', 'on' => 'createUser'],
             [['email'], 'email'],
             [['username', 'passwordHash'], 'string'],

             ['status', 'default', 'value' => self::STATUS_ACTIVE],
             ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

             ['role', 'default', 'value' => self::ROLE_USER],
             ['role', 'in', 'range' => [self::ROLE_USER]],
         ];
     }

 

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    

	/**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

	  /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

	public function getId()
	{
	    return $this->getPrimaryKey();
	}

	public function getAuthKey()
	{
	    return $this->authKey;
	}    

	public function validateAuthKey($authKey)
	{
	    return $this->getAuthKey() === $authKey;
	}

	public function validatePassword($password)
	{
	    return Yii::$app->security->validatePassword($password, $this->passwordHash);
	}

     /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->passwordHash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }
}
