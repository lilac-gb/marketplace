<?php

namespace backend\modules\orders\controllers;

use backend\components\ActiveController;
use common\models\Order;
use common\models\OrderItem;
use Yii;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

class OrderController extends ActiveController
{
    public $modelClass = 'common\models\Order';
    public $modalSize = Modal::SIZE_LARGE;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'set', 'delete', 'update'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function actionUpdate($id = null)
    {
        $this->layout = false;

        $modelItems = [];
        $model = Order::findOne($id);

        if (!empty($model)) {
            $modelItems = OrderItem::findAll(['order_id' => $model->id]);
        }

        if (!isset($model)) {
            $model = new $this->modelClass();
        }

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                return ActiveForm::validate($model);
            }

            if ($model->validate()) {
                $model->save();

                return true;
            }

            return $model->errors;
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'items' => $modelItems,
        ]);
    }
}
