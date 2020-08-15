<?php

namespace api\controllers;


class AdController extends \api\modules\v1\controllers\AdController
{
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['ad'],
        'extraPatterns' => [
            'OPTIONS my' => 'options',
            'OPTIONS create' => 'options',
            'OPTIONS galleryApi' => 'options',
            'OPTIONS {id}/publish' => 'options',
            'OPTIONS {id}/delete' => 'options',
            'OPTIONS {id}/update' => 'options',
            'OPTIONS ads-sections' => 'options',
            'OPTIONS ads-types' => 'options',
            'OPTIONS main-popular-ads' => 'options',

            'GET my' => 'my',
            'POST galleryApi' => 'galleryApi',
            'POST {id}/publish' => 'publish',
            'PUT {id}/update' => 'update',
            'DELETE {id}/delete' => 'delete',
            'GET ads-sections' => 'ads-sections',
            'GET ads-types' => 'ads-types',
            'GET main-popular-ads' => 'main-popular-ads',

            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
            '{id}' => '<id:[0-9-_]+>',
        ],
    ];

    public $modelClass = 'api\models\Ad';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
        'metaTagsEnvelope' => '_metaTags',
        'metaTagsProvider' => 'metaTagsProvider',
    ];
}