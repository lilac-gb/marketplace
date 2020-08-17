<?php

namespace common\components;

use v0lume\yii2\metaTags\models\MetaTag;
use yii;


/**
 * @property mixed translation
 */
class ActiveRecord extends yii\db\ActiveRecord
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        parent::afterDelete();
    }

    public function get_metaTags()
    {
        $model = MetaTag::findOne([
            'model_id' => $this->id,
            'model'  => (new \ReflectionClass($this))->getShortName()
        ]);

        if (!$model) {
            return null;
        }

        return $model->toArray(['title', 'keywords', 'description']);
    }
}
