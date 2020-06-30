<?php

namespace api\components\actions;

use Yii;
use yii\rest\Action;

class ViewAction extends Action
{
    public $afterFind;

    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        call_user_func_array($this->afterFind, [&$model]);

        return $model;
    }
}
