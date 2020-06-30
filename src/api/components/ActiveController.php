<?php

namespace api\components;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\httpclient\XmlFormatter;
use yii\web\Response;

class ActiveController extends \yii\rest\ActiveController
{
//    public static $urlRule = [
//        'class' => 'yii\rest\UrlRule',
//        'pluralize' => false,
//        'controller' => ['artist'],
//    ];

    public $serializer = 'api\components\Serializer';

    public function actions()
    {
        return array_merge(parent::actions(), [
            'delete' => [
                'class' => 'api\components\actions\DeleteAction',
                'modelClass' => $this->modelClass,
                'controller' => $this,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'api\components\actions\ViewAction',
                'modelClass' => $this->modelClass,
                'controller' => $this,
                'afterFind' => [$this, 'afterFindView'],
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'index' => [
                'class' => 'api\components\actions\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => 'api\components\actions\CreateAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['options'],
            'authMethods' => [
                [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'token',
                ],
                HttpBearerAuth::class,
            ],
        ];

        return $behaviors;
    }

    public function afterFindView(&$model) {}

    public function asRawXml($content)
    {
        $response = Yii::$app->response;
        $formatter = new XmlFormatter();

        $response->format = Response::FORMAT_RAW;
        $response->content = $content;

        $contentType = $formatter->contentType;
        $charset = $formatter->encoding === null ? Yii::$app->charset : $formatter->encoding;
        if (stripos($contentType, 'charset') === false) {
            $contentType .= '; charset=' . $charset;
        }

        $response->getHeaders()->set('Content-Type', $contentType);

        return;
    }
}
