<?php

namespace api\models;

use common\models\OrderItem;
use common\services\OrderService;
use common\services\UserService;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * @property mixed items
 */
class Order extends \common\models\Order
{
    public $my = false;

    public function fields()
    {
        return [
            'id',
            'address',
            'phone',
            'email',
            'name',
            'text',
            'status',
            'time_create',
            'items' => function () {
                /** @var $model self */
                $result = [];
                $items = OrderItem::findAll(['order_id' => $this->id]);
                foreach ($items as $item) {
                    /** @var $item OrderItem */
                    $ad = Ad::findOne(['id' => $item->model_id]);
                    $image = $ad->getAttachmentList()[0];
                    $result[] = [
                        'id' => $item->id,
                        'ad_name' => $ad->name,
                        'ad_id' => $ad->id,
                        'ad_type' => $ad->type_id,
                        'ad_url' => $ad->getUrl(),
                        'ad_img' => $image,
                        'count' => $item->count,
                        'price' => $item->price,
                        'status' => $item->status,
                    ];
                }

                return $result;
            },
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->status = Order::STATUS_PROCESS;
            $this->ip = Yii::$app->request->userIP;
            $this->time_create = time();
            $this->time_update = time();
            $request = Yii::$app->request;
            $email = trim($request->post('email'));
            $name = trim($request->post('name'));

            /** @var User $existingUser */
            $existingUser = Yii::$app->user->getIdentity() ?: User::findOne(['email' => $email]);

            if (isset($existingUser) && $existingUser->id) {
                $this->user_id = $existingUser->id;
                $this->email = $existingUser->email;

                return [
                    'statusCode' => 200,
                    'message' => Yii::t('order/index', 'message_email'),
                ];

            } else if (isset($email)) {
                $model = new User();
                $model->email = $email;
                $model->name = $name ?: 'Пользователь';
                $model->lang = Yii::$app->language;
                $password = Yii::$app->security->generateRandomString(8);
                $model->password_hash = Yii::$app->security->generatePasswordHash($password);
                $model->auth_key = $model->generateAuthKey();
                $model->confirmation_secret = Yii::$app->security->generateRandomString(8);
                $model->status = User::STATUS_INACTIVE;
                $model->role = User::ROLE_USER;
                $model->type = User::TYPE_USER;
                if ($model->save(false)) {
                    UserService::sendActivationEmail($model);

                    $this->user_id = $model->id;
                    $this->email = $model->email;

                    return [
                        'statusCode' => 200,
                        'message' => Yii::t('order/index', 'message_activate_email'),
                    ];
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $request = Yii::$app->request;
        $items = $request->post('items');

        if (!empty($items)) {
            foreach ($items as $item) {
                $ad = (object)$item;
                if ($ad->id) {
                    $exist = Ad::findOne($ad->id);
                    if ($exist) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $this->id;
                        $orderItem->model_id = $ad->id;
                        $orderItem->count = $ad->count;
                        $orderItem->price = $exist->price == $ad->price ? $exist->price : $ad->price;
                        $orderItem->status = $ad->price ? OrderItem::STATUS_DONE : OrderItem::STATUS_PROCESS;
                        $orderItem->time_create = time();
                        $orderItem->time_update = time();
                        $orderItem->save();
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);

        OrderService::sendNotificationEmail($this);
        OrderService::sendUserNotificationEmail($this);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        if ($this->my) {
            $this->user_id = Yii::$app->user->id;
        }

        if ($this->time_create && strpos($this->time_create, ' - ')) {
            $time = explode(' - ', $this->time_create);

            $query->andFilterWhere(['between', 'time_create', strtotime($time[0]), strtotime($time[1])]);
        }

        $query->with(['user']);

        //adjust the query by adding the filters
        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['in', 'user_id', $this->user_id]);
        $query->andFilterWhere(['in', 'status', $this->status]);
        $query->andFilterWhere(['<>', 'status', self::STATUS_DELETED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ],
            ],
        ]);

        return $dataProvider;
    }

}