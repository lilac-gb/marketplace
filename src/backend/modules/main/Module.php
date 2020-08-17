<?php

namespace backend\modules\main;

use Yii;

class Module extends \backend\components\Module
{
    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.main.setting.index')) {
            $return['Настройки' ] = [
                Yii::$app->urlManager->createUrl('/main/setting'),
                Yii::$app->urlManager->createUrl('/main/setting/index'),
            ];
        }
        return $return;
    }
}
