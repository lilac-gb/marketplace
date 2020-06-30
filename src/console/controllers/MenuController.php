<?php

namespace console\controllers;

use common\models\Menu;
use common\models\MenuLink;

class MenuController extends \yii\console\Controller
{
    public function actionGenerateDefault() {
        $header = Menu::findOne(['name' => 'header']);

        if (!$header) {
            $header = new Menu();

            $header->name = 'header';
            $header->title = 'Хэдер';
            $header->status = 1;

            $header->save(false);

            $links = [
                ['title' => 'Объявления', 'url' => '/ads'],
                ['title' => 'Публикации', 'url' => '/articles'],
                ['title' => 'Компании', 'url' => '/companies'],
            ];

            foreach ($links as $link) {
                $menu_link = new MenuLink();
                $menu_link->menu_id = $header->id;
                $menu_link->title = $link['title'];
                $menu_link->url = $link['url'];
                $menu_link->class = '';
                $menu_link->status = 1;

                $menu_link->save(false);
            }
        }
    }
}