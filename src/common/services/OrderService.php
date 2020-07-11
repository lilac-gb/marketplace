<?php

namespace common\services;

use common\models\Order;
use common\models\Setting;
use Yii;


class OrderService
{
    public static function sendNotificationEmail(Order $model)
    {
        $sender_email = Setting::findOne(['code' => 'sender_email', 'status' => 0]);
        $link = Yii::$app->urlManager->createAbsoluteUrl(['orders/order/update', 'id' => $model->id]);

        return EmailService::sendWithView(
            $sender_email->value,
            'Уведомление о заказе',
            'order/notification-new',
            ['model' => $model, 'link' => $link]
        );
    }

    public static function sendUserNotificationEmail(Order $model)
    {
        return EmailService::composeOrderWithView(
            $model->email,
            'Уведомление о заказе',
            'order/user-notification-new',
            ['model' => $model]
        );
    }
}