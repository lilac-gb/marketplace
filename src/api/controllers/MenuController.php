<?php
namespace api\controllers;

class MenuController extends \api\modules\v1\controllers\MenuController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['menu'],
        'only' => ['index', 'view', 'options'],
        'extraPatterns' => [
            'GET,HEAD {url}' => 'view',
            '{url}' => 'options',
        ],
        'extraTokens' => [
            '{url}' => '<id:[a-z0-9-_]+>',
        ],
    ];

    public $modelClass = 'api\models\Menu';
}