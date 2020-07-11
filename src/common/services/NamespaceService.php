<?php

namespace common\services;


class NamespaceService
{
    const NEWS = 'news';

    static $models = [
        self::NEWS => 'common\models\News',
    ];

    public static function getModel(string $shortName)
    {
        return isset(self::$models[$shortName]) ? self::$models[$shortName] : false;
    }
}