<?php

namespace backend\modules\ad\controllers;

use backend\components\ActiveController;
use common\models\AdType;
use Yii;
use yii\bootstrap\Modal;
use yii\web\Response;

/**
 * TypesController implements the CRUD actions for News model.
 */
class TypeController extends ActiveController
{
    public $modelClass = 'common\models\AdType';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'delete', 'update', 'set', 'list'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function actionList($query)
    {
        $query = urldecode($query);

        $models = AdType::find()->where(['like', 'name', $query])->all();
        $items = [];

        foreach ($models as $model) {
            $items[] = ['id' => $model->id, 'name' => $model->name];
        }
        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }
}
