<?php

namespace api\modules\v1\controllers;

use api\components\actions\ImageAttachmentAction;
use api\components\ActiveController;
use common\components\ActiveRecord;
use common\models\Company;
use common\models\News;
use common\models\Page;
use Yii;
use yii\web\NotFoundHttpException;


class CompanyController extends ActiveController
{
    public function actions()
    {
        $actions = parent::actions();

        $actions['imgAttachApi'] = [
            'class' => ImageAttachmentAction::class,
            'types' => [
                'user' => Company::class,
            ],
        ];

        $actions['delete'] = array_merge($actions['delete'], [
            'permanent' => false,
            'attribute' => 'status',
            'value' => Company::STATUS_DELETED,
        ]);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator']['optional'] = [
            'index',
            'publish',
            'imgAttachApi',
            'view',
        ];

        return $behaviors;
    }

    public function actionMy()
    {
        /* @var $modelClass \api\models\News */
        $modelClass = new $this->modelClass();
        $modelClass->my = true;

        return $modelClass->search(Yii::$app->request->get());
    }

    public function metaTagsProvider()
    {
        $result = [];

        $page = Page::findOne(['url' => 'company', 'status' => Page::STATUS_PUBLISHED]);

        if (isset($page)) {
            $metaTags = $page->getBehavior('MetaTag')->model;

            $result = [
                'title' => $metaTags->title,
                'keywords' => $metaTags->keywords,
                'description' => $metaTags->description,
            ];
        }

        return $result;
    }

    public function actionPublish($id)
    {
        /** @var $item News */
        $item = Company::findOne($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }

        $item->updateAttributes(['status' => Company::STATUS_MODERATION]);

        // CompanyService::sendNotificationEmail($item);

        return $item->status;
    }

    public function afterFindView(&$model)
    {
        /** @var $model ActiveRecord */
        $model->updateCounters(['views' => 1]);
    }
}
