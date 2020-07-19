<?php

namespace console\controllers;

use common\models\Menu;
use common\models\MenuLink;

class MenuController extends \yii\console\Controller
{
    public function actionGenerateDefault()
    {
        $header = Menu::findOne(['name' => 'header']);

        if (!$header) {
            $header = new Menu();

            $header->name = 'header';
            $header->title = 'Хэдер';
            $header->status = 1;

            $header->save(false);

            $links = [
                ['title' => 'Объявления', 'url' => '/ads'],
                ['title' => 'Публикации', 'url' => '/publications'],
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

    public function actionGenerateFooter()
    {
        $footer = Menu::findOne(['name' => 'footer']);

        if (empty($footer)) {
            $footer = new Menu();
            $footer->name = 'footer';
            $footer->title = 'Футер';
            $footer->levels = 1;
            $footer->status = 2;
            $footer->save(false);
        }

        $links = [
            [
                'title' => 'Связаться',
                'url' => '#',
                'children' => [
                    [
                        'title' => 'Контакты',
                        'url' => '/contacts',
                    ],
                    [
                        'title' => 'Обратная связь',
                        'url' => '#',
                    ],
                ],
            ],
            [
                'title' => 'Информация',
                'url' => '#',
                'children' => [
                    [
                        'title' => 'Условия создания контента',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Политика конфиденциальности',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Реклама',
                        'url' => '#',
                    ],
                ],
            ],
            [
                'title' => 'Разделы',
                'url' => '#',
                'children' => [
                    [
                        'title' => 'Объявления',
                        'url' => '/ads',
                    ],
                    [
                        'title' => 'Публикации',
                        'url' => '/publications',
                    ],
                    [
                        'title' => 'Компании',
                        'url' => '/companies',
                    ],
                ],
            ],
        ];

        foreach ($links as $link) {
            $menu_link = new MenuLink();
            $menu_link->menu_id = $footer->id;
            $menu_link->title = $link['title'];
            $menu_link->url = $link['url'];
            $menu_link->class = '';
            $menu_link->status = 1;
            $menu_link->save(false);

            if (isset($link['children']) && !empty($link['children'])) {
                foreach ($link['children'] as $child) {
                    $menu_child = new MenuLink();
                    $menu_child->menu_id = $footer->id;
                    $menu_child->title = $child['title'];
                    $menu_child->url = $child['url'];
                    $menu_child->parent_id = $menu_link->id;
                    $menu_child->class = '';
                    $menu_child->status = 1;
                    $menu_child->save(false);
                }
            }
        }
    }
}