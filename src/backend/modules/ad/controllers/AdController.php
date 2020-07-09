<?php

namespace backend\modules\ad\controllers;

use backend\components\ActiveController;
use common\models\Ad;
use yii\bootstrap\Modal;
use zxbodya\yii2\galleryManager\GalleryManagerAction;

/**
 * AdsController implements the CRUD actions for Ads model
 */
class AdController extends ActiveController
{
    public $modelClass = 'common\models\Ad';
    public $modalSize = Modal::SIZE_LARGE;

    public function actions()
    {
        $actions = parent::actions();

        $actions['update']['afterLoad'] = [$this, 'afterLoad'];

        $actions['galleryApi'] = [
            'class' => GalleryManagerAction::class,
            'types' => [
                'ad' => Ad::class,
            ],
        ];

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => ['index', 'view', 'update', 'set', 'delete', 'upload', 'galleryApi'],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function afterLoad(Ad &$model)
    {
        $model->detachBehavior('TimeStamp');

        $model->updated_at = time();

        if (empty($model->created_at))
            $model->created_at = time();

        if (!is_numeric($model->created_at))
            $model->created_at = strtotime($model->created_at);

        if (empty($model->ended_at))
            $model->ended_at = time();

        if (!is_numeric($model->ended_at))
            $model->ended_at = strtotime($model->ended_at);
    }

    public function beforeRender(&$model)
    {
        /** @var $model Ad */
        if (!empty($model->created_at))
            $model->created_at = date('d.m.Y H:i', $model->created_at);

        if (!empty($model->ended_at))
            $model->ended_at = date('d.m.Y H:i', $model->ended_at);

        $model->updated_at = date('d.m.Y H:i', $model->updated_at);
    }
}
