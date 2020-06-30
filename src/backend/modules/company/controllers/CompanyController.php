<?php

namespace backend\modules\company\controllers;

use backend\components\ActiveController;
use common\models\Company;
use yii\bootstrap\Modal;
use yii\web\Response;
use Yii;

/**
 * PageController implements the CRUD actions for Company model.
 */
class CompanyController extends ActiveController
{
    public $modelClass = 'common\models\Company';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'set', 'delete', 'update', 'list-companies'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function actionListCompanies($query)
    {
        $query = urldecode($query);

        $models = Company::find()->where(['like', 'name', $query])->all();
        $items = [];

        foreach ($models as $model) {
            /** @var $model Company */
            $items[] = [
                'id' => $model->id,
                'name' => $model->name
            ];
        }
        // We know we can use ContentNegotiator filter
        // this way is easier to show you here :)
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $items;
    }
}
