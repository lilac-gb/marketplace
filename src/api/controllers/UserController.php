<?php

namespace api\controllers;

class UserController extends \api\modules\v1\controllers\UserController {
    public static $urlRule = [
        'class' => 'api\components\UrlRule',
        'pluralize' => false,
        'controller' => ['user', 'v1/user'],
        'extraPatterns' => [
            'GET me' => 'me',
            'OPTIONS' => 'options',
            'OPTIONS {model}/{model_id}' => 'options',
            'OPTIONS {nickname}' => 'options',
            'OPTIONS {id}/confirm-email' => 'options',
            'OPTIONS {id}/activation-email' => 'options',
            'OPTIONS restore-password' => 'options',
            'OPTIONS login' => 'options',
            'OPTIONS signup' => 'options',
            'OPTIONS check-activate-key' => 'options',
            'OPTIONS ticket' => 'options',
            'OPTIONS save' => 'options',
            'OPTIONS change-password' => 'options',
            'OPTIONS imgAttachApi/{id}' => 'options',
            'OPTIONS info' => 'options',

            'GET {model}/{model_id}' => 'index',
            'POST {id}/confirm-email' => 'confirm-email',
            'POST {id}/activation-email' => 'activation-email',
            'POST restore-password' => 'restore',
            'GET,HEAD {nickname}' => 'view',
            'DELETE push-token' => 'push-token-delete',
            'POST login' => 'login',
            'POST signup' => 'signup',
            'POST check-activate-key' => 'check-activate-key',
            'POST save' => 'save',
            'POST change-password' => 'change-password',
            'POST imgAttachApi/{id}' => 'imgAttachApi',
            'GET info' => 'info',
        ],
        'tokens' => [
            '{id}' => '<id:\\d[\\d,]*>',
            '{model_id}' => '<model_id:\\d[\\d,]*>',
            '{model}' => '<model:\\w+>',
            '{nickname}' => '<id:[A-za-z0-9.-_-]+>',
        ],
    ];
}
