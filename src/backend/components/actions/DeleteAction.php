<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 28.03.16
 * Time: 19:57
 */

namespace backend\components\actions;


use Yii;
use yii\base\Response;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

class DeleteAction extends Action
{
    public $permanent = true;
    public $attribute = 'deleted';
    public $value = 1;

    /**
     * Deletes a model.
     * @param mixed $id id of the model to be deleted.
     * @return Response redirect
     */
    public function run($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($this->permanent) {
            $model->delete();
        } else {
            $model->updateAttributes([$this->attribute => $this->value]);
        }

        return $this->controller->redirect($_SERVER['HTTP_REFERER']);
    }
}
