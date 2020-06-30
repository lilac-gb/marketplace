<?php

namespace backend\modules\user;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\user\controllers';
    public $defaultRoute = 'user/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.user')) {
            $return['Пользователи'] = [
                Yii::$app->urlManager->createUrl('/user'),
                Yii::$app->urlManager->createUrl('/user/user'),
            ];
        }

        return $return;
    }
}
