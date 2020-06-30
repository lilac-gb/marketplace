<?php

namespace api\controllers;

class UserController extends \api\modules\v1\controllers\UserController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['user'],
        'only' => ['options', 'index', 'view', 'me', 'login'],
        'extraPatterns' => [
            'GET me' => 'me',
            'GET test' => 'test',
            'POST login' => 'login',
        ],
    ];
}
