<?php

namespace common\services;

use common\models\Ad;
use common\models\Setting;
use Yii;

class AdService
{

    public static function sendNotificationEmail(Ad $model)
    {
        $link = Yii::$app->params['domainFrontend'] . $model->getUrl();
        $publish_ad_email = Setting::getValue('publish_ad_email');

        return EmailService::sendWithView(
            explode(',', $publish_ad_email),
            'Подано объявление на модерацию #' . $model->id,
            'ad-notification',
            [
                'link' => $link,
                'ad' => $model,
                'user' => $model->user,
            ]
        );
    }
}