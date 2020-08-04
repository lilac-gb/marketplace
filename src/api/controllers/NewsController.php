<?php
namespace api\controllers;

class NewsController extends \api\modules\v1\controllers\NewsController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['news'],
        'extraPatterns' => [
            'OPTIONS my' => 'options',
            'OPTIONS main-popular-news' => 'options',
            'OPTIONS create' => 'options',
            'OPTIONS {id}/publish' => 'options',
            'OPTIONS galleryApi' => 'options',

            'GET my' => 'my',
            'POST {id}/publish' => 'publish',
            'POST galleryApi' => 'galleryApi',
            'GET main-popular-news' => 'main-popular-news',
            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
            '{id}' => '<id:[a-z0-9-_]+>',
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