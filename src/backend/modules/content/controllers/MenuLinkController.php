<?php

namespace backend\modules\content\controllers;

use backend\components\ActiveController;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use common\models\MenuLink;
use yii\bootstrap\Modal;
use common\models\Menu;
use Yii;

/**
 * MenuLinkController implements the CRUD actions for MenuLink model.
 */
class MenuLinkController extends ActiveController
{
    public $modelClass = 'common\models\MenuLink';
    public $modalSize = Modal::SIZE_DEFAULT;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['update']);

        return $actions;
    }

    /**
     * Lists all MenuLink models.
     * @return mixed
     */
    public function actionIndex($menu_id)
    {
        $menu = Menu::findOne($menu_id);
        if ($menu == null) {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => MenuLink::find()->where(['menu_id' => $menu_id]),
            'sort' => [
                // Set the default sort by name ASC and created_at DESC.
                'defaultOrder' => [
                    'order' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'menu' => $menu,
        ]);
    }

    /**
     * Updates an existing MenuLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = null, $menu_id)
    {
        $this->layout = false;
        $modelClass = $this->modelClass;

        /**
         * @var $modelClass MenuLink
         */

        $model = $id ? $modelClass::findOne($id) : null;

        if (!isset($model)) {
            $model = new $modelClass();
        }

        $menu = Menu::findOne($menu_id);

        if ($menu == null || (!empty($model->menu_id) && $model->menu_id !== (int)$menu_id)) {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }

        $model->menu_id = $menu_id;

        Yii::$app->response->format = 'json';

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && isset($_POST['ajax'])) {
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->save()) {
                return false;
            } else {
                return $model->errors;
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'menu' => $menu,
        ]);
    }
}
