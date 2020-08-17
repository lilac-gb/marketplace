<?php

namespace api\modules\v1\controllers;

use api\components\actions\GalleryManagerAction;
use api\components\ActiveController;
use common\components\ActiveRecord;
use common\models\News;
use common\models\Page;
use common\services\NewsService;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;


class NewsController extends ActiveController
{
    /**
     * @param string              $action
     * @param \api\models\News|null $model
     * @param array               $params
     * @throws ForbiddenHttpException
     */
   /* public function checkAccess($action, $model = null, $params = [])
    {
        parent::checkAccess($action, $model, $params);
        if ((
                $action == 'update'
                || $action == 'delete'
                || $action == 'create'
                || $action == 'publish'
                || $action == 'galleryApi'
            ) && $model->user_id != Yii::$app->user->id)
        {
            throw new ForbiddenHttpException();
        }
    }*/

    public function actions()
    {
        $actions = parent::actions();

        $actions['galleryApi'] = [
            'class' => GalleryManagerAction::class,
            'types' => [
                'news' => News::class,
            ],
        ];

        $actions['delete'] = array_merge($actions['delete'], [
            'permanent' => false,
            'attribute' => 'status',
            'value' => News::STATUS_DELETED,
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
            'galleryApi',
            'view',
            'main-popular-news',
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

    public function actionPublish($id)
    {

        $news = News::findOne($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }

        // $this->checkAccess('publish', $news, $this->id);
        $news->updateAttributes(['status' => News::STATUS_MODERATION]);
        NewsService::sendNotificationEmail($news);

        return $news->status;
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
