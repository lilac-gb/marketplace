<?php

namespace api\models;

use yii\caching\DbDependency;

class Page extends \common\models\Page
{
    public function fields()
    {
        return [
            'id',
            'url',
            'name',
            'description',
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
        if (!is_numeric($id) && !is_array($id)) {
            $condition = ['url' => $id];
        }

        $condition['status'] = Page::STATUS_PUBLISHED;

        $dependency = new DbDependency(['sql' => 'SELECT max(updated_at) FROM pages']);

        return parent::getDb()->cache(function() use($condition) {
            return parent::findOne($condition);
        }, 0, $dependency);
    }
}
