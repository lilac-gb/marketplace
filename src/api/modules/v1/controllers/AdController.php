<?php

namespace api\modules\v1\controllers;

use api\components\actions\ImageAttachmentAction;
use api\components\ActiveController;
use common\components\ActiveRecord;
use common\models\Ad;
use common\models\Page;
use common\services\AdService;
use Yii;
use yii\caching\DbDependency;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class AdController extends ActiveController
{
    public $modelClass = 'api\models\Ad';

    public $serializer = [
        'class' => 'api\components\Serializer',
        'collectionEnvelope' => 'models',
        'metaTagsEnvelope' => '_metaTags',
        'metaTagsProvider' => 'metaTagsProvider',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['index', 'options'];
        $behaviors['authenticator']['optional'] = [
            'view',
            'imgAttachApi',
            'publish',
            'ads-sections',
            'ads-types',
            'main-popular-ads',
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['delete'] = array_merge($actions['delete'], [
            'permanent' => false,
            'attribute' => 'status',
            'value' => Ad::STATUS_DELETED,
        ]);

        $actions['imgAttachApi'] = [
            'class' => ImageAttachmentAction::class,
            'types' => [
                'publication' => Ad::class,
            ],
        ];

        return $actions;
    }

    public function actionMy()
    {
        /* @var $modelClass \api\models\Ad */
        $modelClass = new $this->modelClass();
        $modelClass->my = true;

        return $modelClass->search(Yii::$app->request->get());
    }

    public function metaTagsProvider()
    {
        $page = Page::findOne(['url' => 'ads', 'status' => Page::STATUS_PUBLISHED]);
        if (!$page) {
            throw new NotFoundHttpException();
        }

        return $page->get_metaTags();
    }

    public function actionAdsSections()
    {
        $sections = new Ad();

        $result = [];

        foreach ($sections->getSections() as $key => $val) {
            $result[] = [
                'id' => $key,
                'name' => $val,
            ];
        }

        return $result;
    }

    public function actionAdsTypes()
    {
        $types = new Ad();

        $result = [];

        foreach ($types->getTypes() as $key => $val) {
            $result[] = [
                'id' => $key,
                'name' => $val,
            ];
        }

        return $result;
    }

    public function afterFindView(&$model)
    {
        /** @var $model ActiveRecord */
        $model->updateCounters(['views' => 1]);
    }

    public function actionPublish($id)
    {

        $ad = Ad::findOne($id);
        if (!$ad) {
            throw new NotFoundHttpException();
        }

        $this->checkAccess('publish', $ad, $this->id);
        $ad->updateAttributes(['status' => Ad::STATUS_MODERATION]);
        AdService::sendNotificationEmail($ad);

        return $ad->status;
    }

    public function actionMainPopularAds(): array
    {
        $gteTime = strtotime('-365 days');
        $gteTime = mktime(0, 0, 0,
            (int)date('M', $gteTime),
            (int)date('d', $gteTime),
            (int)date('Y', $gteTime)
        );

        $ads = Ad::find()
            ->onCondition(['>=', 'ads.created_at', $gteTime])
            ->select(['ads.*', 'ads.views AS sort_field'])
            ->orderBy(['sort_field' => SORT_DESC])
            ->where(['status' => Ad::STATUS_PUBLISHED])
            ->limit(6)
            ->all();

        $result = [];

        foreach ($ads as $ad) {
            /** @var $ad \common\models\Ad */
            $result[] = [
                'id' => $ad->id,
                'name' => $ad->name,
                'section' => $ad->section->name,
                'type' => $ad->type->name,
                'price' => $ad->price,
                'url' => $ad->getUrl(),
                'preview' => $ad->getPreview(),
                'views' => $ad->views,
                'author' => $ad->user ? $ad->user->fullName : '',
                'user_id' => $ad->user_id,
                'created_at' => $ad->created_at,
            ];
        }

        return $result;
    }

    /**
     * @param string              $action
     * @param \api\models\Ad|null $model
     * @param array               $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        parent::checkAccess($action, $model, $params);
        if (($action == 'update' ||
                $action == 'delete' ||
                $action == 'publish'
            )
            && $model->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        if ($action == 'view'
            && in_array($model->status, [
                Ad::STATUS_NOT_PUBLISHED,
                Ad::STATUS_DELETED,
            ])
            && ($model->user_id != Yii::$app->user->id || $model->role != 'admin')) {
            throw new NotFoundHttpException();
        }
    }
}