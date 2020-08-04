<?php

namespace common\services;

use common\models\News;
use common\models\Setting;
use Yii;

class NewsService
{

    public static function sendNotificationEmail(News $model)
    {
        $link = Yii::$app->params['domainFrontend'] . $model->getUrl();
        $publish_news_email = Setting::getValue('publish_news_email');

        return EmailService::sendWithView(
            $publish_news_email,
            'Подана публикация на модерацию #' . $model->id,
            'news-notification',
            [
                'link' => $link,
                'ad' => $model,
                'user' => $model->user,
            ]
        );
    }
}