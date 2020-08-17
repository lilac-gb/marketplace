<?php

namespace backend\modules\company;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\company\controllers';
    public $defaultRoute = 'company/index';

    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.company')) {
            $return['Компании'] = [
                Yii::$app->urlManager->createUrl('/company'),
                Yii::$app->urlManager->createUrl('/company/index'),
            ];
        }

        return $return;
    }
}
