<?php
$params = require(__DIR__ . '/params.php');
$webConfig = require(__DIR__ . '/web.php');

/**
 * Application configuration shared by all test types
 */
return \yii\helpers\ArrayHelper::merge($webConfig, [
    'id' => 'calc-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
]);
