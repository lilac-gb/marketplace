<?php

namespace api\modules\v1\controllers;
use api\components\ActiveController;

class PageController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'][] = '*';

        return $behaviors;
    }
}
