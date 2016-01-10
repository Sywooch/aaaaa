<?php
return $config = [
    'id' => 'aaaaakz',
    'language' => 'ru',
    'basePath' => __DIR__ . '/protected',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=aaaaa',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/q/<question_id:[\d]+>-<url:[\S\s]+>.html' => 'site/question',
                '/page-<param:[^\.]+>.html' => 'site/page',
                '/<file:[\w_-]+>.xml' => 'site/sitemap',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '/<action:[\w-]+>' => 'site/<action>',
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'rkvGRePaiPNtukv660iX51Je9PP0NLIf',
        ],
    ],
    'params' => [
        'adminEmail' => 'admin@aaaaa.kz',
        'name' => 'Aaaaa.KZ',
        'siteUrl' => 'http://aaaaa.kz/',
    ],
];