<?php

namespace api\modules\v1\controllers;
use api\components\ActiveController;
use common\components\ActiveRecord;
use common\models\News;
use common\models\Page;
use Yii;

class NewsController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['options'];
        $behaviors['authenticator']['optional'] = [
            'index',
            'view',
            'main-popular-news',
        ];

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
        /** @var $model ActiveRecord */
        $model->updateCounters(['views' => 1]);
    }

    public function actionMainPopularNews(): array
    {
        $gteTime = strtotime('-365 days');
        $gteTime = mktime(0, 0, 0,
            (int)date('M', $gteTime),
            (int)date('d', $gteTime),
            (int)date('Y', $gteTime)
        );

        $news = News::find()
            ->onCondition(['>=', 'news.created_at', $gteTime])
            ->select(['news.*', 'news.views AS sort_field'])
            ->orderBy(['sort_field' => SORT_DESC])
            ->where(['status' => News::STATUS_PUBLISHED])
            ->limit(4)
            ->all();

        $result = [];

        foreach ($news as $new) {
            /** @var $new \common\models\News */
            $result[] = [
                'id' => $new->id,
                'name' => $new->name,
                'url' => $new->url,
                'coverImages' => [
                    'i600x250' => $new->getPreview('i600x250'),
                ],
                'views' => $new->views,
                'author' => $new->user ? $new->user->fullName : '',
                'user_id' => $new->user_id,
                'created_at' => $new->created_at,
            ];
        }

        return $result;
    }
}
