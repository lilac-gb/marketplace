<?php

use yii\filters\ContentNegotiator;
use yii\web\Response;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
                'application/xml' => Response::FORMAT_XML,
            ],
            'languages' => ['ru', 'en'],
        ],
    ],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                /** @var Response $response */
                $response = $event->sender;
                $tokenData = [];

                $statusCode = $response->statusCode;
                $success = $response->isSuccessful;

                //TODO: wtf? why 401 is changed to 200 only in this place
                if ($statusCode === 401) {
                    $response->statusCode = 200;
                    $success = false;

                    //TODO: maybe move this hack with cors header to controller method
                    if (isset($_SERVER['HTTP_ORIGIN'])) {
                        /** @var \yii\web\HeaderCollection $responseHeaders */
                        $responseHeaders = $response->getHeaders();
                        $responseHeaders->set('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN']);
                    }
                }

                if (Yii::$app->user->token) {
                    $tokenData = Yii::$app->user->getTokenResponse();
                }

                if (Yii::$app->request->getPathInfo() === 'sitemap.xml') {
                    $response->format = 'sitemap';
                } elseif (
                    Yii::$app->request->getPathInfo() === 'news.rss'
                    || Yii::$app->request->getPathInfo() === 'ya-news.rss'
                    || Yii::$app->request->getPathInfo() === 'ya-turbo-news.rss'
                ) {
                    $response->data = null;
                } else {
                    $response->data = array_merge([
                        'success' => $success,
                        'statusCode' => $response->statusCode,
                        'data' => $response->data,
                    ], $tokenData);

                    //TODO this gives to API any time 200
                    // $response->statusCode = 200;
                }
            },
        ],
        'user' => [
            'class' => 'api\components\User',
            'identityClass' => 'api\models\User',
            'enableSession' => false,
// 'identityCookie' => ['name' => '_identity-api', 'httpsOnly' => true],
// 'enableAutoLogin' => false,
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
        'urlManager' => require(__DIR__ . '/url-manager.php'),
    ],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'params' => $params,
];
