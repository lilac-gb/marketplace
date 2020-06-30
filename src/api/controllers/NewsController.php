<?php
namespace api\controllers;

class NewsController extends \api\modules\v1\controllers\NewsController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['news'],
        'only' => ['index', 'view', 'options'],
        'extraPatterns' => [
            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
        ],
    ];

    public $modelClass = 'api\models\News';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
        'metaTagsEnvelope' => '_metaTags',
        'metaTagsProvider' => 'metaTagsProvider',
    ];
}