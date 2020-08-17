<?php

namespace backend\modules\news;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\news\controllers';
    public $defaultRoute = 'news/index';

    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.news')) {
            $return['Новости'] = [
                Yii::$app->urlManager->createUrl('/news'),
                Yii::$app->urlManager->createUrl('/news/news'),
            ];
        }

        return $return;
    }
}
