<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

	const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    private $authKey;

    public static function tableName()
    {
        return '{{%user}}';
    }

      /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }
 

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }


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
	    return Yii::$app->security->validatePassword($password, $this->password);
	}

	/**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
