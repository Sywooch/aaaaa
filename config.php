<?php
$is_console = PHP_SAPI == 'cli' || (!isset($_SERVER['DOCUMENT_ROOT']) && !isset($_SERVER['REQUEST_URI']));

return $config = [
    'id' => 'aaaaakz',
    'language' => 'ru',
    'basePath' => __DIR__ . '/protected',
    'components' => [
        'user' => $is_console ? ['class' => ''] : [
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
                '/search' => 'site/index',
                '/<action:[\w-]+>' => 'site/<action>',
                '/post/<post_id:[\d]+>' => 'site/post',
                '/<file:[\w_-]+>.xml' => 'site/sitemap',
//                '/q/<question_id:[\d]+>-<url:[\S\s]+>.html' => 'site/question',
//                '/page-<param:[^\.]+>.html' => 'site/page',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
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