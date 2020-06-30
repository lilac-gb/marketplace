<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'backend',
    'name' => 'MarketPlace',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'main/main/index',
    'homeUrl' => '/',
    'bootstrap' => ['log'],
    'modules' => array_merge(require(__DIR__ . '/modules.php'), [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ]),
    'components' => [
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'main/main/error',
        ],
        'request' => [
            'class' => 'common\components\Request',
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/',
            'noCsrfRoutes' => [
                'main/main/upload'
            ]
        ],
        'urlManager' => require(__DIR__ . '/url-manager.php'),
        'user' => [
            'class' => 'common\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['main/main/login'],
        ],
        'view' => [
            'params' => [
                'breadcrumbs' => [],
            ]
        ],
    ],
    'params' => $params,
];
