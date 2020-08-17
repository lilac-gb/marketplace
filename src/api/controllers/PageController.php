<?php
namespace api\controllers;

class PageController extends \api\modules\v1\controllers\PageController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['page'],
        'only' => ['view', 'options'],
        'extraPatterns' => [
            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
        ],
    ];

    public $modelClass = 'api\models\Page';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
        'metaTagsEnvelope' => '_metaTags',
        'metaTagsProvider' => 'metaTagsProvider',
    ];
}