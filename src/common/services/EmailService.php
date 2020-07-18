<?php

namespace common\services;

use Yii;

class EmailService
{
    const DEFAULT_FROM = ['post@vbs.one' => 'MarketPlace'];

    public static function sendWithBody($emailTo, $subject, $body, $from = self::DEFAULT_FROM)
    {
        return Yii::$app->mailer
            ->compose()
            ->setFrom($from)
            ->setTo($emailTo)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }

    public static function sendWithView($emailTo, $subject, $viewPath, $params = [], $from = self::DEFAULT_FROM)
    {
        return EmailService::composeWithView($emailTo, $subject, $viewPath, $params, $from)
            ->send();
    }

    public static function composeWithView($emailTo, $subject, $viewPath, $params = [], $from = self::DEFAULT_FROM)
    {
        return Yii::$app->mailer
            ->compose($viewPath, $params)
            ->setFrom($from)
            ->setTo($emailTo)
            ->setSubject($subject);
    }

    public static function composePublicationNewsWithView($emailTo, $subject, $viewPath, $params = [], $from = self::DEFAULT_FROM)
    {
        return Yii::$app->mailer
            ->compose($viewPath, $params)
            ->setFrom($from)
            ->setTo($emailTo)
            ->setSubject($subject)
            ->send();
    }

    public static function composeOrderWithView($emailTo, $subject, $viewPath, $params = [], $from = self::DEFAULT_FROM)
    {
        return Yii::$app->mailer
            ->compose($viewPath, $params)
            ->setFrom($from)
            ->setTo($emailTo)
            ->setSubject($subject)
            ->send();
    }
}