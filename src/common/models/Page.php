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
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $url
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $status
 * @property string $description
 * @property string $name
 */
class Page extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_PUBLISHED = 1;
    const STATUS_NOT_PUBLISHED = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'url', 'status'], 'required'],
            [['description'], 'string'],
            [['updated_at', 'created_at', 'status'], 'integer'],
            [['url', 'name'], 'string', 'max' => 250]
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
            'name' => 'Название',
            'description' => 'Контент',
            'updated_at' => 'Обновлено',
            'created_at' => 'Создано',
            'status' => 'Опубликовано',

        ];
    }


    public function behaviors()
    {
        return [
            'MetaTag' => ['class' => MetaTagBehavior::class],
            ['class' => TimestampBehavior::class],
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

    public function search($params=null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['status' => $this->status]);

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


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if ($this->url !== '/')
            {
                $this->url = trim($this->url, '/');
            }

            return true;
        }
        else
            return false;
    }

    public function getUrl()
    {
        if ($this->url !== '/')
            return '/'.$this->url;

        return $this->url;
    }
}
