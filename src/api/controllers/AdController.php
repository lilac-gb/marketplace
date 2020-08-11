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
            'OPTIONS sections' => 'options',
            'OPTIONS create' => 'options',
            'OPTIONS imgAttachApi/{id}' => 'options',
            'OPTIONS imgAttachApi/{url}' => 'options',
            'OPTIONS {id}/publish' => 'options',
            'OPTIONS {url}/publish' => 'options',
            'OPTIONS {id}/delete' => 'options',
            'OPTIONS {url}/delete' => 'options',
            'OPTIONS {id}/update' => 'options',
            'OPTIONS {url}/update' => 'options',
            'OPTIONS ads-sections' => 'options',
            'OPTIONS ads-types' => 'options',
            'OPTIONS main-popular-ads' => 'options',

            'GET my' => 'my',
            'POST imgAttachApi/{id}' => 'imgAttachApi',
            'POST imgAttachApi/{url}' => 'imgAttachApi',
            'POST {id}/publish' => 'publish',
            'POST {url}/publish' => 'publish',
            'PUT {id}/update' => 'update',
            'PUT {url}/update' => 'update',
            'DELETE {id}/delete' => 'delete',
            'DELETE {url}/delete' => 'delete',
            'GET ads-sections' => 'ads-sections',
            'GET main-popular-ads' => 'main-popular-ads',
            'GET ads-types' => 'ads-types',

            'GET,HEAD {id}' => 'view',
            'OPTIONS {id}' => 'options',
        ],
        'tokens' => [
            '{id}' => '<id:[a-z0-9-_]+>',
        ],
    ];
}