<?php

namespace api\components;

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

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

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
