<?php

namespace api\controllers;

class CompanyController extends \api\modules\v1\controllers\CompanyController
{
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['company'],
        'extraPatterns' => [
            'OPTIONS my' => 'options',
            'OPTIONS create' => 'options',
            'OPTIONS {id}/publish' => 'options',
            'OPTIONS {url}/publish' => 'options',
            'OPTIONS {id}/delete' => 'options',
            'OPTIONS {url}/delete' => 'options',
            'OPTIONS {id}/update' => 'options',
            'OPTIONS {url}/update' => 'options',
            'OPTIONS imgAttachApi' => 'options',

            'GET my' => 'my',
            'POST {id}/publish' => 'publish',
            'POST {url}/publish' => 'publish',
            'PUT {id}/update' => 'update',
            'PUT {url}/update' => 'update',
            'DELETE {id}/delete' => 'delete',
            'DELETE {url}/delete' => 'delete',
            'POST imgAttachApi' => 'imgAttachApi',

            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
            '{id}' => '<id:[0-9-_]+>',
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