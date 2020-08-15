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

        if (isset($params['search'])) {
            $terms = explode(" ", $params['search']);

            if (count($terms) > 1) {
                $address = ['and'];
                $id = ['and'];

                foreach ($terms as $key => $value) {
                    $address[] = ['like', 'LOWER(address)', $value];
                    $id[] = ['like', 'LOWER(id)', $value];
                }
            } else {
                $address = ['like', 'LOWER(address)', $params['search']];
                $id = ['like', 'LOWER(id)', $params['search']];
            }

            $query->where($address)->orWhere($id);
        }

        if (isset($params['status'])) {
            $query->andFilterWhere(['status' => $params['status']]);
        }

        if (isset($params['sortBy']) && isset($params['sortDesc'])) {
            $sortBy = $params['sortBy'];
            $sortDesc = $params['sortDesc'];
            $query->orderBy("{$sortBy} {$sortDesc}");
        }

        $query->groupBy('id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize',
                    Yii::$app->cache->get(self::class . '_pageSize') ?
                        Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ],
        ]);

        return $dataProvider;
    }

}