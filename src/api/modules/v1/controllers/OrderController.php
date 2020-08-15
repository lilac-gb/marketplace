<?php

namespace api\modules\v1\controllers;

use api\components\ActiveController;
use common\models\Ad;
use common\models\Order;
use Yii;


/**
 * @property mixed settings
 */
class OrderController extends ActiveController
{
    public $modelClass = 'api\models\Order';

    public function actions()
    {
        $actions = parent::actions();

        $actions['delete'] = array_merge($actions['delete'], [
            'permanent' => false,
            'attribute' => 'status',
            'value' => Order::STATUS_DELETED,
        ]);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator']['optional'] = ['create', 'ads', 'delete'];

        return $behaviors;
    }

    public function actionMy()
    {
        /* @var $modelClass \api\models\Order */
        $modelClass = new $this->modelClass();
        $modelClass->my = true;

        return $modelClass->search(Yii::$app->request->get());
    }

    public function actionDelete($id)
    {
        $model = Order::findOne($id);
        if (!empty($model)) {

            if ($model->user_id !== Yii::$app->user->id) {
                return json_encode([
                    'status' => 404,
                    'message' => 'Order not found',
                ]);
            }

            $model->updateAttributes(['status' => Order::STATUS_DELETED]);

            return json_encode([
                'status' => 200,
                'success' => 'ok',
            ]);
        } else {
            return json_encode([
                'status' => 404,
                'message' => 'Order not found',
            ]);
        }
    }

    /**
     * @name actionAds
     * @description return to cart goods and services that was added before checkout
     * @returns object
     */
    public function actionAds()
    {
        $data = Yii::$app->request->post('object');
        $services = [];
        $goods = [];
        if ($data) {
            foreach ($data as $item) {
                $item = (object)$item;
                $ad = Ad::findOne($item->id);
                /** @var $ad Ad */
                if ($ad->section_id == Ad::GOODS_SECTION) {
                    $goods[] = [
                        'id' => $ad->id,
                        'name' => $ad->name,
                        'price' => $ad->price,
                        'count' => $item->count,
                    ];
                }
                if ($ad->section_id == Ad::SERVICE_SECTION) {
                    $services[] = [
                        'id' => $ad->id,
                        'name' => $ad->name,
                        'price' => $ad->price ?: 0,
                        'count' => $item->count,
                    ];
                }
            }

            return json_encode((object)[
                'services' => $services,
                'goods' => $goods,
            ]);
        }
    }
}
