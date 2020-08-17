<?php

namespace common\models;

use dosamigos\translateable\TranslateableBehavior;
use common\components\ActiveRecord;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property integer $id
 * @property string  $section_id
 * @property string  $code
 * @property string  $name
 * @property string  $entity
 * @property string  $input_type
 * @property integer $status
 * @property mixed   entity
 */
class Setting extends ActiveRecord
{
    const TYPE_EMAIL = 1;
    const TYPE_STATISTIC = 2;
    const TYPE_NAMES = 3;
    const TYPE_KEYS = 4;

    const STATUS_PUBLISHED = 1;

    static $settingsTypes = [
        self::TYPE_EMAIL => 'Email уведомлений',
        self::TYPE_STATISTIC => 'Счетчики сайта',
        self::TYPE_NAMES => 'Названия в элементах',
        self::TYPE_KEYS => 'Ключи'
    ];

    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'code', 'name', 'input_type'], 'required'],
            [['input_type', 'entity'], 'string'],
            [['status', 'section_id'], 'integer'],
            [['code'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 100],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'section_id' => 'Раздел',
            'code' => 'Системное название',
            'name' => 'Описание',
            'entity' => 'Значение',
            'input_type' => 'Тип поля',
            'status' => 'Статус',
        ];
    }

    public function getElement()
    {
        return $this->input_type;
    }

    /**
     * @name getValue
     * @description get entity of setting param
     * @param $code string
     * @return string
    */
    public static function getValue(string $code): string
    {
        if($code){
            $model = self::findOne(['code' => trim($code)]);
            /** @var $model self */
            if(!$model){
                return "Нет такого кода ($code)";
            }

            return $model->entity;
        }
        return 'Нет обязательного параметра CODE';
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere(['section_id' => $this->section_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize',
                    Yii::$app->cache->get(self::class . '_pageSize') ?
                        Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }
}
