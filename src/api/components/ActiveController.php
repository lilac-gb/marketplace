<?php

namespace api\components;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Request-Method: POST, GET, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 3600');

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;
use \yii\rest\ActiveController as BaseActiveController;
use yii\web\XmlResponseFormatter;

class ActiveController extends BaseActiveController
{
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

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        $behaviors['authenticator'] = $auth;

        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['options'],
            'authMethods' => [
                HttpBearerAuth::class,
                [
                    'class' => QueryParamAuth::class,
                    'tokenParam' => 'token',
                ],
            ],
        ];

        return $behaviors;
    }

    public function afterFindView(&$model) {}

    public function asRawXml($content)
    {
        $response = Yii::$app->response;
        $formatter = new XmlResponseFormatter();

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
