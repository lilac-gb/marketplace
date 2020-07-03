<?php

$rules = [];

$controllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@api/controllers'), ['recursive' => true]);

foreach($controllers as $path) {
    $className = "\api\controllers\\" . str_replace('.php', '', basename($path));

    if (property_exists($className, 'urlRule')) {
        $rules[] = $className::$urlRule;
    }
}

return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => array_merge($rules, [
        '/' => 'main/index',
    ]),
];