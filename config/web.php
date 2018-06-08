<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$rabbitmq = require __DIR__ . '/rabbitmq.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
           'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'askjdglkaudshfanscfavbgncgnzgnzdjsb22222222',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'rabbitmq' => $rabbitmq,

        'mail' => [
             'class' => 'yii\swiftmailer\Mailer',
             'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST'),
                'username' => getenv('SMTP_USER'),
                'password' => getenv('SMTP_PASS'),
                'port' => getenv('SMTP_PORT'),
                'encryption' => getenv('SMTP_ENCR'),
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        
        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => getenv('MEMCACHED_HOST_1'),
                    'port' => getenv('MEMCACHED_PORT_1'),
                    'weight' => 100,
                ],
            ],
        ],

        'user' => [
            'identityClass' => 'app\modules\apiv1\models\Users',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                'health' => 'apiv1/health',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/users','extraPatterns' => ['POST login' => 'login','POST register' => 'register', 'GET me' => 'me', 'GET me2' => 'me2' ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/environments', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/deployments', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/components', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/environment-components', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'apiv1/deployment-environment-components', 'pluralize'=>false],
            ],
        ]
    ],
    'modules' => [
        'apiv1' => [
            'class' => 'app\modules\apiv1\ApiV1Module',
            'healthchecks' => [
                'application',
                'db',
                'cache',
                'rabbitmq',
                'jenkins',
                'custom' => function() {
                    return [
                        'status' => false,
                        'info' => (object) [],
                        'error' => 'Deu Errado'   
                    ];

                    return [
                        'status' => true,
                        'info' => (object) [],
                        'error' => ''   
                    ];
                }
            ]
        ]
    ],
    'params' => $params,
];

if (true) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', 'localhost', '*'],
    ];
}

return $config;