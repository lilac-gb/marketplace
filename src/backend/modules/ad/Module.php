<?php

namespace backend\modules\ad;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\ad\controllers';
    public $defaultRoute = 'ad/index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.ads')) {
            $return['Объявления'] = [
                Yii::$app->urlManager->createUrl('/ad'),
                Yii::$app->urlManager->createUrl('/ad/index'),
            ];
        }
        if (Yii::$app->user->can('backend.ads.sections')) {
            $return['Разделы объявлений'] = [
                Yii::$app->urlManager->createUrl('/ad/section'),
                Yii::$app->urlManager->createUrl('/ad/section/index'),
            ];
        }
        if (Yii::$app->user->can('backend.ads.types')) {
            $return['Тип объявления'] = [
                Yii::$app->urlManager->createUrl('/ad/type'),
                Yii::$app->urlManager->createUrl('/ad/type/index'),
            ];
        }

        return $return;
    }
}
