<?php

namespace api\models;

use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;

/**
 * @property int   status
 * @property mixed price
 * @property mixed sectionNames
 * @property mixed user
 * @property mixed details
 * @property mixed attachmentList
 * @property mixed typeAd
 * @property mixed sectionAd
 */
class Ad extends \common\models\Ad
{
    public $search;
    public $my = false;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['search', 'string'],
        ]);
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'author' => function () {
                return [
                    'id' => $this->user ? $this->user->id : '',
                    'name' => $this->user ? $this->user->fullName : '',
                    'avatar' => $this->user ? $this->user->getAvatar() : '',
                    'userUrl' => $this->user ? ($this->user->username
                        ? '/@' . $this->user->username
                        : '/users/' . $this->user->id) : '',
                ];
            },
            'price',
            'description',
            'coverImages' => function ($model) {
                /** @var $model Ad */
                return [
                    'preview' => $model->getPreview(),
                    'i1200x500' => $model->getGalleryCover(),
                ];
            },
            'life_time',
            'created_at',
            'ended_at',
            'preview',
            'status',
            'url_site',
            'url',
            'sectionName' => function () {
                $type = $this->getSections($this->section_id);

                return [
                    'name' => $type,
                ];
            },
            'typeName' => function () {
                $type = $this->getTypes($this->type_id);

                return [
                    'name' => $type,
                ];
            },
            'section_id',
            'type_id',
            'views',
        ];
    }

    public static function findOne($id)
    {
        $condition = '';

        if (!is_numeric($id) && !is_array($id)) {
            $condition = ['url' => $id];
        } else if (is_numeric($id)) {
            $condition = ['id' => $id];
        }

        $dependency = new DbDependency(['sql' => 'SELECT max(created_at) FROM ads']);

        return parent::getDb()->cache(function () use ($condition) {
            return parent::findOne($condition);
        }, 0, $dependency);
    }

    public function search($params = null)
    {
        $query = Ad::find();

        if (isset($params['search'])) {
            $terms = explode(" ", $params['search']);

            if (count($terms) > 1) {
                $conditionName = ['and'];
                $conditionDesc = ['and'];

                foreach ($terms as $key => $value) {
                    $conditionName[] = ['like', 'LOWER(name)', $value];
                    $conditionDesc[] = ['like', 'LOWER(description)', $value];
                }
            } else {
                $conditionName = ['like', 'LOWER(name)', $params['search']];
                $conditionDesc = ['like', 'LOWER(description)', $params['search']];
            }

            $query->where($conditionName)->orWhere($conditionDesc);
        }

        if (isset($params['filter'])) {
            parse_str($params['filter'], $filter);
        }

        if ($this->my) {
            $query->andWhere(['user_id' => Yii::$app->user->id])
                ->andWhere(['<>', 'status', self::STATUS_DELETED]);
        } else {
            $query->andWhere(['=', 'status', self::STATUS_PUBLISHED]);
        }
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        if (isset($filter)) {

            if (isset($filter['section_id']) && !empty($filter['section_id'])) {
                $query->andFilterWhere(['in', 'section_id', $filter['section_id']]);
            }

            if (isset($filter['type_id']) && !empty($filter['type_id'])) {
                $query->andFilterWhere(['in', 'type_id', $filter['type_id']]);
            }

            if (isset($filter['term']) && !!$filter['term']) {
                $query->andFilterWhere(['between', 'created_at', time() - $filter['term'] * 3600, time()]);
            }

            if (isset($filter['user_id']) && $filter['user_id']) {
                $query->andFilterWhere(['user_id' => $filter['user_id']]);
            }

            if (isset($filter['priceFrom']) && $filter['priceFrom']) {
                $query->andFilterWhere(['>=', 'price', $filter['priceFrom']]);
            }

            if (isset($filter['priceTo']) && $filter['priceTo']) {
                $query->andFilterWhere(['<=', 'price', $filter['priceTo']]);
            }

        };

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

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->user_id = Yii::$app->user->id;
            }

            if (!is_null($this->price)) {
                $this->price = preg_replace("/[^,.0-9]/", '', $this->price);
            }

            $this->status = self::STATUS_NOT_PUBLISHED;

            if (!is_null($this->ended_at) && !is_numeric($this->ended_at)) {
                $this->ended_at = strtotime($this->ended_at);
            } else if (!isset($this->ended_at)) {
                $this->ended_at = null;
            }

            return true;
        }

        return false;
    }

    public function extraFields()
    {
        return [
            'gallery' => function () {
                return $this->getGalleryCover();
            },
        ];
    }
}