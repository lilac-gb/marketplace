<?php

namespace backend\components\actions;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\ActiveForm;

class UpdateAction extends Action
{
    public $afterLoad;
    public $beforeRender;
    /**
     * @var string the scenario to be assigned to the model before it is validated and updated.
     */
    public $scenario = Model::SCENARIO_DEFAULT;


    /**
     * Updates an existing model.
     * @param string $id the primary key of the model.
     * @return string|array
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id=null)
    {
        $this->controller->layout = false;

        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            call_user_func_array($this->afterLoad, [&$model]);

            Yii::$app->response->format = 'json';

            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                return json_encode(['status' => 'success']);
            } else {
                $errors = (array)$model->errors;
                $message = reset($errors);

                return json_encode(['status' => 'error', 'message' => $message]);
            }
        }

        call_user_func_array($this->beforeRender, [&$model]);

        return $this->controller->renderAjax('update', [
            'model' => $model,
        ]);
    }
}
