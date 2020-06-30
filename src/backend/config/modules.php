<?php

$result = [];

$path = Yii::getAlias('@backend/modules');

if (is_dir($path)) {
    foreach (scandir($path) as $module) {
        if ($module[0] == '.') {
            // skip ".", ".." and hidden files
            continue;
        }

        $result[$module] = [
            'class' => "backend\modules\\$module\\Module"
        ];
    }
}

return $result;