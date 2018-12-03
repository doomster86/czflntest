<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $role
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS = array(
        '0' => array(
            'name' => 'Заблоковано',
            'cssClass' => 'blocked',
            'status' => '0',
        ),
        '1' => array(
            'name' => 'Активний',
            'cssClass' => 'active',
            'status' => '1',
        ),
    );

    const ROLES = array(
        '0' => array (
            'name' => 'Слухач',
            'img' => '/img/student.png',
            'roles' =>'0',
        ),
        '1' => array(
            'name' => 'Адміністратор',
            'img' => '/img/admin.png',
            'roles' => '1',
        ),
        '2' => array(
            'name' => 'Викладач',
            'img' => '/img/lector.png',
            'roles' => '2',
        ),
    );

    /**
     * @inheritdoc
     */
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
            [['username','firstname', 'middlename', 'lastname', 'phone'], 'safe'],
            [['email'], 'required'],
            [['firstname', 'middlename', 'lastname'], 'unique', 'targetAttribute' => ['firstname', 'middlename', 'lastname'], 'message'=>'Такий користувач вже існує'],
            ['status', 'default', 'value' => self::STATUS['1']['status'] ],
            ['status', 'in', 'range' => [ self::STATUS['1']['status'], self::STATUS['0']['status'] ]],
            ['role', 'default', 'value' => self::ROLES['0']['roles'] ],
            ['role', 'in', 'range' => [ self::ROLES['0']['roles'], self::ROLES['1']['roles'], self::ROLES['2']['roles'] ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS['1']['status'] ]);
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
        return static::findOne(['username' => $username, 'status' => self::STATUS['1']['status'] ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
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

    /**
     * Reset password
     */
    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS['1']['status'],
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /*
     * Custom get methods
     */
    public function getRegisterDate()
    {
        return date('d.m.Y', $this->created_at);
    }

    /*
     * Get teacher names format Фамилия И.О.
     */
    public function getFullName()
    {
        return $this->lastname . ' ' . mb_substr($this->firstname, 0, 1) . '.' . mb_substr($this->middlename, 0, 1) .'.';
    }
}
