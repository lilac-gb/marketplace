<?php
namespace api\controllers;

use api\components\Controller;
use api\models\Page;

class MainController extends Controller
{
    public function behaviors() {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['optional'][] = 'index';

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
}