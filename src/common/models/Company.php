<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use common\components\ActiveRecord;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use Yii;

/**
 *
 * @property integer $id
 * @property integer $owner_id
 * @property string $vat
 * @property string $id_number
 * @property string $url
 * @property string $site
 * @property string $working_days
 * @property integer $time_from
 * @property integer $time_to
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $views
 * @property string $description
 * @property string $name
 * @property mixed $user
 */
class Company extends ActiveRecord
{
    const MONDAY = 0;
    const TUESDAY = 1;
    const WEDNESDAY = 2;
    const THURSDAY = 3;
    const FRIDAY = 4;
    const SATURDAY = 5;
    const SUNDAY = 6;

    static $weekDays = [
        self::MONDAY => 'ПН',
        self::TUESDAY => 'ВТ',
        self::WEDNESDAY => 'СР',
        self::THURSDAY => 'ЧТ',
        self::FRIDAY => 'ПТ',
        self::SATURDAY => 'СБ',
        self::SUNDAY => 'ВС',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%companies}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at', 'views', 'owner_id'], 'integer'],
            [['url'], 'string', 'max' => 250],
            [[ 'id_number', 'vat', 'site'], 'string'],
            [[
                'name', 'description', 'date_from', 'phone', 'email',
                'time_from', 'time_to', 'working_days',
            ], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'url' => 'URL',
            'owner_id' => 'Пользователь',
            'vat' => 'ИНН',
            'id_number' => 'ОГРН',
            'name' => 'Название компании',
            'description' => 'Описание компании',
            'site' => 'Сайт компании',
            'time_from' => 'открыто с',
            'time_to' => 'открыто до',
            'working_days' => 'Рабочие дни',
            'updated_at' => 'Время обновления',
            'created_at' => 'Время создания',
            'views' => 'Просмотры',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
            ],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    /** @var $model self */
                    return [
                        'loc' => Url::to($model->getUrl(), true),
                        'lastmod' => $model->updated_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (is_array($this->working_days)) {
            $this->working_days = implode(',', $this->working_days);
        }

        return parent::beforeSave($insert);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

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

    public function getUrl()
    {
        return '/companies/' . ($this->url ?: $this->id);
    }
}
