<?php

namespace api\models;

use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;

class Menu extends \common\models\Menu
{
    public function fields()
    {
        return [
            'id',
            'name',
            'title',
            'updated_at',
            'levels' => function($model) {
                return $model->levels;
            },
            'children' => function($model) {
                return $model->getItemsArray();
            }
        ];
    }

    public static function findOne($condition)
    {
        if (!is_numeric($condition) && !is_array($condition)) {
            $condition = ['name' => $condition];
        }

        $dependency = new DbDependency(['sql' => 'SELECT max(updated_at) FROM menus']);

        return parent::getDb()->cache(function() use($condition) {
            return parent::findOne($condition);
        }, 0, $dependency);
    }

    public function search($params = null)
    {
        $query = self::find()->with(['items']);

        if (isset($params['name'])) {
            $query->andWhere(['name' => explode(',', $params['name'])]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //TODO: think how to add dependency also by menus table
        //$dependency = new DbDependency(['sql' => 'SELECT max(time_update) FROM menus_links']);
        //$this::getDb()->cache(function() use ($dataProvider) {
        //    return $dataProvider->prepare();
        //}, 0, $dependency);

        return $dataProvider;
    }
}
