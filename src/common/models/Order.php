<?php

namespace common\models;

use common\components\ActiveRecord;
use common\services\UserService;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $address
 * @property string  $phone
 * @property string  $ip
 * @property string  $email
 * @property integer $name
 * @property string  $text
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_PROCESS = 0;
    const STATUS_DONE = 1;
    const STATUS_SHIPPING = 2;

    public static $statuses = [
        self::STATUS_DELETED => 'Deleted',
        self::STATUS_DONE => 'Done',
        self::STATUS_PROCESS => 'Process',
        self::STATUS_SHIPPING => 'Shipping',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['address', 'phone', 'email', 'name', 'text'], 'string'],
            [['created_at', 'updated_at'], 'save'],
            ['email', 'email'],
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
            'ip' => 'IP',
            'user_id' => 'Пользователь',
            'address' => 'Адрес',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'name' => 'ФИО',
            'text' => 'Текст',
            'status' => 'Статус',
            'updated_at' => 'Обновлено',
            'created_at' => 'Создано',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['model_id' => 'id']);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['like', 'phone', $this->phone]);
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
