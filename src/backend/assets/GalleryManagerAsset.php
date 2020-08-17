<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

class GalleryManagerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        //'jquery.iframe-transport.js',
        //'jquery.galleryManager.js',
        'js/jquery.galleryManager.min.js',
        'js/jquery.iframe-transport.min.js',
    ];
    public $css = [
        'css/galleryManager.css?v5',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];

}
