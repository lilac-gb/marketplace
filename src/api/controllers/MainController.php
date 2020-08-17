<?php
namespace api\controllers;

use api\components\Controller;
use api\models\Page;
use common\models\Ad;
use common\models\News;
use Yii;

class MainController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['index', 'main-search'];

        return $behaviors;
    }

    public function actionIndex() {
        return ['message' => 'ok'];
    }

    public function metaTagsProvider()
    {
        $result = [];

        $page = Page::findOne(['url' => '/']);

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

    public function actionMainSearch()
    {
        if (Yii::$app->request->post('query')) {
            $ad = Ad::find();
            $news = News::find();

            $search = Yii::$app->request->post('query');

            if (isset($search)) {
                $terms = explode(" ", $search);

                if (count($terms) > 1) {
                    $conditionName = ['and'];
                    $conditionDesc = ['and'];

                    foreach ($terms as $key => $value) {
                        $conditionName[] = ['like', 'name', $value];
                        $conditionDesc[] = ['like', 'description', $value];
                    }
                } else {
                    $conditionName = ['like', 'name', $search];
                    $conditionDesc = ['like', 'description', $search];
                }

                $adsAll = $ad->where($conditionName)
                    //->orWhere($conditionDesc)
                    ->limit(5)
                    ->all();

                $newsAll = $news->where($conditionName)
                    //->orWhere($conditionDesc)
                    ->limit(5)
                    ->all();

                $result = [];

                foreach ($adsAll as $ad) {
                    /** @var $ad Ad */
                    $result[] = [
                        'id' => $ad->id,
                        'name' => $ad->name,
                        'url' => '/ads/' . $ad->url,
                    ];
                }

                foreach ($newsAll as $new) {
                    /** @var $new News */
                    $result[] = [
                        'id' => $new->id,
                        'name' => $new->name,
                        'url' => '/publications/' . $new->url,
                    ];
                }

                shuffle($result);

                return $result;
            }
        }

        return '';
    }
}