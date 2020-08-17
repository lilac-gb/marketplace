<?php

namespace api\controllers;


class OrderController extends \api\modules\v1\controllers\OrderController
{
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['order'],
        'patterns' => [
            'OPTIONS' => 'options',
            'OPTIONS my' => 'options',
            'OPTIONS ads' => 'options',
            'OPTIONS {id}/delete' => 'options',

            'GET my' => 'my',
            'GET,HEAD {id}' => 'view',
            'PUT,PATCH {id}' => 'update',
            'DELETE {id}/delete' => 'delete',
            'POST' => 'create',
            'POST ads' => 'ads',

            'OPTIONS {id}' => 'options',
        ],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{user_id}' => '<user_id:\\d[\\d,]*>',
        ],
    ];

    public $modelClass = 'api\models\Order';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
    ];
}