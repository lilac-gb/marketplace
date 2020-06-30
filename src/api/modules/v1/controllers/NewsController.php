<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 04.03.17
 * Time: 19:41
 */
namespace api\modules\v1\controllers;
use api\components\ActiveController;
use common\models\Page;

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

        $page = Page::findOne(['url' => 'news']);

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
}
