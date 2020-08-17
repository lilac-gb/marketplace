<?php

namespace api\components;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Request-Method: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 3600');

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class Controller extends \yii\rest\Controller
{
    public $serializer = 'api\components\Serializer';

    public function actions()
    {
        return array_merge(parent::actions(), [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        $behaviors['authenticator'] = $auth;

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['options'],
            'authMethods' => [
                [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'token',
                ],
                HttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }
}
