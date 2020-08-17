<?php

namespace backend\components\actions;

use common\models\History;
use common\models\ObjectOrder;
use common\services\ObjectOrderService;
use common\services\ObjectService;
use Yii;
use yii\db\ActiveRecord;

class SetAction extends Action
{
    /**
     * Update a model attribute with given value.
     * @param mixed $id id of the model to be updated.
     * @param mixed $attr id of the model to be updated.
     * @param mixed $val id of the model to be updated.
     * @return string redirect to back
     */
    public function run($id, $attr, $val)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if (isset($model->$attr)) {
            $model->updateAttributes([$attr => $val]);

            if (isset($model->updated_at) || isset($model->time_update)) {
                // touch update time to reset cache
                $model->touch(isset($model->updated_at) ? 'updated_at' : 'time_update');
            }
        }

        return $this->controller->redirect($_SERVER['HTTP_REFERER']);
    }
}
