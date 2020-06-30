<?php

namespace backend\widgets;

use iutbay\yii2kcfinder\KCFinderAsset;
use Yii;
use yii\helpers\ArrayHelper;

class CKEditor extends \dosamigos\ckeditor\CKEditor
{
    public $enableKCFinder = true;

    public $clientOptions = [
        'disabled'=>false,
        'denyZipDownload' => true,
        'denyUpdateCheck' => true,
        'denyExtensionRename' => true,
        'theme' => 'default',
        'access' =>[    // @link http://kcfinder.sunhater.com/install#_access
            'files' =>[
                'upload' => false,
                'delete' => false,
                'copy' => false,
                'move' => false,
                'rename' => false,
            ],
            'dirs' =>[
                'create' => false,
                'delete' => false,
                'rename' => false,
            ],
        ],
        'types'=>[  // @link http://kcfinder.sunhater.com/install#_types
            'files' => [
                'type' => '',
            ],
            'images' => [
                'type' => '*img',
            ],
        ],
        'thumbsDir' => '.thumbs',
        'thumbWidth' => 100,
        'thumbHeight' => 100,
    ];

    public function run()
    {
        // kcfinder options
        // http://kcfinder.sunhater.com/install#dynamic
        $kcfOptions = array_merge($this->clientOptions, [
            'disabled' => false,
            'uploadURL' => Yii::$app->params['domainFrontend'] . '/uploads',
            'uploadDir'=> Yii::getAlias('@frontend/web/uploads'),
            'access' => [
                'files' => [
                    'upload' => true,
                    'delete' => true,
                    'copy' => true,
                    'move' => true,
                    'rename' => true,
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => true,
                    'rename' => true,
                ],
            ],
        ]);

        // Set kcfinder session options
        Yii::$app->session->set('KCFINDER', $kcfOptions);

        parent::run();
    }

    /**
     * Registers CKEditor plugin
     */
    protected function registerPlugin()
    {
        if ($this->enableKCFinder)
        {
            $this->registerKCFinder();
        }

        parent::registerPlugin();
    }

    /**
     * Registers KCFinder
     */
    protected function registerKCFinder()
    {
        $register = KCFinderAsset::register($this->view);
        $kcfinderUrl = $register->baseUrl;

        $browseOptions = [
            'filebrowserBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=files',
            'filebrowserUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=files',
            'filebrowserImageBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=images',
            'filebrowserImageUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=images',
        ];

        $this->clientOptions = ArrayHelper::merge($browseOptions, $this->clientOptions);
    }
}