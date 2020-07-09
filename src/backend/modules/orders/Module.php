<?php

namespace backend\modules\orders;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\orders\controllers';
    public $defaultRoute = 'order/index';

    public function menu()
    {
        $return = [];

        if (Yii::$app->user->can('backend.orders.order')) {
            $return['Заказы'] = [
                Yii::$app->urlManager->createUrl('/orders/order'),
                Yii::$app->urlManager->createUrl('/orders/order/index'),
            ];
        }

        return $return;
    }
}
