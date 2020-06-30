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
            'email',
            'created_at',
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