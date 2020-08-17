<?php

return [
    'Docker' => [
        'path' => 'docker',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'console/runtime',
            'api/runtime',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'backend/config/params.php',
            'common/config/params.php',
            'api/config/params.php',
            'common/config/codeception-local.php',
            'api/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'api/runtime',
            'backend/runtime',
            'backend/web/assets',
            'frontend/web',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'api/config/main-local.php',
            'backend/config/main-local.php',
        ],
    ],
];
