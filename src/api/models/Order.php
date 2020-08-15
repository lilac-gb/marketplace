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
            'created_at',
            'items' => function () {
                /** @var $model self */
                $result = [];
                $items = OrderItem::findAll(['order_id' => $this->id]);
                foreach ($items as $item) {
                    /** @var $item OrderItem */
                    $ad = \common\models\Ad::findOne(['id' => $item->model_id]);
                    /** @var $ad Ad */
                    $image = $ad->getPreview() ?? '';
                    $result[] = [
                        'id' => $item->id,
                        'ad_name' => $ad->name,
                        'ad_id' => $ad->id,
                        'ad_type' => $ad->type_id,
                        'ad_url' => $ad->url,
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
            $this->created_at = time();
            $this->updated_at = time();
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
                    'message' => 'Заказ успешно сохранен в Вашем личном кабинете',
                ];

            } else if (isset($email)) {
                $model = new User();
                $model->email = $email;
                $model->first_name = $name ?: 'Пользователь';
                $password = Yii::$app->security->generateRandomString(8);
                $model->password_hash = Yii::$app->security->generatePasswordHash($password);
                $model->auth_key = $model->generateAuthKey();
                $model->confirmation_secret = Yii::$app->security->generateRandomString(8);
                $model->status = User::STATUS_INACTIVE;
                $model->role = User::ROLE_USER;
                if ($model->save(false)) {
                    UserService::sendActivationEmail($model);

                    $this->user_id = $model->id;
                    $this->email = $model->email;

                    return [
                        'statusCode' => 200,
                        'message' => 'Заказ сохранен в личном кабинете. На ваш почтовый ящик, выслано письмо с данными для активации',
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
                        $orderItem->created_at = time();
                        $orderItem->updated_at = time();
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

        if ($this->created_at && strpos($this->created_at, ' - ')) {
            $time = explode(' - ', $this->created_at);

            $query->andFilterWhere(['between', 'created_at', strtotime($time[0]), strtotime($time[1])]);
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
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        return $dataProvider;
    }

}