<?php

namespace api\models;

use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;

class Company extends \common\models\Company
{
    public $my = false;

    public function fields()
    {
        return [
            'id',
            'url',
            'name',
            'owner_id',
            'role',
            'vat',
            'id_number',
            'working_days',
            'time_from',
            'time_to',
            'description',
            'email',
            'site',
            'phone',
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
            'images' => function () {
                return [
                    'logo' => $this->getLogo('i300x300') ?? '',
                    'original' => $this->getLogo('original') ?? '',
                    'preview' => $this->getLogo('preview') ?? '',
                ];
            },
            'views',
            'status',
            'created_at',
        ];
    }

    public function extraFields()
    {
        return [
            '_metaTags',
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

        return parent::findOne($condition);
    }

    public function search($params = null)
    {
        $query = self::find();

        if (isset($params)) {
            $this->load($params);
        }

        if ($this->my) {
            $query->andWhere(['owner_id' => Yii::$app->user->id])
                ->andWhere(['<>', 'status', self::STATUS_DELETED]);
        } else {
            $query->andWhere(['=', 'status', self::STATUS_PUBLISHED]);
        }

        $query->andFilterWhere(['like', 'LOWER(name)', $this->name]);

        if (isset($params['owner_id'])) {
            $query->andFilterWhere(['owner_id' => $params['owner_id']]);
        }

        /*if (isset($params['sortBy']) && isset($params['sortDesc'])) {
            $sortBy = $params['sortBy'];
            $sortDesc = $params['sortDesc'];
            $query->orderBy("{$sortBy} {$sortDesc}");
        }*/

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
