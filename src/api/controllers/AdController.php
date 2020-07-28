<?php
/**
 * Created by PhpStorm.
 * User: rogozhuk
 * Date: 20.03.17
 * Time: 1:15
 */

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
            'OPTIONS imgAttachApi/{id}' => 'options',
            'OPTIONS imgAttachApi/{url}' => 'options',
            'OPTIONS {id}/publish' => 'options',
            'OPTIONS ads-sections' => 'options',
            'OPTIONS ads-types' => 'options',

            'GET my' => 'my',
            'POST imgAttachApi/{id}' => 'imgAttachApi',
            'POST imgAttachApi/{url}' => 'imgAttachApi',
            'POST {id}/publish' => 'publish',

            'GET ads-sections' => 'ads-sections',
            'GET ads-types' => 'ads-types',

            'GET,HEAD {id}' => 'view',
            'OPTIONS {id}' => 'options',
        ],
        'tokens' => [
            '{id}' => '<id:[a-z0-9-_]+>',
        ],
    ];
}