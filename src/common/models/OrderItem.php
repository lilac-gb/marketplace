<?php

namespace common\models;

use common\components\ActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "{{%orders_items}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $model_id
 * @property integer $count
 * @property integer $price
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 * @property mixed   items
 */
class OrderItem extends ActiveRecord
{
    const STATUS_PROCESS = 0;
    const STATUS_DONE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'model_id', 'count', 'status', 'updated_at', 'created_at'], 'integer'],
            ['price', 'double'],
        ];
    }

    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'model_id' => 'Модель',
            'count' => 'Кол-во',
            'price' => 'Стоимость',
            'status' => 'Статус',
            'updated_at' => 'Обновлено',
            'created_at' => 'Создано',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function search($params = null)
    {
        $query = self::find();

        $query->with(['']);

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['price' => $this->price]);
        $query->andFilterWhere(['count' => $this->count]);
        $query->andFilterWhere(['order_id' => $this->order_id]);
        $query->andFilterWhere(['model_id' => $this->model_id]);
        $query->andFilterWhere(['status' => $this->status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize',
                    Yii::$app->cache->get(self::class . '_pageSize') ?
                        Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ],
        ]);

        return $dataProvider;
    }
}
