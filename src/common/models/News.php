<?php

namespace common\models;

use common\components\ActiveRecord;
use himiklab\sitemap\behaviors\SitemapBehavior;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $type
 * @property string $name
 * @property string $anons
 * @property string $description
 * @property integer $views
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $status
 * @property string url
 * @property mixed user
 */
class News extends ActiveRecord
{
    const STATUS_DELETED = -1;
    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%news}}';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'required'],
            [['name', 'description', 'anons'], 'string'],
            ['url', 'unique', 'message' => 'Такой URL уже есть'],
            [[
                'views', 'status',
                'updated_at',
                'user_id',
            ], 'integer'],
            [['name', 'url'], 'string', 'max' => 255],
            [['views'], 'default', 'value' => 0],
            [['published_at', 'created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'url' => 'Url',
            'meta' => 'МЕТА',
            'name' => 'Название',
            'anons' => 'Анонс',
            'description' => 'Полное описание',
            'created_at' => 'Время создания',
            'updated_at' => 'Время отправки',
            'published_at' => 'Время авто публикации',
            'views' => 'Просмотры',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => ['class' => MetaTagBehavior::class],
            'TimeStamp' => ['class' => TimestampBehavior::class],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    /** @var $model self*/
                    if (date('Y', $model->created_at) !== date('Y')) {
                        return false;
                    }

                    if ($model->status !== self::STATUS_PUBLISHED) {
                        return false;
                    }

                    return [
                        'loc' => Yii::$app->params['domainFrontend'] . Url::to($model->getUrl()),
                        'lastmod' => $model->created_at,
                        'changefreq' => SitemapBehavior::CHANGEFREQ_HOURLY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    public function getUrl()
    {
        $headUrl = '/news/';
        $backUrl =
            date('Y', $this->created_at) . '/' .
            date('m', $this->created_at) . '/' .
            date('d', $this->created_at) . '/' .
            ($this->url ? $this->url : $this->id);
        return $headUrl . $backUrl;
    }

    public function compareDate($year, $month, $day): bool
    {
        if (date('Y', $this->created_at) !== $year)
            return false;

        if (date('m', $this->created_at) !== $month)
            return false;

        if (date('d', $this->created_at) !== $day)
            return false;

        return true;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        $query->andFilterWhere(['like', 'LOWER(name)', $this->name]);
        $query->andFilterWhere(['views' => $this->views]);

        if ($this->created_at && strpos($this->created_at, ' - ')) {
            $time = explode(' - ', $this->created_at);

            $query->andFilterWhere(['between', 'created_at', strtotime($time[0]), strtotime($time[1])]);
        }

        if ($this->published_at && strpos($this->published_at, ' - ')) {
            $time = explode(' - ', $this->published_at);

            $query->andFilterWhere(['between', 'published_at', strtotime($time[0]), strtotime($time[1])]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        return $dataProvider;
    }

    public function status($status)
    {
        $result = '';
        switch ($status) {
            case self::STATUS_DELETED:
                return $result = '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                <i class="glyphicon glyphicon-remove" title="Удалено"></i> <b class="caret"></b>
                             </button>';
            case self::STATUS_NOT_PUBLISHED:
                return $result = '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                <i class="glyphicon glyphicon-pencil" title="Не опубликовано"></i> <b class="caret"></b>
                             </button>';
            case self::STATUS_PUBLISHED:
                return $result = '<button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
                                <i class="glyphicon glyphicon-ok" title="Опубликовано"></i> <b class="caret"></b>
                             </button>';
            case self::STATUS_MODERATION:
                return $result = '<button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">
                             <i title="Модерация"  class="glyphicon glyphicon-eye-open"></i> <b class="caret"></b>
                         </button>';
        }
        return $result;
    }
}
