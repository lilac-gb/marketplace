<?php
namespace common\models;

use common\components\ActiveRecord;
use common\components\ImageAttachmentBehavior;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $username
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $verification_token
 * @property string  $confirmation_secret
 * @property string  $role
 * @property string  $email
 * @property string  $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $oldPassword;

    const SCENARIO_SIGN_UP = 'signup';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_UPDATE = 'user-update';
    const SCENARIO_UPDATE_PASSWORD = 'password-update';

    const ROLE_ADMIN = "admin";
    const ROLE_GUEST = "guest";
    const ROLE_USER = "user";

    const STATUS_INACTIVE = 0;
    const STATUS_EMAIL_NC = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = -1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
            'avatarBehavior' => [
                'class' => ImageAttachmentBehavior::class,
                'type' => 'user',
                'previewWidth' => 200,
                'previewHeight' => 200,
                'extension' => 'jpg',
                'directory' => Yii::getAlias('@frontend') . '/uploads/users',
                'url' => Yii::$app->params['domainFrontend'] . '/uploads/users',
                'versions' => [
                    'i300x300' => function ($img) {
                        $width = 300;
                        $height = 300;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'unique', 'message' => 'Такой никнейм уже есть'],
            ['email', 'unique', 'message' => 'Такой пользователь уже есть'],
            [[
                'password_hash', 'password_reset_token', 'verification_token',
                'username', 'last_name', 'first_name', 'confirmation_secret', 'role',
                'email', 'auth_key', 'password'
            ], 'string'],
            [
                ['password', 'oldPassword'], 'required',
                'on' => self::SCENARIO_UPDATE_PASSWORD, 'message' => '{attribute} обязательно',
            ],
            [['created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [
                self::STATUS_ACTIVE,
                self::STATUS_INACTIVE,
                self::STATUS_DELETED,
                self::STATUS_EMAIL_NC,
            ]],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios = array_merge($scenarios, [
            self::SCENARIO_SIGN_UP => [
                'first_name', 'email',
            ],
            self::SCENARIO_LOGIN => [
                'email', 'password',
            ],
            self::SCENARIO_UPDATE => [
                'first_name', 'last_name', 'email', 'username', 'password', 'oldPassword',
            ],
            self::SCENARIO_UPDATE_PASSWORD => [
                'password', 'oldPassword',
            ],
        ]);

        return $scenarios;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Никнейм',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'status' => 'Статус',
            'created_at' => 'Создан',
            'updated_at' => 'Обнавлен',
            'password ' => 'Пароль',
        ];
    }

    public function beforeValidate()
    {
        $parentValidationResult = parent::beforeValidate();

        if ($parentValidationResult) {
            if (!is_null($this->username)) {
                $this->username = strtolower($this->username);
            }

            if (!$this->isNewRecord) {
                if (preg_match('/[А-Яа-яЁё]/u', $this->username)) {
                    $this->addError('username', 'Никнэйм не может содержать русские символы');

                    return false;
                }
            }

            if (is_numeric($this->username)) {
                $this->addError('username', 'Никнэйм не может содержать последовательность цифр');

                return false;
            }

            return true;
        }

        if ($parentValidationResult) {
            return true;
        }

        return false;
    }


    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        if ($insert && !$this->password_hash) {
            $this->status = self::STATUS_INACTIVE;
        }

        if (empty($this->auth_key)) {
            $this->generateAuthKey();
        }

        if (empty($this->verification_token)) {
            $this->generateEmailVerificationToken();
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
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

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
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
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getFullName()
    {
        return $this->first_name . (!empty($this->last_name) ? ' ' . $this->last_name : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
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
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getAvatar($version = 'i300x300')
    {
        $result = '/img/no-photo.jpg';

        if ($this->getBehavior('avatarBehavior')->hasImage()) {
            $result = $this->getBehavior('avatarBehavior')->getUrl($version);
        }

        return $result;
    }

    public static function list($prompt = false)
    {
        $result = [];

        if($prompt)
            $result[0] = $prompt;

        $users = self::find()->where(['status' => User::STATUS_ACTIVE])->all();

        foreach($users as $user)
            $result[$user->id] = $user->username;

        return $result;
    }

}
