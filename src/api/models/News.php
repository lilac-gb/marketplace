<?php

namespace api\models;

use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class News extends \common\models\News
{
    public $my = false;

    public function fields()
    {
        return [
            'id',
            'url',
            'name',
            'status',
            'user' => function () {
                if (empty($this->user)) {
                    return null;
                }

                return [
                    'name' => ($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''),
                    'url' => $this->user->username,
                    'status' => $this->user->status,
                ];
            },
            'coverImages' => function ($model) {
                /** @var $model News */
                return [
                    'preview' => $model->getPreview(),
                    // 'socImage' => $model->getSocialImage(),
                    'i600x250' => $model->getNewsCover(),
                    'i1200x500' => $model->getGalleryCover(),
                ];
            },
            'description',
            'views',
            'created_at',
        ];
    }

    public function extraFields()
    {
        return [
            '_metaTags',
            'gallery' => function () {
                $images = $this->getBehavior('galleryBehavior')->getImages();

                return ArrayHelper::toArray($images, [
                    'zxbodya\yii2\galleryManager\GalleryImage' => [
                        'id',
                        'name',
                        'description',
                        'rank',
                        'original' => function ($model) {
                            return $model->getUrl('original');
                        },
                        'i600x250' => function ($model) {
                            return $model->getUrl('i600x250');
                        },
                        'i1200x500' => function ($model) {
                            return $model->getUrl('i1200x500');
                        },
                        'preview' => function ($model) {
                            return $model->getUrl('preview');
                        },
                    ],
                ]);
            },
        ];
    }

    public static function findOne($id)
    {
        if (!is_numeric($id) && !is_array($id)) {
            $condition = ['url' => $id];
        }

        $condition['status'] = News::STATUS_PUBLISHED;

        $dependency = new DbDependency(['sql' => 'SELECT max(created_at) FROM news']);

        return parent::getDb()->cache(function () use ($condition) {
            return parent::findOne($condition);
        }, 0, $dependency);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        if ($this->my) {
            $query->andWhere(['user_id' => Yii::$app->user->id])
                ->andWhere(['<>', 'status', self::STATUS_DELETED]);
        } else {
            $query->andWhere(['=', 'status', self::STATUS_PUBLISHED]);
        }

        $query->andFilterWhere(['like', 'LOWER(name)', $this->name]);

        if (isset($params['user_id'])) {
            $query->andFilterWhere(['news.user_id' => $params['user_id']]);
        }

        if (isset($params['sortBy']) && isset($params['sortDesc'])) {
            $sortBy = $params['sortBy'];
            $sortDesc = $params['sortDesc'];
            $query->orderBy("{$sortBy} {$sortDesc}");
        }

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
