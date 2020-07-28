<?php

namespace api\modules\v1\controllers;
use api\components\ActiveController;
use common\components\ActiveRecord;
use common\models\Page;
use Yii;

class NewsController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator']['optional'] = ['index', 'view'];

        return $behaviors;
    }

    public function metaTagsProvider()
    {
        $result = [];

        $page = Page::findOne(['url' => 'news', 'status' => Page::STATUS_PUBLISHED]);

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

    public function afterFindView(&$model)
    {
        if ($model->user_id !== Yii::$app->user->id) {
            /** @var $model ActiveRecord */
            $model->updateCounters(['views' => 1]);
        }
    }
}
