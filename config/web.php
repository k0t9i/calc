<?php

Yii::$container->set('app\models\calc\interfaces\ICalculator', [
    'class' => 'app\models\calc\PostfixNotationCalculator'
]);
Yii::$container->set('app\models\calc\interfaces\IConverter', [
    'class' => 'app\models\calc\PostfixNotationConverter'
]);
Yii::$container->set('app\models\calc\interfaces\ITokenizer', [
    'class' => 'app\models\calc\Tokenizer'
]);

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'calc',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'default',
    'components' => [
        'user' => [],
        'request' => [
            'cookieValidationKey' => 'YbSVf1V9sDjb2Lu4a8vwlUp_qAeT1g9-',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'calc' => [
            'class' => 'app\components\Calculator'
        ]
    ],
    'params' => $params,
];
if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
