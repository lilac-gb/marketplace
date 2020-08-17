<?php

namespace backend\modules\news\controllers;

use zxbodya\yii2\galleryManager\GalleryManagerAction;
use backend\components\ActiveController;
use common\models\News;
use yii\bootstrap\Modal;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends ActiveController
{
    public $modelClass = 'common\models\News';
    public $modalSize = Modal::SIZE_LARGE;

    public function actions()
    {
        $actions = parent::actions();

        $actions['update']['afterLoad'] = [$this, 'afterLoad'];

        $actions['galleryApi'] = [
            'class' => GalleryManagerAction::class,
            'types' => [
                'news' => News::class,
            ],
        ];

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'actions' => [
                    'index', 'delete', 'update', 'set', 'galleryApi',
                ],
                'allow' => true,
                'roles' => ['@'],
            ],
        ];

        return $behaviors;
    }

    public function afterLoad(&$model)
    {
        /** @var $model News*/
        $model->detachBehavior('TimeStamp');

        if(empty($model->created_at)) {
            $model->created_at = time();
        }

        if(empty($model->published_at)) {
            $model->published_at = 0;
        }

        if(!is_numeric($model->created_at)) {
            $model->created_at = strtotime($model->created_at);
        }

        if(!is_numeric($model->published_at)){
            $model->published_at = strtotime($model->published_at);
        }
    }

    public function beforeRender(&$model)
    {
        /** @var $model News*/
        if(!empty($model->created_at)) {
            $model->created_at = date('d.m.Y H:i', $model->created_at);
        }

        if(!empty($model->published_at)) {
            $model->published_at = date('d.m.Y H:i', $model->published_at);
        } else {
            $model->published_at = '';
        }
    }
}
