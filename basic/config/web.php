<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'UjUAwk8grBauCDdxIncJGcCboUWO88eJ',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error'],
                    'categories' => ['yii\*'],
                    'message' => [
                       'from' => ['bot@womanclothing.top'],
                       'to' => ['rolenweb@mail.ru'],
                       'subject' => 'Woman Clothing',
                    ],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'search-data-api',
                    'suffix' => '',
                    'only' => ['create','index','view','delete'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'search-data-shedule-api',
                    'suffix' => '',
                    'only' => ['view','update'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'product-api',
                    'suffix' => '',
                    'only' => ['search-data','view','next'],
                    'extraPatterns' => [
                        'GET search-data/<id>' => 'search-data',
                        'GET next/<id>' => 'next',
                    ],
                ],
                'p/<slug:[A-Za-z0-9 -_.]+>' => 'site/product',
                [
                   'pattern'=>'sitemap-static-page',
                   'route' => 'site/sitemap-static-page',
                   'suffix' => '.xml',
                ],
                [
                   'pattern'=>'sitemap-<slug:[A-Za-z0-9 -_.]+>',
                   'route' => 'site/sitemap-page',
                   'suffix' => '.xml',
                ],
                [
                   'pattern'=>'sitemap',
                   'route' => 'site/sitemap',
                   'suffix' => '.xml',
                ],
                'link/<action>' => 'link/<action>',
                'category/<action>' => 'category/<action>',
                'product/<action>' => 'product/<action>',
                'product-property/<action>' => 'product-property/<action>',
                'search-data/<action>' => 'search-data/<action>',
                'loginrolen' => 'site/login',
                'logout' => 'site/logout',
                'contact' => 'site/contact',
                'captcha' => 'site/captcha',
                '<cat1:[A-Za-z0-9 -_.]+>/<cat2:[A-Za-z0-9 -_.]+>/' => 'site/index',
                '<cat1:[A-Za-z0-9 -_.]+>/' => 'site/index',
                '' => 'site/index',
                
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
