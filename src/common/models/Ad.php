<?php

namespace common\models;

use common\components\ActiveRecord;
use himiklab\sitemap\behaviors\SitemapBehavior;
use v0lume\yii2\metaTags\MetaTagBehavior;
use yii\behaviors\SluggableBehavior;
use yii\helpers\Url;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use zxbodya\yii2\galleryManager\GalleryBehavior;

/**
 * This is the model class for table "{{%ads}}".
 *
 * @property integer $id
 * @property string  $name
 * @property integer $user_id
 * @property integer $views
 * @property integer $ended_at
 * @property integer $created_at
 * @property string  $description
 * @property integer $updated_at
 * @property integer $status
 * @property integer $life_time
 * @property string  $url_site
 * @property string  $url
 * @property integer $section_id
 * @property integer $type_id
 * @property mixed   section
 * @property mixed   type
 * @property mixed   price
 * @property mixed   user
 */
class Ad extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%ads}}';
    }

    const STATUS_DELETED = -1;
    const STATUS_NOT_PUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_MODERATION = 2;
    const STATUS_TIME_END = 3;

    const SERVICE_GOODS_TYPE = 1;
    const SERVICE_TYPE = 2;
    const GOODS_SECTION = 1;
    const SERVICE_SECTION = 2;

    public static $statuses = [
        self::STATUS_DELETED => 'Удалено',
        self::STATUS_NOT_PUBLISHED => 'Редактирование',
        self::STATUS_PUBLISHED => 'Опубликовано',
        self::STATUS_MODERATION => 'Модерация',
        self::STATUS_TIME_END => 'Время истекло',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [[
                'id',
                'section_id',
                'type_id',
                'created_at',
                'ended_at',
                'life_time',
                'status',
                'user_id',
                'views',
                'updated_at',
            ], 'integer'],
            ['price', 'double'],
            [['name', 'url_site', 'url'], 'string'],
            [['description'], 'string', 'max' => 1000],
            [['name'], 'string', 'max' => 250],
            [['url_site'], 'url', 'message' => 'Сайт необходимо указать как https://site.com или http://site.com'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Раздел',
            'type_id' => 'Тип',
            'name' => 'Название',
            'price' => 'Стоимость от',
            'description' => 'Описание',
            'life_time' => 'Время поднятия',
            'url' => 'URL',
            'created_at' => 'Время создания',
            'user_id' => 'Пользователь',
            'ended_at' => 'Время окончания',
            'status' => 'Статус объявления',
            'url_site' => 'Ссылка на товар',
            'keyNames' => 'Ключи',
            'views' => 'Просмотры',
            'categories' => 'Категории',
            'subcategories' => 'Подкатегории',
        ];
    }

    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'url',
                'uniqueSlugGenerator' => '-',
            ],
            'TimeStamp' => ['class' => TimestampBehavior::class],
            'galleryBehavior' => [
                'class' => GalleryBehavior::class,
                'type' => 'ad',
                'extension' => 'jpg',
                // image dimmentions for preview in widget
                'previewWidth' => 300,
                'previewHeight' => 200,
                // path to location where to save images
                'directory' => Yii::getAlias('@frontend') . '/uploads/ads',
                'url' => Yii::$app->params['domainFrontend'] . '/uploads/ads',
                // additional image versions
                'versions' => [
                    'i400x200' => function ($img) {
                        $width = 400;
                        $height = 200;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                ],

            ],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    /** @var $model self */
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
                        'priority' => 0.8,
                    ];
                },
            ],
        ];
    }

    public function getSections($section_id = 0)
    {
        $adName = [
            self::GOODS_SECTION => 'Товары',
            self::SERVICE_SECTION => 'Услуги',
        ];
        if (!$section_id) {
            return $adName;
        } else {
            return $adName[$section_id];
        }
    }

    public function getTypes($type_id = 0)
    {
        $adName = [
            self::SERVICE_GOODS_TYPE => 'Услуги общие',
            self::SERVICE_TYPE => 'Услуги к товарам',
        ];
        if (!$type_id) {
            return $adName;
        } else {
            return $adName[$type_id];
        }
    }

    /**
     * @return mixed
     */
    public function status($status)
    {
        $result = '';
        switch ($status) {
            case Ad::STATUS_DELETED:
                return $result = '<button data-toggle="dropdown" class="btn btn-danger dropdown-toggle">
                             <i title="Удалено у пользователя"  class="glyphicon glyphicon-flash"></i> <b class="caret"></b>
                         </button>';
            case Ad::STATUS_NOT_PUBLISHED:
                return $result = '<button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                <i class="glyphicon glyphicon-pencil" title="Редактирование"></i> <b class="caret"></b>
                             </button>';
            case Ad::STATUS_PUBLISHED:
                return $result = '<button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
                                <i class="glyphicon glyphicon-ok" title="Опубликовано"></i> <b class="caret"></b>
                             </button>';
            case Ad::STATUS_MODERATION:
                return $result = '<button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">
                             <i title="Модерация"  class="glyphicon glyphicon-eye-open"></i> <b class="caret"></b>
                         </button>';
            case Ad::STATUS_TIME_END:
                return $result = '<button data-toggle="dropdown" class="btn btn-info dropdown-toggle">
                              <i title="Время закончилось"  class="glyphicon glyphicon-time"></i> <b class="caret"></b>
                         </button>';
        }

        return $result;
    }

    public function getMeta()
    {
        $meta = $this->getBehavior('MetaTag');

        return !empty($meta->title && $meta->keywords && $meta->description);
    }

    public function getUrl()
    {
        return '/ads/' . ($this->url ?? $this->id);
    }

    public function getSection()
    {
        return $this->hasOne(AdSection::class, ['id' => 'section_id']);
    }

    public function getType()
    {
        return $this->hasOne(AdType::class, ['id' => 'type_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getSectionUrl()
    {
        $dirs = $this->section;
        $out = '';
        foreach ($dirs as $dir) {
            $out = $dir->url;
        }

        return $out;
    }

    public function getPreview($version = 'preview')
    {
        $result = '';

        if ($this->getBehavior('galleryBehavior')->getImages()) {
            $images = $this->getBehavior('galleryBehavior')->getImages([0]);

            foreach ($images as $image) {
                $result = $image->getUrl($version);
            }
        }

        return $result;
    }

    public function getGalleryCover($version = 'i400x200')
    {
        $result = '';

        if ($this->getBehavior('galleryBehavior')->getImages()) {
            $images = $this->getBehavior('galleryBehavior')->getImages([0]);

            foreach ($images as $image) {
                $result = $image->getUrl($version);
            }
        }

        return $result;
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        if ($this->created_at && strpos($this->created_at, ' - ')) {
            $time = explode(' - ', $this->created_at);

            $query->andFilterWhere(['between', 'created_at', strtotime($time[0]), strtotime($time[1])]);
        }

        if ($this->ended_at && strpos($this->ended_at, ' - ')) {
            $time = explode(' - ', $this->ended_at);

            $query->andFilterWhere(['between', 'ended_at', strtotime($time[0]), strtotime($time[1])]);
        }

        $query->filterWhere(['id' => $this->id]);
        $query->filterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['views' => $this->views]);
        $query->andFilterWhere(['status' => $this->status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(self::class . '_pageSize') ? Yii::$app->cache->get(self::class . '_pageSize') : true),
            ],
        ]);

        return $dataProvider;
    }
}
