<?php
namespace api\models;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class User extends \common\models\User {
    public function fields() {
        return [
            'id',
            'username',
            'first_name',
            'last_name',
            'created_at',
            'images' => function () {
                return [
                    'avatar' => $this->getAvatar('i300x300') ?? '',
                    'original' => $this->getAvatar('original') ?? '',
                    'preview' => $this->getAvatar('preview') ?? '',
                ];
            },
        ];
    }

    public function extraFields()
    {
        return [
            'email' => function () {
                return (\Yii::$app->user->id == $this->id) ? $this->email : null;
            },
            'role' => function () {
                return !\Yii::$app->user->isGuest ? $this->role : null;
            },
        ];
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        $token = (new Parser())->parse((string) $token);

        if ($token->validate(new ValidationData())) {
            return self::findOne($token->getClaim('uid'));
        }

        return null;
    }
}