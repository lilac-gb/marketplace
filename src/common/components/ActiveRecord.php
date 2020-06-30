<?php

namespace common\components;

//use common\models\History;
use common\models\MetaTag;
use yii;


/**
 * @property mixed translation
 */
class ActiveRecord extends yii\db\ActiveRecord
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        History::saveToHistory($this, $insert ? History::ACTION_ADD : History::ACTION_UPDATE);
    }

    public function afterDelete()
    {
//        History::saveToHistory($this, History::ACTION_DELETE);

        parent::afterDelete();
    }

    public function get_metaTags()
    {
        $model = MetaTag::findOne([
            'model_id' => $this->model->id,
            'model'  => (new \ReflectionClass($this))->getShortName()
        ]);

        if (!$model) {
            return null;
        }

        return $model->toArray(['title', 'keywords', 'description']);
    }
}
