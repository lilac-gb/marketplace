<?php

namespace backend\modules\content\controllers;

use backend\components\ActiveController;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\models\Menu;
use Yii;


/**
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends ActiveController
{
    public $modelClass = 'common\models\Menu';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update']);

        return $actions;
    }

    /**
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */

    public function actionUpdate($id=null)
    {
        $this->layout = false;
        $modelClass = $this->modelClass;

        /**
         * @var $modelClass Menu
         */

        $model = $id ? $modelClass::findOne($id) : null;

        if (!isset($model)) {
            $model = new $modelClass();
        }

        $new = $model->isNewRecord;

        if($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->isAjax && isset($_POST['ajax']))
            {
                Yii::$app->response->format = 'json';
                return ActiveForm::validate($model);
            }

            if ($model->save()) {
                if($new)
                    $this->redirect(['/content/menu-link/index', 'menu_id' => $model->id]);
                else
                    return false;
            }
            else
                return $model->errors;
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }
}
