<?php

namespace api\modules\v1\controllers;

use api\components\ActiveController;

class MenuController extends ActiveController
{
    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator']['optional'] = ['index', 'view'];

        return $behaviors;
    }
}
