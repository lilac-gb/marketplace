<?php

return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '/',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '<module>/<controller>/<action>/<id:[a-z0-9-_]+>' => '<module>/<controller>/<action>',
    ],
];
