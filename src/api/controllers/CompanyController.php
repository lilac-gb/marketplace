<?php

namespace api\controllers;

class CompanyController extends \api\modules\v1\controllers\CompanyController
{
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['company'],
        'only' => ['view', 'options', 'index'],
        'extraPatterns' => [
            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
        ],
    ];

    public $modelClass = 'api\models\Company';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
        'metaTagsEnvelope' => '_metaTags',
        'metaTagsProvider' => 'metaTagsProvider',
    ];
}