<?php

namespace common\services;

use common\models\User;
use Yii;

class UserService
{
    const REG_TYPE_EMAIL = 0;
    const REG_TYPE_PHONE = 1;

    const KEEP_ALIVE_TIME = [
        self::REG_TYPE_EMAIL => 3 * 24 * 60 * 60,
        self::REG_TYPE_PHONE => 5 * 60,
    ];

    const TOKEN_LENGTH = [
        self::REG_TYPE_EMAIL => 40,
    ];

    public static function sendRestorePasswordEmail(User $model)
    {

        $link = Yii::$app->params['domainFrontend']
            . '/users/restore-password?token='
            . $model->password_reset_token;

        return EmailService::sendWithView(
            $model->email,
            'Восстановление пароля',
            'reset-password',
            [
                'model' => $model,
                'link' => $link,
            ]
        );
    }

    public static function sendPassword(User $model, $password)
    {
        return EmailService::sendWithView(
            $model->email,
            'Ваш временный пароль',
            'send-password',
            [
                'model' => $model,
                'password' => $password,
            ]
        );
    }

    public static function sendChangeEmailConfirmation(User $model, string $newEmail, string $confirmationHash)
    {

        $link = Yii::$app->params['domainFrontend']
            . '/users/'
            . $model->id
            . '/confirm-email?hash='
            . urlencode($confirmationHash);

        return EmailService::sendWithView(
            $newEmail,
            'Подтверждение актуальности нового email',
            'change-email-confirmation',
            [
                'newEmail' => $newEmail,
                'link' => $link,
            ]
        );
    }

    public static function sendActivationEmail(User $model)
    {

        $confirmationSecret = $model->confirmation_secret;
        $encrypt = Yii::$app->security->encryptByKey($model->email, $confirmationSecret);
        $confirmationHash = base64_encode($encrypt);

        $link = Yii::$app->params['domainFrontend'] . '/activation?id=' . $model->id . '&hash=' . $confirmationHash;

        return EmailService::sendWithView(
            $model->email,
            'Активация аккаунта',
            'email-confirmation',
            [
                'email' => $model->email,
                'link' => $link,
            ]
        );
    }

}