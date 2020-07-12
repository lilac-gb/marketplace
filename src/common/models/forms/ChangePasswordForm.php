<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;

/**
 * Change password form
 */
class ChangePasswordForm extends Model
{
    public $oldPassword;
    public $newPassword;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword'], 'required'],
            ['oldPassword', 'checkOldPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
        ];
    }

    /**
     * Validates the old password.
     * This method serves as the inline validation for old password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params the additional name-value pairs given in the rule
     */
    public function checkOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->getIdentity();

            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Старый пароль указан неверно.');
            }
        }
    }

    /**
     * Changes user's password with new password value
     *
     * @return bool whether the user is logged in successfully
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->getIdentity();
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();

            $user->save(false);

            Yii::$app->user->logout();
            Yii::$app->user->login($user);
            Yii::$app->user->refreshToken();

            return $user;
        } else {
            return false;
        }
    }
}
